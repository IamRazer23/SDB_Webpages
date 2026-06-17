<nav class="navbar" id="navbar">
    <div class="container nav-inner">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-icon">⊕</span> SD BIOSENSOR
        </a>

        <ul class="nav-menu" id="nav-menu">
            <li>
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Sobre Nosotros
                </a>
            </li>
            <li>
                <a href="{{ route('products.index') }}"
                   class="{{ request()->routeIs('products.*') ? 'active' : '' }}">
                    Productos
                </a>
            </li>
            <li>
                <a href="{{ route('research.index') }}"
                   class="{{ request()->routeIs('research.*') ? 'active' : '' }}">
                    I+D
                </a>
            </li>
            <li>
                <a href="#">Rel. con Inversionistas</a>
            </li>
            <li>
                <a href="{{ route('media.index') }}"
                   class="{{ request()->routeIs('media.*') ? 'active' : '' }}">
                    Centro de Medios
                </a>
            </li>
            <li>
                <a href="{{ route('support.index') }}"
                   class="{{ request()->routeIs('support.*') ? 'active' : '' }}">
                    Centro de Soporte
                </a>
            </li>
        </ul>

        <div class="nav-actions">
            <button class="btn-search" aria-label="Buscar">
                <i class="fas fa-search"></i>
            </button>
            <span class="lang-switcher">ESP ▾</span>
        </div>

        <button class="hamburger" id="hamburger" aria-label="Menú">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>
