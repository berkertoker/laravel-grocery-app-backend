<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    public $table = 'subcategories';
    use HasFactory;
    protected $fillable = ['name','child_id'];
    public function categories()
    {
        return $this->belongsTo(Category::class);
    }
}
