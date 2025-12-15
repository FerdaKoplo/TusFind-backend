<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'brand',
        'color',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function lostReports()
    {
        return $this->hasMany(ItemLost::class);
    }

    public function foundReports()
    {
        return $this->hasMany(ItemFound::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }
}
