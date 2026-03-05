<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WarungKu') - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 h-screen overflow-hidden">
    <div class="flex h-full">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-b from-green-600 to-green-700 text-white flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-green-500">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                        <i class="fas fa-store text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Warungku</h1>
                        <p class="text-xs text-green-100">Point of Sale</p>
                    </div>
                </div>
            </div>

            <!-- Menu -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                           {{ request()->routeIs('dashboard.*') 
                              ? 'bg-green-500 text-white shadow-md' 
                              : 'text-green-100 hover:bg-green-400 hover:text-white' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kasir.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                           {{ request()->routeIs('kasir.*') 
                              ? 'bg-green-500 text-white shadow-md' 
                              : 'text-green-100 hover:bg-green-400 hover:text-white' }}">
                            <i class="fas fa-cash-register"></i>
                            <span>Kasir</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('stok.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                           {{ request()->routeIs('stok.*') 
                              ? 'bg-green-500 text-white shadow-md' 
                              : 'text-green-100 hover:bg-green-400 hover:text-white' }}">
                            <i class="fas fa-boxes"></i>
                            <span>Stok</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kategori.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                           {{ request()->routeIs('kategori.*') 
                              ? 'bg-green-500 text-white shadow-md' 
                              : 'text-green-100 hover:bg-green-400 hover:text-white' }}">
                            <i class="fas fa-tags"></i>
                            <span>Kategori</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                           {{ request()->routeIs('laporan.*') 
                              ? 'bg-green-500 text-white shadow-md' 
                              : 'text-green-100 hover:bg-green-400 hover:text-white' }}">
                            <i class="fas fa-file-alt"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('analisis.index') }}" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition
                           {{ request()->routeIs('analisis.*') 
                              ? 'bg-green-500 text-white shadow-md' 
                              : 'text-green-100 hover:bg-green-400 hover:text-white' }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Analisis</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Profile -->
            <div class="p-4 border-t border-green-500">
                <div class="flex items-center space-x-3">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=10b981&color=fff" class="w-10 h-10 rounded-full" alt="avatar">
                    <div class="flex-1">
                        <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-green-200">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-green-200 hover:text-white">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col bg-gray-50">
            <!-- Header -->
            <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">@yield('title', 'Dashboard')</h2>
                    <p class="text-sm text-gray-500">@yield('subtitle')</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Cari..." class="pl-10 pr-4 py-2 border rounded-full text-sm focus:outline-none focus:ring focus:ring-green-500">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <button class="relative p-2 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-bell"></i>
                        <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <div class="text-sm text-gray-600">
                        <i class="far fa-calendar"></i> {{ now()->locale('id')->format('d M Y') }}
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
