<?php

namespace App\Http\Controllers\main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        return view('main.landing-page');
    }

    public function browseRequests()
    {
        $requests = \App\Models\ServiceRequest::with(['user', 'categories'])
            ->withCount('offers')
            ->where('expires_at', '>', now())
            ->where('status', 'open')
            ->latest()
            ->paginate(12);

        return view('main.requests', compact('requests'));
    }
}
