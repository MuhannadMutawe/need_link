<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        return view('main.landing-page');
    }

    public function browseRequests(Request $request)
    {
        $categories = \App\Models\Category::all();

        $query = \App\Models\ServiceRequest::with(['user', 'categories'])
            ->withCount('offers')
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->where('status', 'open');

        if ($request->has('category_name') && $request->category_name) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.name', $request->category_name);
            });
        }

        $requests = $query->latest()->paginate(12)->appends($request->all());

        return view('main.requests', compact('requests', 'categories'));
    }
}
