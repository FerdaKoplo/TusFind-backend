<?php

namespace App\Helpers;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;

class AdminLogger
{
    public static function log(string $action): void
    {
        AdminLog::create([
            'admin_id' => Auth::id(),
            'action' => $action,
        ]);
    }
}

