<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemFound extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'item_id',
        'found_date',
        'found_location',
        'description',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

}
