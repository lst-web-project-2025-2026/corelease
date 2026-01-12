<nav class="navbar">
    <div class="container-navbar">
        <a href="/" class="brand">
            <x-ui.logo size="32px" />
            <span class="brand-name">Corelease</span>
        </a>

        <div class="nav-links">
            <a href="/catalog" class="nav-link">Resource Catalog</a>
            <a href="/status" class="nav-link">Check Application Status</a>
        </div>

        <div class="nav-actions">
            <!-- Theme Toggle -->
            <button onclick="toggleDarkMode()" class="theme-toggle" title="Toggle Theme">
                <span class="dark-icon">🌙</span>
                <span class="light-icon">☀️</span>
            </button>

            <!-- Accent Picker -->
            <div class="accent-toggle" title="Change Accent">
                <div class="accent-dots">
                    <div class="accent-dot" style="background: #3b82f6;" onclick="setAccent(217, 91, 60)"></div>
                    <div class="accent-dot" style="background: #10b981;" onclick="setAccent(160, 84, 39)"></div>
                    <div class="accent-dot" style="background: #f59e0b;" onclick="setAccent(38, 92, 50)"></div>
                    <div class="accent-dot" style="background: #ef4444;" onclick="setAccent(0, 84, 60)"></div>
                </div>
            </div>

            @auth
                <a href="/dashboard" class="nav-link">My Workspace</a>
            @else
                <x-ui.button href="/apply" variant="secondary">Request Access</x-ui.button>
                <x-ui.button href="/login">Login</x-ui.button>
            @endauth
        </div>
    </div>
</nav>

<style>
    [data-theme="dark"] .light-icon { display: none; }
    [data-theme="light"] .dark-icon { display: none; }
</style>
