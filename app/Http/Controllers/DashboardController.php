<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $current = Schedule::query()
            ->with(['toReceive.speech', 'toSend.speech', 'toSend.speaker'])
            ->whereDate('month', date('Y-m-01'))
            ->first();

        $next = Schedule::query()
            ->whereDate('month', date('Y-m-01', strtotime('+1 month')))
            ->first();

        return Inertia::render('Dashboard', compact('current', 'next'));
    }
}
