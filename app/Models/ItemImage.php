<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'item_lost_id',
        'item_found_id',
        'image_path',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function itemLost()
    {
        return $this->belongsTo(ItemLost::class);
    }

    public function itemFound()
    {
        return $this->belongsTo(ItemFound::class);
    }
}
