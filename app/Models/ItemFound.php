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
}
