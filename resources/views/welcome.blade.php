@extends('layouts.app')

@section('title', 'Infrastructure for Research')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')
<div class="landing-page">
    <!-- Hero Section -->
    <header class="hero">
        <div class="container hero-content animate-fade-in">
            <h1 class="hero-title">High-Performance Computing for Internal Research</h1>
            <p class="hero-subtitle text-secondary">Corelease provides transparent access to data center nodes, virtual machines, and specialized hardware for authorized research groups and personnel.</p>
            
            <div class="hero-actions m-t-xl">
                <x-ui.button href="/apply" class="btn-lg">Request Resource Access</x-ui.button>
                <x-ui.button href="/catalog" variant="secondary" class="btn-lg">View Available Nodes</x-ui.button>
            </div>
        </div>

        <div class="hero-bg-glow"></div>
    </header>

    <!-- Live Status Component -->
    <section class="live-status container">
        <div class="section-header text-center m-b-lg">
            <h2 class="section-title">Global Resource Status</h2>
            <p class="text-secondary">A live overview of our current computational allocation and facility health.</p>
        </div>

        <div class="grid grid-4 animate-fade-in" style="animation-delay: 0.2s">
            <x-ui.card class="status-card">
                <div class="status-icon"><i class="icon-server"></i></div>
                <div class="status-value">{{ $totalResources }}</div>
                <div class="status-label">Total Nodes Managed</div>
            </x-ui.card>

            <x-ui.card class="status-card">
                <div class="status-icon"><i class="icon-check"></i></div>
                <div class="status-value">{{ $availableNow }}</div>
                <div class="status-label">Nodes Ready for Lease</div>
            </x-ui.card>

            <x-ui.card class="status-card">
                <div class="status-icon"><i class="icon-users"></i></div>
                <div class="status-value">{{ $activeUsers }}</div>
                <div class="status-label">Authorized Operators</div>
            </x-ui.card>

            <x-ui.card class="status-card {{ $systemStatus === 'Operational' ? 'status-green' : 'status-red' }}">
                <div class="status-icon"><i class="icon-activity"></i></div>
                <div class="status-value">{{ $systemStatus }}</div>
                <div class="status-label">Facility Health Status</div>
            </x-ui.card>
        </div>
    </section>

    <!-- Visual Divider -->
    <div class="divider container m-t-xl"></div>

    <!-- Features Section / How it Works -->
    <section class="features container m-t-xl">
         <div class="grid grid-3">
             <div class="feature-item">
                 <h3>Open Inventory</h3>
                 <p class="text-secondary">Browse our technical specifications for servers, storage clusters, and network nodes without requiring an initial account.</p>
                 <a href="/catalog" class="feature-link m-t-sm">Inventory Specs &rarr;</a>
             </div>
             <div class="feature-item">
                 <h3>Justified Allocation</h3>
                 <p class="text-secondary">Submit reservation requests with detailed project justifications. Our managers prioritize high-impact research needs.</p>
                 <a href="/login" class="feature-link m-t-sm">Reserve Capacity &rarr;</a>
             </div>
             <div class="feature-item">
                 <h3>Status Transparency</h3>
                 <p class="text-secondary">Track your application status and resource availability in real-time through our centralized moderation board.</p>
                 <a href="/status" class="feature-link m-t-sm">Check Progress &rarr;</a>
             </div>
         </div>
    </section>
</div>
@endsection
