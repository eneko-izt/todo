<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Task Manager Dashboard</title>

    <!-- Tailwind CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="h-screen bg-gray-100">

    <!-- Sidebar -->
    <div id="sidebar"
         class="fixed inset-y-0 left-0 w-56 bg-gray-800 text-white p-4 pt-12 flex flex-col space-y-2 transform transition-transform duration-300 z-20">
        <a class="block w-full bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded flex items-center justify-center mb-2"
           href="{{ route('welcome') }}">Home</a>
        @if(auth()->check())
            <a class="block w-full bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded flex items-center justify-center mb-2"
               href="{{ route('home') }}">Dashboard</a>
        @endif
        @can('viewColumn', App\Column::class)
            <a class="block w-full bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded flex items-center justify-center mb-2"
               href="{{ route('columns.index') }}">Manage Columns</a>
        @endcan
        @can('viewTag', App\Tag::class)
            <a class="block w-full bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded flex items-center justify-center mb-2"
               href="{{ route('tags.index') }}">Manage Tags</a>
        @endcan
        @can('viewUser', App\User::class)
            <a class="block w-full bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded flex items-center justify-center"
               href="{{ route('users.index') }}">Manage Users</a>
        @endcan
    </div>

    <!-- Toggle Sidebar Button -->
    <button id="toggleSidebar"
            class="fixed top-4 left-4 z-50 bg-white border rounded-full w-10 h-10 flex items-center justify-center shadow-md hover:bg-gray-200"
            aria-label="Toggle sidebar">
        ☰
    </button>

    <!-- Main content -->
    <main id="mainContent" class="transition-all duration-300 ml-56 min-h-screen relative z-30">
        <div class="p-6">

            <!-- Header -->
            <header class="flex justify-between items-center mb-6 border-b pb-3 pl-14 relative z-40 bg-white">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Task Manager</h1>
                    <span id="currentDate" class="text-gray-600 font-medium"></span>
                </div>

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="flex items-center space-x-2 text-gray-800 hover:text-gray-600 focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a>
                        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
                    </div>
                @endauth
            </header>

            <!-- Page content -->
            <div class="flex flex-col space-y-6">
                @yield('breadcrumb')
                @yield('create_trash')
                @yield('content')
            </div>

        </div>
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- AlpineJS already loaded in head -->
    <!-- App.js -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Sidebar toggle & current date -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleSidebar');

        // Display current date
        const currentDate = document.getElementById("currentDate");
        if (currentDate) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            currentDate.textContent = new Date().toLocaleDateString(undefined, options);
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            if (sidebar.classList.contains('-translate-x-full')) {
                mainContent.classList.remove('ml-56');
                mainContent.classList.add('ml-0');
            } else {
                mainContent.classList.remove('ml-0');
                mainContent.classList.add('ml-56');
            }
        });
    </script>

    <!-- Page-specific scripts -->
    @yield('scripts')

</body>
</html>
