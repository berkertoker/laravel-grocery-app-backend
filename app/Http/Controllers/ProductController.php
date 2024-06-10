<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductDetails;
use App\Models\ProductSize;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSizes;

class ProductController extends Controller
{
    public function createProduct(Request $request)
    {
        try {
            $request->validate([
                //products table
                'name' => 'required|string',
                'description' => 'required|string',
                'gender' => 'required|in:male,female,unisex,child',
                'product_categories_id'=>'required|integer',
                'product_subcategories_id'=>'required|integer',

                //product_images table
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

                //product_details table
                'size_id' => 'required|array',
                'stock' => 'required|array',
                'price' => 'required|array',
                'price.*'=>'numeric'
            ]);

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->gender = $request->gender;
            $product->product_categories_id = $request->product_categories_id;
            $product->product_subcategories_id = $request->product_subcategories_id;
            $product->save();

            $imageUrls = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = $image->store('posts', 'public');

                    $productImage = new ProductImage();
                    $productImage->product_image_id = $product->id;
                    $productImage->url = $filename;
                    $productImage->save();

                    $imageUrls[] = $filename;
                }
            }
            $sizes = $request->input('size_id');
            $stocks = $request->input('stock');
            $prices = $request->input('price');

            $count = count($stocks);

            for ($i = 0; $i < $count; $i++) {
                $productDetail = new ProductDetails();
                $productDetail->product_id = $product->id;
                $productDetail->size_id = $sizes[$i];
                $productDetail->stock = $stocks[$i];
                $productDetail->price = $prices[$i];
                $productDetail->save();
            }

            return response()->json(['success' => true, 'message' => 'Product Created Successfully', 'image_urls' => $imageUrls], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating product: ' . $e->getMessage()], 400);
        }
    }
    public function getProductsByName($keyword)
    {
        $products = Product::where('name', 'like', '%' . $keyword . '%')->get();

        $responseData = [];

        foreach ($products as $product) {
            $productImages = ProductImage::where('product_image_id', $product->id)->get();
            $firstImage = $productImages->isEmpty() ? null : $productImages->first();

            $productDetail = ProductDetails::where('product_id', $product->id)
                ->where('stock', '>', 0)
                ->orderBy('price', 'asc')
                ->first();

            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $firstImage ? [
                    [
                        'id' => $firstImage->id,
                        'product_image_id' => $firstImage->product_image_id,
                        'url' => $firstImage->url,
                    ]
                ] : [],
                'details' => $productDetail ? [
                    [
                        'id' => $productDetail->id,
                        'product_id' => $productDetail->product_id,
                        'stock' => $productDetail->stock,
                        'price' => $productDetail->price,
                    ]
                ] : [],
            ];

            $responseData[] = $productData;
        }

        return response()->json($responseData);
    }

    //yenilenecek
    public function getProduct($ids)
    {
        $product = Product::find($ids);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $productImages = ProductImage::where('product_image_id', $product->id)->get();

        $responseData = [
            'id'=> $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'description' => $product->description,
            'images' => [],
        ];

        foreach ($productImages as $image) {
            $responseData['images'][] = [
                'id'=> $image->id,
                'product_image_id' => $image->product_image_id,
                'url' => $image->url,
            ];
        }

        return response()->json($responseData);
    }
    //

    public function getProducts()
    {
        $products = Product::all();

        $responseData = [];

        foreach ($products as $product) {
            $productImages = ProductImage::where('product_image_id', $product->id)->get();
            $firstImage = $productImages->isEmpty() ? null : $productImages->first();

            $productDetail = ProductDetails::where('product_id', $product->id)
                ->where('stock', '>', 0)
                ->orderBy('price', 'asc')
                ->first();

            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $firstImage ? [
                    [
                        'id' => $firstImage->id,
                        'product_image_id' => $firstImage->product_image_id,
                        'url' => $firstImage->url,
                    ]
                ] : [],
                'details' => $productDetail ? [
                    [
                        'id' => $productDetail->id,
                        'product_id' => $productDetail->product_id,
                        'stock' => $productDetail->stock,
                        'price' => $productDetail->price,
                    ]
                ] : [],
            ];
            $responseData[] = $productData;
        }
        return response()->json($responseData);
    }
    public function getAllProductDetailsById($ids)
    {
        $product = Product::find($ids);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }
        $responseData = [];

        $productCategory = Category::find($product->product_categories_id);
        $productSubCategory = SubCategory::find($product->product_subcategories_id);
        $productData = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'gender'=> $product->gender,
            'category'=>$productCategory->name,
            'subcategory'=>$productSubCategory->name,
            'images' => [],
            'details' => [],
        ];
        $productImages = ProductImage::where('product_image_id', $product->id)->get();
        foreach ($productImages as $image) {
            $productData['images'][] = [
                'id' => $image->id,
                'product_image_id' => $image->product_image_id,
                'url' => $image->url,
            ];
        }
        $productDetails = ProductDetails::where('product_id', $product->id)->get();
        foreach ($productDetails as $productDetail) {
            $size = ProductSizes::find($productDetail->size_id);
            $productData['details'][] = [
                'id' => $productDetail->id,
                'size'=>$size->name,
                'product_id' => $productDetail->product_id,
                'size_id' => $productDetail->size_id,
                'stock' => $productDetail->stock,
                'price' => $productDetail->price,
            ];
        }
        $responseData[] = $productData;


        return response()->json($responseData);
    }

    public function getAllProducts()
    {
        $products = Product::all();

        $responseData = [];

        foreach ($products as $product) {
            $productCategory = Category::find($product->product_categories_id);
            $productSubCategory = SubCategory::find($product->product_subcategories_id);
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'gender'=> $product->gender,
                'category'=>$productCategory->name,
                'subcategory'=>$productSubCategory->name,
                'images' => [],
                'details' => [],
            ];
            $productImages = ProductImage::where('product_image_id', $product->id)->get();
            foreach ($productImages as $image) {
                $productData['images'][] = [
                    'id' => $image->id,
                    'product_image_id' => $image->product_image_id,
                    'url' => $image->url,
                ];
            }
            $productDetails = ProductDetails::where('product_id', $product->id)->get();
            foreach ($productDetails as $productDetail) {
                $size = ProductSizes::find($productDetail->size_id);
                $productData['details'][] = [
                    'id' => $productDetail->id,
                    'size'=>$size->name,
                    'product_id' => $productDetail->product_id,
                    'size_id' => $productDetail->size_id,
                    'stock' => $productDetail->stock,
                    'price' => $productDetail->price,
                ];
            }
            $responseData[] = $productData;
        }

        return response()->json($responseData);
    }

    public function edit($id){
        $images=Product::findOrFail($id);
        return response()->json($images);
    }
    public function update(Request $request, $id){
        $images=Product::findOrFail($id);

        $destination=public_path("storage\\".$images->image);
        $filename="";
        if($request->hasFile("new_image")){
            if(File::exists($destination)){
                File::delete($destination);
            }
            $filename=$request->file("new_image")->store("posts","public");
        }else{
            $filename=$request->image;
        }
        $images->title=$request->title;
        $images->image=$filename;
        $result=$images->save();
        if($result){
            return response()->json(['success'=>true]);
        }
        else{
            return response()->json(['success'=>false]);
        }
    }

    public function delete($id){
        $images=Product::findOrFail($id);
        $destination=public_path("storage\\".$images->image);
        if(File::exists($destination)){
            File::delete($destination);
        }
        $result=$images->delete ();
        if($result){
            return response()->json(['success'=>true]);
        }
        else{
            return response()->json(['success'=>false]);
        }
    }
}
