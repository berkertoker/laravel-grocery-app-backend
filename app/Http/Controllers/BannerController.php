<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Banners;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function createBanner(Request $request)
    {
        try {
            $request->validate([
                'images' => 'required|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $imageUrls = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $filename = $image->store('banners', 'public');

                    $bannerImage = new Banners();
                    $bannerImage->url = $filename;
                    $bannerImage->save();

                    $imageUrls[] = $filename;
                }
            }
            return response()->json(['success' => true, 'message' => 'Banner Created Successfully', 'image_urls' => $imageUrls], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating banner: ' . $e->getMessage()], 400);
        }
    }
    public function getAllBanners()
    {
        $banners = Banners::all();

        $responseData = [];

        foreach ($banners as $banner) {
            $productData = [
                'id' => $banner->id,
                'images' => $banner->url,
            ];

            $responseData[] = $productData;
        }

        return response()->json($responseData);
    }
}
