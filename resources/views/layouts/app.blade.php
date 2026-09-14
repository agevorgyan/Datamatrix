<!DOCTYPE html>
<html lang="hy">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DataMatrix Label Print Engine - datamatrix.elab.am</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <!-- BWIP-JS DataMatrix Generator -->
    <script src="https://cdn.jsdelivr.net/npm/bwip-js@3.4.4/dist/bwip-js-min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 flex flex-col min-h-screen font-sans antialiased">

    <!-- Top Navigation Bar with Responsive Burger Menu -->
    <header class="bg-slate-900 text-white shadow-md border-b border-slate-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            
            <!-- Logo & Brand Header -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.png') }}" alt="elab logo" class="h-10 w-auto object-contain bg-white/10 p-1 rounded-lg backdrop-blur border border-white/20 shadow-sm">
                    <div class="flex flex-col">
                        <span class="text-base sm:text-lg font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 via-sky-300 to-indigo-300">
                            DataMatrix Print Engine
                        </span>
                        <span class="text-[10px] text-emerald-400 font-medium tracking-wide">by elab Digital Studio</span>
                    </div>
                </a>
                <span class="hidden xl:inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-950/80 text-emerald-300 border border-emerald-500/40">
                    Ունիվերսալ Լեյբլ Պրինտեր
                </span>
            </div>

            <!-- Desktop Navigation Links -->
            <nav class="hidden md:flex items-center space-x-1 lg:space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-emerald-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        📊 Dashboard / Տպագրություն
                    </a>
                    <a href="{{ route('settings.edit') }}" class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('settings.*') ? 'bg-slate-800 text-emerald-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        ⚙️ Կարգավորումներ
                    </a>
                    <a href="{{ route('history.index') }}" class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('history.*') ? 'bg-slate-800 text-emerald-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        📜 Պատմություն
                    </a>
                    <a href="{{ route('help.index') }}" class="px-3 py-2 rounded-lg text-xs font-bold transition-colors {{ request()->routeIs('help.*') ? 'bg-slate-800 text-emerald-400 border border-slate-700' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        ❓ Օգնություն
                    </a>

                    <div class="pl-3 border-l border-slate-800 flex items-center space-x-3">
                        <span class="text-xs text-slate-400 hidden lg:inline-block">
                            👤 {{ Auth::user()->name }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-rose-600/20 text-rose-300 hover:bg-rose-600 hover:text-white transition-all border border-rose-500/30">
                                Դուրս գալ
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-xs font-bold text-slate-300 hover:text-white">
                        Մուտք
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white transition-all shadow-sm">
                        Գրանցվել
                    </a>
                @endauth
            </nav>

            <!-- Mobile Hamburger (Burger) Button -->
            <div class="flex items-center md:hidden">
                <button type="button" id="mobileMenuBtn" class="p-2 rounded-lg bg-slate-800 text-slate-300 hover:text-white focus:outline-none border border-slate-700">
                    <svg class="h-6 w-6" id="burgerIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-6 w-6 hidden" id="closeIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu Dropdown -->
        <div id="mobileDrawer" class="hidden md:hidden bg-slate-900 border-b border-slate-800 px-4 pt-2 pb-4 space-y-2">
            @auth
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-bold {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    📊 Dashboard / Տպագրություն
                </a>
                <a href="{{ route('settings.edit') }}" class="block px-3 py-2 rounded-lg text-sm font-bold {{ request()->routeIs('settings.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    ⚙️ Լեյբլի Կարգավորումներ
                </a>
                <a href="{{ route('history.index') }}" class="block px-3 py-2 rounded-lg text-sm font-bold {{ request()->routeIs('history.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    📜 Տպագրության Պատմություն
                </a>
                <a href="{{ route('help.index') }}" class="block px-3 py-2 rounded-lg text-sm font-bold {{ request()->routeIs('help.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300 hover:bg-slate-800' }}">
                    ❓ Օգնություն & Ուղեցույց
                </a>

                <div class="pt-3 border-t border-slate-800 flex items-center justify-between">
                    <span class="text-xs text-slate-400">👤 {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-bold rounded-lg bg-rose-600/20 text-rose-300 hover:bg-rose-600 hover:text-white transition border border-rose-500/30">
                            Դուրս գալ
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm font-bold text-slate-300 hover:bg-slate-800">
                    Մուտք
                </a>
                <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-sm font-bold bg-emerald-600 text-white text-center">
                    Գրանցվել
                </a>
            @endauth
        </div>
    </header>

    <!-- Global Notification Messages -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-sm flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">✅</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <span class="text-lg">⚠️</span>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-xl bg-rose-50 text-rose-800 border border-rose-200 shadow-sm mb-2">
                <div class="font-medium text-sm mb-1">Խնդրում ենք ուղղել հետևյալ սխալները․</div>
                <ul class="list-disc list-inside text-xs space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content Body -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-sm border-t border-slate-800 mt-12 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <a href="{{ route('terms') }}" class="hover:text-emerald-400 transition-colors">Օգտագործման պայմաններ</a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('privacy') }}" class="hover:text-emerald-400 transition-colors">Գաղտնիության քաղաքականություն</a>
                <span class="text-slate-700">•</span>
                <a href="{{ route('help.index') }}" class="hover:text-emerald-400 transition-colors">Օգնություն</a>
            </div>
            <div class="flex items-center space-x-3 text-center md:text-right">
                <img src="{{ asset('images/logo.png') }}" alt="elab logo" class="h-7 w-auto object-contain inline-block">
                <span class="font-medium text-slate-300">
                    Համակարգը պատրաստված է սիրով <a href="https://elab.am" target="_blank" rel="noopener" class="text-emerald-400 hover:text-emerald-300 font-bold hover:underline">elab.am</a>-ի կողմից
                </span>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Burger Menu Toggle Logic
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileDrawer = document.getElementById('mobileDrawer');
        const burgerIcon = document.getElementById('burgerIcon');
        const closeIcon = document.getElementById('closeIcon');

        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function () {
                const isHidden = mobileDrawer.classList.contains('hidden');
                if (isHidden) {
                    mobileDrawer.classList.remove('hidden');
                    burgerIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                } else {
                    mobileDrawer.classList.add('hidden');
                    burgerIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                }
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
