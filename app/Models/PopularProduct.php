<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PopularProduct extends Model
{
    use HasFactory;

    protected $fillable = ['popular_product_id','popularity_score'];
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
