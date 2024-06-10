<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PopularityController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//product
Route::post('/createProduct', [ProductController::class,'createProduct']);
Route::get('/getProductById/{id}', [ProductController::class,'getProduct']);
Route::get('/getAllProducts', [ProductController::class,'getAllProducts']);
Route::get('/getAllProductDetailsById/{id}', [ProductController::class,'getAllProductDetailsById']);
Route::get('/getProducts', [ProductController::class,'getProducts']);
Route::get('/getProductsByName/{keyword}', [ProductController::class,'getProductsByName']);


Route::patch('/edit/{id}', [ProductController::class,'edit']);
Route::post('/update/{id}', [ProductController::class,'update']);
Route::delete('/delete/{id}', [ProductController::class,'delete']);

//Banner
Route::post('/createBanner', [BannerController::class,'createBanner']);
Route::get('/getAllBanners', [BannerController::class,'getAllBanners']);

//Category
Route::post('/createCategory', [CategoryController::class,'createCategory']);
Route::get('/getAllCategories', [CategoryController::class,'getAllCategories']);
Route::get('/getSubCategoriesByCategoryId/{categoryId}', [CategoryController::class,'getSubCategoriesByCategoryId']);

//PopularCategory
Route::post('/createPopularCategory', [PopularityController::class,'selectPopularCategory']);
Route::get('/getAllPopularCategories', [PopularityController::class,'getAllPopularCategories']);

//PopularProduct
Route::post('/createPopularProduct', [PopularityController::class,'selectPopularProduct']);
Route::get('/getAllPopularProduct', [PopularityController::class,'getAllPopularProducts']);

//Product Sizes
Route::post('/createProductSizes', [TagsController::class,'createProductSizes']);
Route::get('/getAllProductSizes', [TagsController::class,'getAllProductSizes']);
Route::get('/getSizesExcept/{sizeId}', [TagsController::class,'getSizesExcept']);

//Product Details
Route::post('/createProductDetails', [TagsController::class,'createProductDetails']);
Route::get('/getAllProductDetails', [TagsController::class,'getAllProductDetails']);
