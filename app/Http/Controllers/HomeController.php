<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\User;
use App\Models\Reservation;
use App\Services\SystemControlService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(protected SystemControlService $systemService) {}

    public function index()
    {
        $totalResources = Resource::count();
        $activeReservations = Reservation::where('status', 'Approved')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();
        
        $availableNow = $totalResources - $activeReservations;
        $activeUsers = User::where('is_active', true)->count();
        
        $systemStatus = $this->systemService->isSystemLocked() ? 'Maintenance' : 'Operational';

        return view('welcome', compact('totalResources', 'availableNow', 'activeUsers', 'systemStatus'));
    }
}
