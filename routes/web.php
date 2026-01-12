<?php

use Illuminate\Support\Facades\Route;

use App\Models\Resource;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Setting;

Route::get('/', function () {
    $totalResources = Resource::count();
    $activeReservations = Reservation::where('status', 'Approved')
        ->where('start_date', '<=', now())
        ->where('end_date', '>=', now())
        ->count();
    
    $availableNow = $totalResources - $activeReservations;
    $activeUsers = User::where('is_active', true)->count();
    
    $maintenanceSetting = Setting::where('key', 'facility_maintenance')->first();
    $systemStatus = ($maintenanceSetting && $maintenanceSetting->value == '1') ? 'Maintenance' : 'Operational';

    return view('welcome', compact('totalResources', 'availableNow', 'activeUsers', 'systemStatus'));
});
