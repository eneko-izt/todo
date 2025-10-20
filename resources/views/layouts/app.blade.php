<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Task Manager') }}</title>

    <!-- Tailwind CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- AlpineJS -->
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="h-screen bg-gray-100">

    @php
        $currentRoute = Route::currentRouteName();
    @endphp

    <!-- Sidebar -->
    <div id="sidebar"
        class="fixed inset-y-0 left-0 w-56 bg-gray-800 text-white p-4 pt-12 flex flex-col space-y-2 transform transition-transform duration-300 z-20">

        <!-- Home -->
        <a class="block w-full bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded flex items-center justify-center mb-2"
            href="{{ route('welcome') }}">
            {{ __('Home') }}
        </a>

        <!-- Dashboard -->
        @if(auth()->check())
            <a class="block w-full bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded flex items-center justify-center mb-2"
                href="{{ route('home') }}">
                {{ __('Dashboard') }}
            </a>
        @endif

        <!-- TasksAdmin -->
        @can('viewAllTasks', App\Task::class)
            <div x-data="{ open: {{ Str::startsWith($currentRoute, 'tasks.') ? 'true' : 'false' }} }" class="mb-2">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded">
                    <span>{{ __('Tasks') }}</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform duration-200"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-3 flex flex-col space-y-1">
                    <a href="{{ route('tasks.index') }}"
                        class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'tasks.index') ? 'bg-gray-600' : '' }}">
                        {{ __('List') }}
                    </a>
                </div>
            </div>
        @endif

        <!-- Columns -->
        @can('viewColumn', App\Column::class)
            <div x-data="{ open: {{ Str::startsWith($currentRoute, 'columns.') ? 'true' : 'false' }} }" class="mb-2">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded">
                    <span>{{ __('Columns') }}</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform duration-200"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-3 flex flex-col space-y-1">
                    <a href="{{ route('columns.index') }}"
                        class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'columns.index') ? 'bg-gray-600' : '' }}">
                        {{ __('List') }}
                    </a>
                    @can('createColumn', App\Column::class)
                        <a href="{{ route('columns.create') }}"
                            class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'columns.create') ? 'bg-gray-600' : '' }}">
                            {{ __('New') }}
                        </a>
                    @endcan
                    @can('viewColumn', App\Column::class)
                        <a href="{{ route('columns.trash') }}"
                            class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'columns.trash') ? 'bg-gray-600' : '' }}">
                            {{ __('Trash') }}
                        </a>
                    @endcan
                </div>
            </div>
        @endcan

        <!-- Tags -->
        @can('viewTag', App\Tag::class)
            <div x-data="{ open: {{ Str::startsWith($currentRoute, 'tags.') ? 'true' : 'false' }} }" class="mb-2">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded">
                    <span>{{ __('Tags') }}</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform duration-200"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-3 flex flex-col space-y-1">
                    <a href="{{ route('tags.index') }}"
                        class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'tags.index') ? 'bg-gray-600' : '' }}">
                        {{ __('List') }}
                    </a>
                    @can('createTag', App\Tag::class)
                        <a href="{{ route('tags.create') }}"
                            class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'tags.create') ? 'bg-gray-600' : '' }}">
                            {{ __('New') }}
                        </a>
                    @endcan
                    @can('viewTrash', App\Tag::class)
                        <a href="{{ route('tags.trash') }}"
                            class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'tags.trash') ? 'bg-gray-600' : '' }}">
                            {{ __('Trash') }}
                        </a>
                    @endcan
                </div>
            </div>
        @endcan

        <!-- Users -->
        @can('viewUser', App\User::class)
            <div x-data="{ open: {{ Str::startsWith($currentRoute, 'users.') ? 'true' : 'false' }} }" class="mb-2">
                <button @click="open = !open"
                    class="w-full flex justify-between items-center bg-gray-700 hover:bg-gray-600 py-2 px-3 rounded">
                    <span>{{ __('Users') }}</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 transform transition-transform duration-200"
                        fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="mt-1 ml-3 flex flex-col space-y-1">
                    <a href="{{ route('users.index') }}"
                        class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'users.index') ? 'bg-gray-600' : '' }}">
                        {{ __('List') }}
                    </a>
                    @can('createUser', App\User::class)
                        <a href="{{ route('users.create') }}"
                            class="px-3 py-1 text-sm hover:bg-gray-600 rounded {{ Str::endsWith($currentRoute, 'users.create') ? 'bg-gray-600' : '' }}">
                            {{ __('New') }}
                        </a>
                    @endcan
                </div>
            </div>
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
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Task Manager') }}</h1>
                    <span class="text-gray-600 font-medium">{{ now()->locale(app()->getLocale())->isoFormat('LL') }}</span>
                </div>

                @error('email')
                    <p class="help is-danger" style="color:#d8000c">{{ $message }}</p>
                @enderror

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
                               class="block px-4 py-2 text-gray-700 hover:bg-gray-100">{{ __('Logout') }}</a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">{{ __('Login') }}</a>
                        <a href="{{ route('register') }}" class="text-blue-600 hover:underline">{{ __('Register') }}</a>
                    </div>
                @endauth
            </header>

            <!-- Page content -->
            <div class="flex space-x-6">
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

    <!-- Sidebar toggle -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('toggleSidebar');

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
