@extends('layouts.app')

@section('title', 'Resource Catalog')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/catalog.css') }}">
@endsection

@section('content')
<div class="catalog-page container">
    <header class="catalog-header animate-fade-in">
        <h1 class="page-title">Technical Resource Catalog</h1>
        <p class="page-subtitle text-secondary">Browse our high-performance computing nodes, storage clusters, and specialized hardware specifications.</p>
    </header>

    <form action="{{ route('catalog.index') }}" method="GET" id="catalog-form">
        <div class="catalog-layout">
            <!-- Sidebar Filters -->
            <aside class="catalog-sidebar animate-fade-in">
                <div class="filter-section">
                    <h3 class="filter-title">Filter by Category</h3>
                    <div class="filter-options">
                        @foreach(['Server', 'VM', 'Storage', 'Network'] as $cat)
                            <label class="filter-checkbox">
                                <input type="checkbox" name="categories[]" value="{{ $cat }}" 
                                    {{ (request('categories') && in_array($cat, request('categories'))) || !request()->has('categories') ? 'checked' : '' }}>
                                <span>{{ $cat === 'VM' ? 'Virtual Machines' : ($cat === 'Server' ? 'Server Nodes' : ($cat === 'Storage' ? 'Storage Clusters' : 'Networking')) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-title">Availability</h3>
                    <div class="filter-options">
                        @foreach(['Enabled' => 'Online & Available', 'Disabled' => 'Maintenance / Offline'] as $val => $label)
                            <label class="filter-checkbox">
                                <input type="checkbox" name="statuses[]" value="{{ $val }}"
                                    {{ (request('statuses') && in_array($val, request('statuses'))) || !request()->has('statuses') ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-title">Sort Order</h3>
                    <select name="sort" class="form-select">
                        <option value="category_asc" {{ request('sort') == 'category_asc' ? 'selected' : '' }}>Category (A-Z)</option>
                        <option value="category_desc" {{ request('sort') == 'category_desc' ? 'selected' : '' }}>Category (Z-A)</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                        <option value="status_asc" {{ request('sort') == 'status_asc' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>

                <div class="sidebar-actions">
                    <x-ui.button type="submit" style="width: 100%;">Apply Filters</x-ui.button>
                    @if(request()->anyFilled(['categories', 'statuses', 'sort']))
                        <a href="{{ route('catalog.index') }}" class="btn-text text-muted" style="display: block; text-align: center; margin-top: 1rem; font-size: 0.875rem;">Clear All</a>
                    @endif
                </div>

                <div class="sidebar-info" style="margin-top: 2rem;">
                    <p class="text-muted"><i class="icon-info"></i> Justified access is required for all resources. <a href="/apply">Apply for access</a> if you are a new researcher.</p>
                </div>
            </aside>

            <!-- Resource Grid -->
            <main class="catalog-main">
                <div class="catalog-top-actions animate-fade-in">
                    <div class="results-count">
                        Showing {{ $resources->firstItem() ?? 0 }} - {{ $resources->lastItem() ?? 0 }} of {{ $resources->total() }} resources
                    </div>
                </div>

                <div class="catalog-pagination pagination-top animate-fade-in">
                    {{ $resources->links('vendor.pagination.custom') }}
                </div>

                <div class="catalog-grid animate-fade-in">
                    @forelse ($resources as $resource)
                        <x-ui.card class="resource-card">
                            <div class="card-header">
                                <x-ui.badge variant="primary">{{ $resource->category }}</x-ui.badge>
                                <x-ui.status status="{{ $resource->status === 'Enabled' ? 'online' : 'offline' }}" />
                            </div>
                            
                            <h3 class="resource-name">{{ $resource->name }}</h3>
                            
                            <div class="resource-specs">
                                @if(is_array($resource->specs))
                                    <div class="specs-list">
                                        @foreach($resource->specs as $key => $value)
                                            <div class="spec-item">
                                                <span class="spec-key">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                <span class="spec-value">{{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No specifications available.</p>
                                @endif
                            </div>

                            <div class="card-footer">
                                @auth
                                    <x-ui.button href="/reservations/create?resource={{ $resource->id }}" variant="primary" style="width: 100%;">
                                        Reserve Node
                                    </x-ui.button>
                                @else
                                    <x-ui.button href="/login" variant="secondary" style="width: 100%;">
                                        Login to Reserve
                                    </x-ui.button>
                                @endauth
                            </div>
                        </x-ui.card>
                    @empty
                        <div class="empty-state">
                            <p class="text-secondary">No resources match your current filters.</p>
                        </div>
                    @endforelse
                </div>

                <div class="catalog-pagination pagination-bottom">
                    {{ $resources->links('vendor.pagination.custom') }}
                </div>
            </main>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        console.log('Catalog page initialized');
    </script>
@endsection