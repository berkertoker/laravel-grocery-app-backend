<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\PopularCategory;
use App\Models\PopularProduct;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class PopularityController extends Controller
{
    public function createPopularCategory(Request $request)
    {
        try {
            $request->validate([
                'popular_category_id' => 'required|integer',
                'popularity_score' => 'required|numeric',
            ]);

            $popularCategory = new PopularCategory();
            $popularCategory->popular_category_id = $request->popular_category_id;
            $popularCategory->popularity_score = $request->popularity_score;
            $popularCategory->save();
            return response()->json(['success' => true, 'message' => 'Popular Category Selected']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while popular category selection: ' . $e->getMessage()], 400);
        }
    }
    public function getAllPopularCategories()
    {
        $popularCategories = PopularCategory::all();
        $responseData = [];

        foreach ($popularCategories as $popularCategory) {
            $popCategory = Category::find($popularCategory->popular_category_id);

            if ($popCategory) {
                $responseData[] = [
                    'category' => [
                        'id' => $popCategory->id,
                        'name' => $popCategory->name,
                        'image' => $popCategory->url,
                    ]
                ];
            }
            else{
                return response()->json(['error' => 'Category not found'], 404);
            }
        }

        return response()->json($responseData);
    }
    public function selectPopularProduct(Request $request)
    {
        try {
            $request->validate([
                'popular_product_id	' => 'required',
                'popularity_score' => 'required',
            ]);

            $popularProduct = new PopularProduct();
            $popularProduct->popular_product_id = $request->popular_product_id;
            $popularProduct->popularity_score = $request->popularity_score;
            $popularProduct->save();
            return response()->json(['success' => true, 'message' => 'Popular Product Selected'],200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while popular product selection: ' . $e->getMessage()], 400);
        }
    }
    public function getAllPopularProducts()
    {
        $popularProducts = PopularProduct::all();
        $responseData = [];

        foreach ($popularProducts as $popularProduct) {
            $popProduct = Category::find($popularProduct->popular_product_id);
            $productImages = ProductImage::where('product_image_id', $popProduct->id)->get();
            if ($popProduct) {
                $productData = [
                    'id' => $popProduct->id,
                    'name' => $popProduct->name,
                    'price' => $popProduct->price,
                    'description' => $popProduct->description,
                    'images' => [],
                ];
                foreach ($productImages as $image) {
                    $productData['images'][] = [
                        'id' => $image->id,
                        'product_image_id' => $image->product_image_id,
                        'url' => $image->url,
                    ];
                }
                $responseData[] = $productData;
            }
            else{
                return response()->json(['error' => 'Product not found'], 404);
            }
        }

        return response()->json($responseData);
    }
}
