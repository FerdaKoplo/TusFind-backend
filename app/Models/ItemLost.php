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
}
