<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = AdminLog::with('admin')->latest();

        if ($request->filled('range')) {
            match ($request->range) {
                '7_days' => $query->where('created_at', '>=', Carbon::now()->subDays(7)),
                '30_days' => $query->where('created_at', '>=', Carbon::now()->subDays(30)),
                'today' => $query->whereDate('created_at', Carbon::today()),
                default => null,
            };
        }

        $logs = $query->paginate(20);

        return view('admin.activities.index', compact('logs'));
    }


}
