{{-- layout/app.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'UMKM Management')</title>
    <style>
        :root {
            --peach: #FFCDB2;
            --mocha-cream: #E8CEBF;
            --sage-green: #B7C4A4;
            --warm-white: #F8F4F0;
            --charcoal-gray: #3A3A3A;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--warm-white);
            color: var(--charcoal-gray);
            margin: 0;
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
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 40px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: block;
            padding: 12px 16px;
            border-radius: 10px;
            color: var(--charcoal-gray);
            text-decoration: none;
            transition: 0.3s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255, 255, 255, 0.3);
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 20px 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-logout {
            background: var(--charcoal-gray);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .btn-logout:hover {
            background: #555;
        }
    </style>
    @yield('styles')
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">UMKM Manager</div>
        <ul class="nav-menu">
            <li class="nav-item"><a href="#" class="nav-link active">Dashboard</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Produksi</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Distribusi</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Laporan</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Pengaturan</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="header">
            <h1>@yield('header', 'Dashboard')</h1>
            <div>
                <button class="btn-logout" onclick="window.location.href='{{ route('logout') }}'">Keluar</button>
            </div>
        </header>

        <section>
            @yield('content')
        </section>
    </main>

    @yield('scripts')
</body>
</html>
