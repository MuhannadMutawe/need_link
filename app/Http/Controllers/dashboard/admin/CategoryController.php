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

        if (request()->expectsJson()) {
            return response()->json($categories);
        }

        return view('dashboard.admin.categories', compact('categories'));
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon' => 'nullable|string',
        ], [
            'name.required' => 'اسم الفئة مطلوب',
            'name.unique' => 'اسم الفئة موجود مسبقاً'
        ]);

        $category->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'تم تحديث الفئة بنجاح',
                'category' => $category
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث الفئة بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        if (request()->expectsJson()) {
            return response()->json(["ok"=>"true"]);
        }
        
        return redirect()->back()->with('success', 'تم حذف الفئة بنجاح');
    }
}
