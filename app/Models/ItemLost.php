<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'item_id',
        'lost_date',
        'lost_location',
        'description',
        'status',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_MATCHED = 'matched';
    const STATUS_RESOLVED = 'resolved';

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
