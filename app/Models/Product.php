<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description','gender', 'product_categories_id','product_subcategories_id'  ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function details()
    {
        return $this->hasMany(ProductDetails::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'product_categories_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'product_subcategories_id');
    }
}
