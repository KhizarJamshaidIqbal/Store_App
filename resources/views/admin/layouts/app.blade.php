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
    <script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('modal', {
                current: null,
                open(name) {
                    this.current = name;
                },
                close() {
                    this.current = null;
                }
            });
        });
    </script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <!-- TinyMCE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
    <!-- Sortable -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Additional Styles -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

</head>
<body class="bg-gray-50/50 backdrop-blur-xl">
    <div x-data="{ sidebarOpen: true }" class="min-h-screen bg-gray-50/50 backdrop-blur-xl">
        <!-- Top Navigation -->
        <nav class="fixed top-0 right-0 left-0 z-50 bg-gradient-to-r from-blue-600/90 to-blue-700/90 border-b border-white/10 backdrop-blur-xl shadow-xl shadow-black/10">
            <div class="mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-16">
                    <!-- Left side - Toggle Button -->
                    <div class="flex items-center">
                        <button @click="sidebarOpen = !sidebarOpen"
                                class="p-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all duration-300 focus:outline-none">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Right side - User Menu -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:block">
                            <span class="text-white/90 text-sm">Welcome back, <span class="font-medium">{{ auth()->user()->name }}</span></span>
                        </div>

                        <div class="h-8 w-[1px] bg-white/10 hidden sm:block"></div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.profile.edit') }}"
                               class="flex items-center gap-2 px-3 py-1.5 text-sm text-white/90 hover:text-white rounded-lg hover:bg-white/10 transition-all duration-300">
                                <i class="fa fa-user text-[0.9rem] opacity-80"></i>
                                <span class="hidden sm:inline">Profile</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="inline-block">
                                @csrf
                                <button type="submit"
                                        class="flex items-center gap-2 px-3 py-1.5 text-sm text-red-300 hover:text-red-200 rounded-lg hover:bg-red-500/20 transition-all duration-300">
                                    <i class="fa fa-sign-out-alt text-[0.9rem] opacity-80"></i>
                                    <span class="hidden sm:inline">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="flex h-screen pt-16">
            <!-- Sidebar -->
            <aside :class="{'translate-x-0': sidebarOpen, '-translate-x-64': !sidebarOpen}"
                   class="fixed left-0 top-16 w-64 h-[calc(100vh-4rem)] bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white transform transition-all duration-300 ease-in-out z-30 shadow-xl overflow-y-auto">
                <!-- Logo -->
                <div class="flex items-center justify-between p-6 border-b border-blue-500/30">
                    <h2 class="text-2xl font-bold tracking-wider transform hover:scale-105 transition-transform duration-200">
                        <span class="flex items-center space-x-2">
                            <i class="fa fa-circle-nodes text-blue-200"></i>
                            <span>Admin Panel</span>
                        </span>
                    </h2>
                    <button @click="sidebarOpen = false"
                            class="lg:hidden text-white hover:text-blue-200 transition-all duration-200 hover:rotate-180">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="mt-6 px-4">
                    <div class="space-y-3">
                        <a href="{{ route('admin.dashboard') }}"
                           class="group flex items-center px-4 py-3 text-gray-100 rounded-lg transition-all duration-300 ease-in-out transform hover:translate-x-2
                                  {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 shadow-lg scale-[1.02]' : 'hover:bg-white/5' }}">
                            <i class="fa fa-gauge-high w-5 h-5 mr-4 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12
                               {{ request()->routeIs('admin.dashboard') ? 'text-blue-200' : '' }}"></i>
                            <span class="font-medium transition-all duration-300 group-hover:text-blue-200
                                       {{ request()->routeIs('admin.dashboard') ? 'text-blue-200' : '' }}">Dashboard</span>
                        </a>

                        <!-- Categories with Dropdown -->
                        <div x-data="{ isOpen: false }" class="relative">
                            <a @click="isOpen = !isOpen" @click.away="isOpen = false"
                               class="group flex items-center px-4 py-3 text-gray-100 rounded-lg transition-all duration-300 ease-in-out transform hover:translate-x-2
                                      {{ request()->routeIs('admin.categories.*') ? 'bg-white/10 shadow-lg scale-[1.02]' : 'hover:bg-white/5' }}">
                                <i class="fa fa-folder-tree w-5 h-5 mr-4 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12
                                   {{ request()->routeIs('admin.categories.*') ? 'text-blue-200' : '' }}"></i>
                                <span class="flex-1">Categories</span>
                                <i class="fas fa-chevron-down transition-transform duration-300"
                                   :class="{'rotate-180': isOpen}"></i>
                            </a>

                            <!-- Dropdown Menu -->
                            <div x-show="isOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="relative left-0 mt-2 w-full bg-white/10 backdrop-blur-xl rounded-lg overflow-hidden z-50">
                                <div class="py-2 space-y-1">
                                    <a href="{{ route('admin.categories.index') }}"
                                       class="block px-4 py-2 text-sm text-gray-100 hover:bg-white/10 transition-colors duration-200
                                              {{ request()->routeIs('admin.categories.index') ? 'bg-white/20' : '' }}">
                                        <i class="fas fa-list-ul mr-2"></i> All Categories
                                    </a>
                                    <a href="{{ route('admin.categories.create') }}"
                                       class="block px-4 py-2 text-sm text-gray-100 hover:bg-white/10 transition-colors duration-200
                                              {{ request()->routeIs('admin.categories.create') ? 'bg-white/20' : '' }}">
                                        <i class="fas fa-plus mr-2"></i> Add Category
                                    </a>
                                    <a href="{{ route('admin.categories.trashed') }}"
                                       class="block px-4 py-2 text-sm text-gray-100 hover:bg-white/10 transition-colors duration-200
                                              {{ request()->routeIs('admin.categories.trashed') ? 'bg-white/20' : '' }}">
                                        <i class="fas fa-trash mr-2"></i> Trash
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Products with Dropdown -->
                        <div x-data="{ isOpen: false }" class="relative">
                            <a @click="isOpen = !isOpen" @click.away="isOpen = false"
                               class="group flex items-center px-4 py-3 text-gray-100 rounded-lg transition-all duration-300 ease-in-out transform hover:translate-x-2
                                      {{ request()->routeIs('admin.products.*') ? 'bg-white/10 shadow-lg scale-[1.02]' : 'hover:bg-white/5' }}">
                                <i class="fa fa-boxes-stacked w-5 h-5 mr-4 transition-all duration-300 group-hover:scale-110 group-hover:rotate-12
                                   {{ request()->routeIs('admin.products.*') ? 'text-blue-200' : '' }}"></i>
                                <span class="flex-1">Products</span>
                                <i class="fas fa-chevron-down transition-transform duration-300"
                                   :class="{'rotate-180': isOpen}"></i>
                            </a>

                            <!-- Dropdown Menu -->
                            <div x-show="isOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform translate-y-0"
                                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                                 class="relative left-0 mt-2 w-full bg-white/10 backdrop-blur-xl rounded-lg overflow-hidden z-50">
                                <div class="py-2 space-y-1">
                                    <a href="{{ route('admin.products.index') }}"
                                       class="block px-4 py-2 text-sm text-gray-100 hover:bg-white/10 transition-colors duration-200
                                              {{ request()->routeIs('admin.products.index') ? 'bg-white/20' : '' }}">
                                        <i class="fas fa-list-ul mr-2"></i> All Products
                                    </a>
                                    <a href="{{ route('admin.products.create') }}"
                                       class="block px-4 py-2 text-sm text-gray-100 hover:bg-white/10 transition-colors duration-200
                                              {{ request()->routeIs('admin.products.create') ? 'bg-white/20' : '' }}">
                                        <i class="fas fa-plus mr-2"></i> Add Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Animated Indicator -->
                    <div class="absolute bottom-0 left-0 w-full">
                        <div class="h-1 bg-gradient-to-r from-transparent via-blue-400 to-transparent
                                   opacity-0 transition-all duration-500 transform"
                             :class="{'opacity-100 translate-y-0': sidebarOpen, 'translate-y-1': !sidebarOpen}"></div>
                    </div>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <div :class="{'pl-64': sidebarOpen, 'pl-0': !sidebarOpen}" class="flex-1 transition-all duration-200 ease-in-out w-full h-[calc(100vh-4rem)] overflow-y-auto">
                <main class="p-8">
                    @yield('content')
                </main>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
