<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSizes extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function productDetails()
    {
        return $this->belongsToMany(ProductDetails::class);
    }
}
