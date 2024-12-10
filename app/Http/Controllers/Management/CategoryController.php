<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all categories to pass to the view
        $categories = Category::paginate(3);
        return view('management.category', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|unique:categories|max:255',
        ]);

        // Create the new category
        Category::create([
            'name' => $request->name,
        ]);

        // Redirect with success message
        return redirect()->route('category.index')->with('success', 'Category added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Find the category to edit
        $category = Category::findOrFail($id);
        return view('management.edit_category', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the input
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        // Find and update the category
        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->name,
        ]);

        // Redirect with success message
        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find and delete the category
        $category = Category::findOrFail($id);
        $category->delete();

        // Redirect with success message
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
}
