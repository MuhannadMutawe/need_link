<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
        // not implemented yet !
        // return view('dashboard.admin.categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name|max:255',
            'icon' => 'nullable|string',
        ], [
            'name.required' => 'اسم الفئة مطلوب',
            'name.unique' => 'اسم الفئة موجود مسبقاً'
        ]);

        $category = Category::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم إضافة الفئة بنجاح',
                'category' => $category
            ], 201);
        }

        return redirect()->back()->with('success', 'تم إضافة الفئة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(["ok"=>"true"]);
        // return redirect()->back()->with('success', 'تم حذف الفئة بنجاح');
    }
}
