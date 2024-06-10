<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategory(Request $request)
    {
        try {
            $request->validate([
                "name" => "required",
                'image' => 'max:2048',
                'subcategories' => 'required|array',
            ]);
            $category = new Category();
            $filename = "";
            if ($request->hasFile('image')) {
                $filename = $request->file('image')->store('category', 'public');
            }

            $category->name = $request->name;
            $category->url = $filename;
            $category->save();

            $subcategories = $request->input('subcategories');
            foreach ($subcategories as $subCat) {
                $subcategory = new SubCategory();
                $subcategory->name = $subCat;
                $subcategory->child_id = $category->id;
                $subcategory->save();
            }
            return response()->json(['success' => true, 'message' => 'Category Created Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating category: ' . $e->getMessage()], 400);
        }
    }
    public function getAllCategories()
    {
        $categories = Category::all();

        $responseData = [];

        foreach ($categories as $category) {
            $subCategories = SubCategory::where('child_id', $category->id)->get();
            $productData = [
                'id' => $category->id,
                'name' => $category->name,
                'image' => $category->url,
                'subcategory' => []
            ];
            foreach ($subCategories as $subCategory) {
                $productData['subcategory'][] = [
                    'id' => $subCategory->id,
                    'child_id' => $subCategory->child_id,
                    'name' => $subCategory->name,
                ];
            }
            $responseData[] = $productData;
        }
        return response()->json($responseData);
    }
    public function getSubCategoriesByCategoryId($categoryId)
    {
        $subCategories = SubCategory::where('child_id', $categoryId)->get();

        $responseData = [];

        foreach ($subCategories as $subCategory) {
            $responseData[] = [
                'id' => $subCategory->id,
                'child_id' => $subCategory->child_id,
                'name' => $subCategory->name,
            ];
        }

        return response()->json($responseData);
    }
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}
