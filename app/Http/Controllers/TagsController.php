<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProductDetails;
use App\Models\ProductSizes;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function createProductSizes(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|array',
            ]);

            $sizes = $request->input('name');
            foreach ($sizes as $size) {
                $productSizes = new ProductSizes();
                $productSizes->name = $size;
                $productSizes->save();
            }
            return response()->json(['success' => true, 'message' => 'Size Created Successfully'],200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating size: ' . $e->getMessage()], 400);
        }
    }
    public function getSizes()
    {
        $sizes = ProductSizes::all();
        return response()->json($sizes);
    }

    public function getSizesExcept($id)
    {
        $sizes = ProductSizes::where('id', '!=', $id)->get();
        return response()->json($sizes);
    }

    public function getAllProductSizes()
    {
        $sizes = ProductSizes::all();

        $responseData = [];

        foreach ($sizes as $size) {
            $productData = [
                'id' => $size->id,
                'name'=> $size->name,
            ];
            $responseData[] = $productData;
        }

        return response()->json($responseData);
    }
    public function createProductDetails(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|integer',
                'size_id' => 'required|array',
                'stock' => 'required|array',
                'price' => 'required|array',
            ]);

            $sizes = $request->input('size_id');
            $stocks = $request->input('stock');
            $prices = $request->input('price');


            $count = count($sizes);

            for ($i = 0; $i < $count; $i++) {
                $productDetail = new ProductDetails();
                $productDetail->product_id = $request->product_id;
                $productDetail->size_id = $sizes[$i];
                $productDetail->stock = $stocks[$i];
                $productDetail->price = $prices[$i];
                $productDetail->save();
            }

            return response()->json(['success' => true, 'message' => 'Product Details Created Successfully'],200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating Product Details: ' . $e->getMessage()], 400);
        }
    }
    public function getAllProductDetails()
    {
        $details = ProductDetails::all();

        $responseData = [];

        foreach ($details as $detail) {
            $productData = [
                'product_id' => $detail->product_id,
                'size_id'=> $detail->size_id,
                'stock'=> $detail->stock,
                'price'=> $detail->price,
            ];
            $responseData[] = $productData;
        }

        return response()->json($responseData);
    }
}
