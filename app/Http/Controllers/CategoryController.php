<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Public: list all categories.
     */
    public function index()
    {
        return response()->json(Category::orderBy('id')->get());
    }

    /**
     * Admin: create a new category.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|string|max:255|unique:categories,slug',
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:500',
            'subcategories' => 'required|json',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create([
            'slug' => $request->input('slug'),
            'name' => $request->input('name'),
            'tagline' => $request->input('tagline'),
            'image' => $request->input('image'),
            'subcategories' => json_decode($request->input('subcategories'), true),
        ]);

        return response()->json($category, 201);
    }

    /**
     * Admin: delete a category.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
