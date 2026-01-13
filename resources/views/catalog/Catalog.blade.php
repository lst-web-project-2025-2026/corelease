<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corelease | Catalog</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Catalog.css"> <!-- Ensure path is correct -->
</head>
<body>

<nav class="navbar">
    <div class="logo">
        <!-- LOGO (Wrapped in a div to allow CSS coloring) -->
        <div class="logo-icon"></div>
        <span class="logo-text">Corelease</span>
    </div>

    <div class="nav-right">
        <!-- Night Mode -->
        <div class="mode-toggle" id="modeToggle">🌙</div>

        <!-- Color Themes -->
        <div class="theme-dots">
            <div class="dot dot-blue" data-theme="blue"></div>
            <div class="dot dot-green" data-theme="green"></div>
            <div class="dot dot-yellow" data-theme="yellow"></div>
            <div class="dot dot-red" data-theme="red"></div>
        </div>
        
        <!-- The title in the corner -->
        <div class="nav-resource-title">Resource Catalog</div>
    </div>
</nav>

<div class="container" style="max-width: 1200px; margin: 0 auto; text-align: center; padding-top: 50px;">
    <h1 class="main-title">Resource Catalog</h1>
    <p style="color: var(--text-muted);">Read-only access to infrastructure specifications.</p>

    <div class="catalog-grid">
        <!-- Example Loop (Blade syntax kept) -->
        @foreach ($Catalog as $resource)
        <div class="card">
            <div class="category-tag">{{ $resource->category }}</div>
            <h3 style="margin: 15px 0;">{{ $resource->name }}</h3>
            
            <div class="spec-container">
                {{ $resource->specs }}
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.8rem; color: var(--text-muted);">Status:</span>
                <span class="status-badge">
                    {{ strtoupper($resource->status) }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="Catalog.js"></script>
</body>
</html>