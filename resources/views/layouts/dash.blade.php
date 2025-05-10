<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"class="light"> {{-- Set light as default --}}

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'JackNails') }} | Admin Dashboard</title> {{-- Add Admin Dashboard to title --}}

    {{-- Using bunny.net for Inter font via Vite --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    {{-- If you specifically need the Lexend font from Google Fonts --}}
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap" rel="stylesheet"> --}}


    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{-- @fluxAppearance --}} {{-- Keep if you are using Flux components --}}

    {{-- Optional: Specific styles if needed, e.g., for charts --}}
    {{-- <style>
        #chartdiv {
            width: 100%;
            height: 500px;
        }
    </style> --}}

    {{-- Optional: Styles for Material Symbols if used, ensure the font link is uncommented if needed --}}
    {{-- <style>
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24
        }
    </style> --}}

    {{-- Optional: Lexend font style if used and font linked --}}
    {{-- <style>
        .lexend {
            font-family: "Lexend", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }
    </style> --}}

    {{-- Remove loader styles unless you have a specific loading indicator --}}
    {{-- <style>
        .loader { ... }
        @keyframes l7 { ... }
    </style> --}}


</head>

<body class="min-h-screen bg-gray-100 dark:bg-zinc-800 font-sans antialiased"> {{-- Use a standard gray background --}}

    {{-- Adjusted classes for better contrast and slightly wider standard width --}}
    <aside id="sidebar"
        class="fixed top-0 left-0 z-40 h-screen w-64 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 bg-white dark:bg-zinc-900 border-r border-gray-200 dark:border-zinc-700 flex flex-col shadow-lg">

        <button id="sidebar-toggle-close"
            class="lg:hidden absolute top-4 right-4 p-2 text-gray-500 hover:text-gray-700 dark:text-zinc-400 dark:hover:text-zinc-200 z-50">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="flex items-center px-4 py-4 border-b border-gray-200 dark:border-zinc-700">
             <a href="{{ route('dashboard') }}" class="flex items-center"> {{-- Link to dashboard --}}
                {{-- Assuming you have a logo or text brand --}}
                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">J<span class="text-gray-800 dark:text-white">N</span></span>
                <span class="ml-2 text-xl font-semibold text-gray-800 dark:text-white">{{ config('app.name', 'JackNails') }}</span>
             </a>
        </div>


        {{-- <div class="px-4 py-3">
            <input type="search"
                class="w-full flex items-center px-3 py-2 bg-gray-100 dark:bg-zinc-700 rounded-md text-gray-500 dark:text-zinc-400 border border-gray-300 dark:border-zinc-600 focus:outline-none focus:ring focus:border-indigo-300"
                placeholder="Search..." />
        </div> --}}

        <nav class="mt-5 px-2 space-y-1 flex-grow"> {{-- Use flex-grow to push user section down --}}
            {{-- Dashboard Link --}}
            <a href="{{ route('dashboard') }}"
                class="flex items-center px-3 py-2 rounded-md transition duration-150 ease-in-out
                {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span>Dashboard</span>
            </a>

            {{-- Bookings Link --}}
            <a href="{{ route('admin.bookings') }}"
                class="flex items-center px-3 py-2 rounded-md transition duration-150 ease-in-out
                 {{ request()->routeIs('admin.bookings*') ? 'bg-gray-200 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                 <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg> {{-- Icon for bookings --}}
                <span>Bookings</span>
            </a>

            {{-- Time Slots Link --}}
             <a href="{{ route('admin.timeslots') }}"
                class="flex items-center px-3 py-2 rounded-md transition duration-150 ease-in-out
                 {{ request()->routeIs('admin.timeslots*') ? 'bg-gray-200 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{-- Icon for calendar/time --}}
                <span>Time Slots</span>
            </a>

             {{-- Reports Link --}}
             <a href="{{ route('admin.reports') }}"
                class="flex items-center px-3 py-2 rounded-md transition duration-150 ease-in-out
                 {{ request()->routeIs('admin.reports*') ? 'bg-gray-200 dark:bg-zinc-700 text-gray-900 dark:text-white' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800' }}">
                 <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg> {{-- Icon for reports/stats --}}
                <span>Reports</span>
            </a>

            {{-- Example Expandable Group (Keep if needed, otherwise remove) --}}
             {{-- <div data-expandable class="hidden lg:block">
                <button data-expandable-heading
                    class="w-full flex items-center justify-between px-3 py-2 text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md transition duration-150 ease-in-out">
                    <span class="font-medium text-sm">More</span>
                    <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                        </path>
                    </svg>
                </button>
                <div data-expandable-content class="ml-3 mt-1 space-y-1 hidden">
                     <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md">
                        <span>Sub Item 1</span>
                    </a>
                     <a href="#"
                        class="flex items-center px-3 py-2 text-gray-600 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-800 rounded-md">
                        <span>Sub Item 2</span>
                    </a>
                </div>
            </div> --}}

        </nav>

        <div class="px-4 py-4 border-t border-gray-200 dark:border-zinc-700">
            {{-- Assuming you are using Laravel Breeze or Jetstream for auth --}}
             @auth
				
						<form method="POST" action="{{ route('logout') }}">
							@csrf
							<button type="submit"
								class="flex items-center w-full text-left px-2 py-2 text-gray-700 dark:text-zinc-300 hover:bg-gray-100 dark:hover:bg-zinc-700 rounded-md">
								<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
									xmlns="http://www.w3.org/2000/svg">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
										d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
									</path>
								</svg>
								<span>Log Out</span>
							</button>
						</form>
					</div>
			 @endauth
        </div>
    </aside>

     <header class="lg:hidden fixed top-0 left-0 right-0 z-30 bg-white dark:bg-zinc-900 shadow p-4 flex items-center justify-between">
         <button id="sidebar-toggle-open"
            class="p-2 text-gray-500 hover:text-gray-700 dark:text-zinc-400 dark:hover:text-zinc-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
         <span class="text-lg font-semibold text-gray-800 dark:text-white">{{ config('app.name', 'JackNails') }}</span>
         {{-- Optional: Mobile User/Auth indicator --}}
         @auth
			<img src="{{ asset('storage/avatars/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}"
				 class="w-8 h-8 rounded-full object-cover">
		 @endauth
    </header>


    {{-- Added pt-16 to main to prevent content from being hidden by the fixed mobile header --}}
    <main class="lg:ml-64 p-6 pt-16 lg:pt-6">
        @yield('content')
    </main>

    {{-- Position adjusted to avoid profile dropdown --}}
    <button id="dark-mode-toggle"
        class="fixed bottom-4 right-4 p-3 bg-gray-200 dark:bg-zinc-700 rounded-full shadow-lg z-50 transition duration-200 ease-in-out hover:scale-110">
        <svg class="w-6 h-6 text-gray-800 dark:text-zinc-200 block dark:hidden" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
        <svg class="w-6 h-6 text-zinc-200 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
            </path>
        </svg>
    </button>

     @livewireScripts {{-- Livewire scripts --}}
     {{-- Add any other global scripts here if needed --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // --- Dark Mode Toggle ---
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const htmlElement = document.documentElement; // Use the root html element

            // Check for saved theme preference in localStorage
            const theme = localStorage.getItem('theme');

            // Apply saved theme or default to system preference
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                htmlElement.classList.add('dark');
                 htmlElement.classList.remove('light'); // Ensure 'light' is removed
            } else {
                htmlElement.classList.add('light'); // Default to light if no preference or preference is light
                 htmlElement.classList.remove('dark'); // Ensure 'dark' is removed
            }


            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function () {
                    if (htmlElement.classList.contains('dark')) {
                        htmlElement.classList.remove('dark');
                        htmlElement.classList.add('light');
                        localStorage.setItem('theme', 'light');
                    } else {
                        htmlElement.classList.remove('light');
                        htmlElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }

            // --- Sidebar Toggle (Mobile) ---
            const sidebarToggleOpen = document.getElementById('sidebar-toggle-open');
            const sidebarToggleClose = document.getElementById('sidebar-toggle-close');
            const sidebar = document.getElementById('sidebar');

            if (sidebarToggleOpen && sidebarToggleClose && sidebar) {
                sidebarToggleOpen.addEventListener('click', function () {
                    sidebar.classList.remove('-translate-x-full');
                });

                sidebarToggleClose.addEventListener('click', function () {
                    sidebar.classList.add('-translate-x-full');
                });
            }

            // --- Dropdown Functionality (for Profile Dropdown) ---
            const dropdownToggles = document.querySelectorAll('[data-dropdown-toggle]');

            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    e.stopPropagation(); // Prevent click from closing dropdown immediately
                    const targetId = this.getAttribute('data-dropdown-toggle');
                    const target = document.getElementById(targetId);

                    if (target) {
                        // Close other open dropdowns
                        document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
                            if (dropdown.id !== targetId) {
                                dropdown.classList.add('hidden');
                            }
                        });
                        // Toggle the clicked dropdown
                        target.classList.toggle('hidden');
                    }
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function (event) {
                 // Check if the click is outside any dropdown or dropdown toggle
                let isClickInsideDropdown = false;
                document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
                    if (dropdown.contains(event.target)) {
                        isClickInsideDropdown = true;
                    }
                });
                 let isClickInsideToggle = false;
                 document.querySelectorAll('[data-dropdown-toggle]').forEach(toggle => {
                     if (toggle.contains(event.target)) {
                         isClickInsideToggle = true;
                     }
                 });

                if (!isClickInsideDropdown && !isClickInsideToggle) {
                    document.querySelectorAll('[data-dropdown]').forEach(dropdown => {
                        dropdown.classList.add('hidden');
                    });
                }
            });

            // --- Expandable Nav Groups (Keep if used, remove if not) ---
            const expandableGroups = document.querySelectorAll('[data-expandable]');

            expandableGroups.forEach(group => {
                const heading = group.querySelector('[data-expandable-heading]');
                const content = group.querySelector('[data-expandable-content]');

                if (heading && content) {
                    heading.addEventListener('click', function () {
                        const isOpen = !content.classList.contains('hidden');

                         // Close other open expandable groups (optional)
                         // expandableGroups.forEach(otherGroup => {
                         //      if (otherGroup !== group) {
                         //          otherGroup.querySelector('[data-expandable-content]').classList.add('hidden');
                         //          otherGroup.querySelector('[data-expandable-heading] svg').classList.remove('rotate-180');
                         //      }
                         // });


                        content.classList.toggle('hidden');
                        heading.querySelector('svg').classList.toggle('rotate-180');
                    });
                }
            });
        });
    </script>
</body>

</html>