<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Job Portal') - {{ config('app.name', 'Laravel') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex flex-col antialiased">
        
        <!-- Header / Navigation -->
        <header class="w-full border-b border-[#19140035] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] sticky top-0 z-50 shadow-xs">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                            <!-- Premium Tech Icon -->
                            <div class="w-9 h-9 rounded-md bg-[#f53003] dark:bg-[#FF4433] flex items-center justify-center text-white font-bold text-lg shadow-sm transition-transform group-hover:scale-105">
                                J
                            </div>
                            <span class="font-semibold text-lg tracking-tight text-[#1b1b18] dark:text-[#EDEDEC]">
                                Job<span class="text-[#f53003] dark:text-[#FF4433]">Portal</span>
                            </span>
                        </a>
                    </div>
                    
                    <nav class="flex items-center gap-6">
                        <a 
                            href="{{ route('jobs.index') }}" 
                            class="text-sm font-medium transition-colors hover:text-[#f53003] dark:hover:text-[#FF4433] {{ Request::is('jobs*') ? 'text-[#f53003] dark:text-[#FF4433] font-semibold border-b-2 border-[#f53003] dark:border-[#FF4433] py-5 -mb-px' : 'text-[#706f6c] dark:text-[#A1A09A]' }}"
                        >
                            Jobs
                        </a>
                        <a 
                            href="{{ route('positions.index') }}" 
                            class="text-sm font-medium transition-colors hover:text-[#f53003] dark:hover:text-[#FF4433] {{ Request::is('positions*') ? 'text-[#f53003] dark:text-[#FF4433] font-semibold border-b-2 border-[#f53003] dark:border-[#FF4433] py-5 -mb-px' : 'text-[#706f6c] dark:text-[#A1A09A]' }}"
                        >
                            Positions
                        </a>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 py-10">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Flash Notification Banner -->
                @if (session('success'))
                    <div id="flash-success" class="p-4 rounded-md border border-[#19140035] dark:border-[#3E3E3A] bg-emerald-50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-300 flex items-center gap-3 shadow-lg transition-opacity duration-500" style="position: fixed; top: 1.5rem; right: 1.5rem; z-index: 99999; min-width: 300px;">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div id="flash-error" class="p-4 rounded-md border border-[#19140035] dark:border-[#3E3E3A] bg-[#fff2f2] dark:bg-[#1D0002] text-[#f53003] dark:text-[#FF4433] flex items-center gap-3 shadow-lg transition-opacity duration-500" style="position: fixed; top: 1.5rem; right: 1.5rem; z-index: 99999; min-width: 300px;">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="border-t border-[#19140035] dark:border-[#3E3E3A] bg-white dark:bg-[#161615] py-6 text-center text-xs text-[#706f6c] dark:text-[#A1A09A]">
            <div class="max-w-6xl mx-auto px-4">
                &copy; {{ date('Y') }} JobPortal. Built with Laravel and Tailwind CSS.
            </div>
        </footer>
        
        @yield('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const successMsg = document.getElementById('flash-success');
                const errorMsg = document.getElementById('flash-error');
                
                function fadeOutAndRemove(el) {
                    if (el) {
                        setTimeout(() => {
                            el.style.opacity = '0';
                            setTimeout(() => el.remove(), 500);
                        }, 3000);
                    }
                }
                
                fadeOutAndRemove(successMsg);
                fadeOutAndRemove(errorMsg);
            });
        </script>
    </body>
</html>
