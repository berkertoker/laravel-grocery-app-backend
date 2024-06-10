<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TagsController;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Product Create Screen
Route::get('/create-product', function () {
    return view('productCreate');
});
Route::post('/create-product', [ProductController::class,'createProduct'])->name('product.create');
Route::get('/get-categories', [CategoryController::class,'getCategories']);
Route::get('/get-subcategories/{categoryId}', [CategoryController::class, 'getSubCategoriesByCategoryId']);
Route::get('/get-sizes', [TagsController::class,'getSizes']);



Route::get('/getSizesExcept/{sizeId}', [TagsController::class,'getSizesExcept']);


//Banner Create Screen
Route::get('/create-banner', function () {
    return view('bannerCreate');
});
Route::post('/create-banner', [BannerController::class,'createBanner'])->name('banner.create');

//Category Create Screen
Route::get('/create-category', function () {
    return view('adminScreen');
});
Route::post('/create-category', [CategoryController::class,'createCategory'])->name('category.create');
Route::post('/create-newSizes', [TagsController::class,'createProductSizes'])->name('size.create');


//deeplink
Route::get('/data', function () {
    // Rotanın işleyeceği kod
})->middleware('check.device');
