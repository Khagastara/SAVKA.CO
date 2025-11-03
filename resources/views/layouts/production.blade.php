<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Produksi')</title>
    <style>
        :root {
            --peach: #FFCDB2;
            --mocha-cream: #E8CEBF;
            --sage-green: #B7C4A4;
            --warm-white: #F8F4F0;
            --charcoal-gray: #3A3A3A;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--warm-white);
            color: var(--charcoal-gray);
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, var(--sage-green) 0%, var(--peach) 100%);
            padding: 30px 20px;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 40px;
            color: var(--charcoal-gray);
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: var(--charcoal-gray);
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(5px);
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            opacity: 0.8;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        .header {
            background: white;
            padding: 24px 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--charcoal-gray);
        }

        .btn-logout {
            background: var(--charcoal-gray);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #555;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
    @yield('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">Produksi Panel</div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('production.account') }}" class="nav-link {{ request()->routeIs('production.account') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Akun</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="" class="nav-link {{ request()->routeIs('production.supply') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 7V4a1 1 0 011-1h3m0 0h8m0 0h3a1 1 0 011 1v3m-1 0V4H5v3M4 7v13a1 1 0 001 1h14a1 1 0 001-1V7H4z"/>
                    </svg>
                    <span>Supply</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="" class="nav-link {{ request()->routeIs('production.schedule') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l9-9 9 9M4 10v10h16V10"/>
                    </svg>
                    <span>Produksi</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="" class="nav-link {{ request()->routeIs('production.products') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Produk</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header">
            <h1>@yield('header', 'Dashboard Produksi')</h1>
            <div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar</button>
                </form>
            </div>
        </header>

        <section>
            @yield('content')
        </section>
    </main>

    @yield('scripts')
</body>
</html>
