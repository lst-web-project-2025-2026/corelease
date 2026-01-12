<nav class="navbar">
    <div class="container navbar-inner">
        <a href="/" class="brand">
            <div class="brand-svg">
                <svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M20 0C27.4768 0 31.2154 -0.000204921 34 1.60742C35.8242 2.66064 37.3394 4.17577 38.3926 6C40.0002 8.7846 40 12.5232 40 20C40 27.4768 40.0002 31.2154 38.3926 34C37.3394 35.8242 35.8242 37.3394 34 38.3926C31.2154 40.0002 27.4768 40 20 40C12.5232 40 8.7846 40.0002 6 38.3926C4.17577 37.3394 2.66064 35.8242 1.60742 34C-0.000204921 31.2154 0 27.4768 0 20C0 12.5232 -0.000204921 8.7846 1.60742 6C2.66064 4.17577 4.17577 2.66064 6 1.60742C8.7846 -0.000204921 12.5232 0 20 0ZM22 4C13.1634 4 6 11.1634 6 20C6 28.8366 13.1634 36 22 36C30.8366 36 38 28.8366 38 20C38 11.1634 30.8366 4 22 4Z" fill="var(--accent-primary)"/>
                    <path d="M36 20C36 25.5228 31.5228 30 26 30C20.4772 30 16 25.5228 16 20C16 14.4772 20.4772 10 26 10C31.5228 10 36 14.4772 36 20Z" fill="var(--accent-primary)"/>
                </svg>
            </div>
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
