<nav class="navbar">
    <div class="container-navbar">
        <div class="nav-main">
            <a href="/" class="brand">
                <x-ui.logo size="32px" />
                <span class="brand-name">Corelease</span>
            </a>

            <div class="nav-links">
                <a href="/catalog" class="nav-link">Resource Catalog</a>
                @auth
                    <a href="/dashboard" class="nav-link">Dashboard</a>
                @else
                    <a href="/#status-checker" class="nav-link">Check Application Status</a>
                @endauth
            </div>
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
                <span class="nav-link user-name">{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <x-ui.button type="submit" variant="secondary" size="sm">Sign Out</x-ui.button>
                </form>
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
