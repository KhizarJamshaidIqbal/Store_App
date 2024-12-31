<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - Admin Panel</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <!-- Alpine.js -->
<script defer src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
 <!-- Scripts -->
 @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Custom Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Top Header -->
        <header class="bg-blue-600 text-white">
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center gap-2">
                    <i class="fas fa-lock text-xl"></i>
                    <h1 class="text-xl font-semibold">Admin Panel</h1>
                </div>
            </div>
        </header>

        <!-- Menu -->
        <nav class="bg-white border-b border-gray-200">
            <div class="container mx-auto px-4">
                <div class="flex items-center gap-6 h-14">
                    <span class="text-sm font-medium text-gray-500">MENU</span>
                    <div class="flex items-center gap-6">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : '' }}">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.categories.*') ? 'text-blue-600' : '' }}">
                            <i class="fas fa-folder"></i>
                            <span>Categories</span>
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-2 text-sm text-gray-700 hover:text-blue-600 {{ request()->routeIs('admin.products.*') ? 'text-blue-600' : '' }}">
                            <i class="fas fa-box"></i>
                            <span>Products</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-6">
            @yield('content')
        </main>
    </div>
    <!-- Scripts -->
    @stack('scripts')
    {{-- @stack('scripts') --}}
</body>
</html>
