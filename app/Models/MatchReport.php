<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_lost_id',
        'item_found_id',
        'match_score',
        'status'
    ];
}
