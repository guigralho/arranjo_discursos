<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $current = Schedule::query()
            ->with(['toReceive.speech', 'toSend.speech', 'toSend.speaker'])
            ->whereDate('month', date('Y-m-01'))
            ->first();

        $nextDate = Carbon::now()->firstOfMonth()->addMonth()->format('Y-m-01');

        $next = Schedule::query()
            ->whereDate('month', $nextDate)
            ->first();

        return Inertia::render('Dashboard', compact('current', 'next'));
    }
}
