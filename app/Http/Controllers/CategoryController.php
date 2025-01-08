<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CategoriesRequest\StoreCategoryRequest;
use App\Http\Requests\CategoriesRequest\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $userId = auth()->id(); 
        $categories = Category::where('user_id', $userId)->with('parent')->paginate(10);
        $parentCategories = Category::all();
        return view('pages.categories.index', compact('categories', 'parentCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //
        Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'parent_id' => $request->parent_id,
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('categories.index')->with('success', 'new category added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return response()->json([
                'message' => 'Category deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete the category. Please try again later.',
            ], 500);
        }
    }
    public function checkCategoryName(Request $request)
    {
        $exists = Category::where('name', $request->category_name)->exists();
        return response()->json(['exists' => $exists]);
    }
}
