<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Laundry - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <div class="bg-indigo-900 text-white w-64 flex-shrink-0 flex flex-col transition-all duration-300">
        <div class="flex items-center justify-center h-16 border-b border-indigo-800">
            <h1 class="text-xl font-bold uppercase tracking-wider"><i class="fas fa-tshirt mr-2"></i> Laundry</h1>
        </div>
        <div class="p-4 flex items-center border-b border-indigo-800">
            <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-lg">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-semibold">{{ Auth::user()->name }}</p>
                <p class="text-xs text-indigo-300">{{ Auth::user()->level->level_name }}</p>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto py-4">
            <nav class="space-y-1 px-2">
                @if(Auth::user()->id_level == 1 || Auth::user()->id_level == 2)
                    @if(Auth::user()->id_level == 1)
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-tachometer-alt w-6 text-center mr-2"></i> Dashboard
                        </a>
                    @endif
                    <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-users w-6 text-center mr-2"></i> Customers
                    </a>
                    <a href="{{ route('operator.orders.index') }}" class="{{ request()->routeIs('operator.orders.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-shopping-cart w-6 text-center mr-2"></i> Transaksi Baru
                    </a>
                    @if(Auth::user()->id_level == 1)
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-user-tie w-6 text-center mr-2"></i> Operator
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-box w-6 text-center mr-2"></i> Master Jasa
                        </a>
                    @endif
                @elseif(Auth::user()->id_level == 3)
                    <a href="{{ route('pimpinan.dashboard') }}" class="{{ request()->routeIs('pimpinan.dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-700' }} group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                        <i class="fas fa-chart-line w-6 text-center mr-2"></i> Laporan Penjualan
                    </a>
                @endif
            </nav>
        </div>
        
        <div class="p-4 border-t border-indigo-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center px-2 py-2 text-sm font-medium text-indigo-100 rounded-md hover:bg-red-600 hover:text-white transition-colors duration-200">
                    <i class="fas fa-sign-out-alt w-6 text-center mr-2"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top header -->
        <header class="bg-white shadow">
            <div class="px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800 leading-tight">
                    @yield('header')
                </h2>
                <div>
                   <span class="text-sm text-gray-500">{{ now()->format('d M Y, H:i') }}</span>
                </div>
            </div>
        </header>

        <!-- Main section -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-sm rounded-r" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-sm rounded-r" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
