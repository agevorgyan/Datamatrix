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

    <!-- Top Navigation Bar with Burger Menu -->
    <header class="bg-slate-900 text-white shadow-lg border-b border-slate-800 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Enlarged Logo & Brand Header (No border) -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3.5 group">
                    <img src="{{ asset('images/logo.png') }}" alt="elab logo" class="h-14 sm:h-16 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
                    <div class="flex flex-col">
                        <span class="text-lg sm:text-xl md:text-2xl font-black tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 via-sky-300 to-indigo-300">
                            DataMatrix Print Engine
                        </span>
                        <span class="text-xs text-emerald-400 font-medium tracking-wide">by elab Digital Studio</span>
                    </div>
                </a>
            </div>

            <!-- Burger Menu Trigger Button -->
            <div class="flex items-center space-x-3">
                @auth
                    <div class="hidden sm:flex items-center space-x-2 text-xs text-slate-300 bg-slate-800/90 px-3.5 py-1.5 rounded-full border border-slate-700/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="font-semibold">{{ Auth::user()->name }}</span>
                    </div>
                @endauth

                <button type="button" id="mobileMenuBtn" class="px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 hover:text-white border border-slate-700 font-bold text-xs shadow-md transition flex items-center space-x-2 focus:outline-none">
                    <svg class="h-5 w-5 text-emerald-400" id="burgerIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="h-5 w-5 text-emerald-400 hidden" id="closeIcon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="tracking-wide">ՄԵՆՅՈՒ</span>
                </button>
            </div>
        </div>

        <!-- Burger Menu Dropdown Drawer Panel -->
        <div id="mobileDrawer" class="hidden bg-slate-900/95 backdrop-blur-xl border-b border-slate-800 px-4 sm:px-8 py-5 shadow-2xl transition-all">
            <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="p-3.5 rounded-xl transition-all border {{ request()->routeIs('dashboard') ? 'bg-emerald-950/70 border-emerald-500/50 text-emerald-300' : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-800 hover:text-white hover:border-slate-600' }} flex items-center space-x-3">
                        <span class="text-xl">📊</span>
                        <div>
                            <div class="font-bold text-sm">Dashboard / Տպագրություն</div>
                            <div class="text-[11px] text-slate-400">Կոդերի ներբեռնում և տպում</div>
                        </div>
                    </a>

                    <a href="{{ route('settings.edit') }}" class="p-3.5 rounded-xl transition-all border {{ request()->routeIs('settings.*') ? 'bg-emerald-950/70 border-emerald-500/50 text-emerald-300' : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-800 hover:text-white hover:border-slate-600' }} flex items-center space-x-3">
                        <span class="text-xl">⚙️</span>
                        <div>
                            <div class="font-bold text-sm">Կարգավորումներ</div>
                            <div class="text-[11px] text-slate-400">Լեյբլի չափսեր, DPI, margins</div>
                        </div>
                    </a>

                    <a href="{{ route('history.index') }}" class="p-3.5 rounded-xl transition-all border {{ request()->routeIs('history.*') ? 'bg-emerald-950/70 border-emerald-500/50 text-emerald-300' : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-800 hover:text-white hover:border-slate-600' }} flex items-center space-x-3">
                        <span class="text-xl">📜</span>
                        <div>
                            <div class="font-bold text-sm">Տպագրության Պատմություն</div>
                            <div class="text-[11px] text-slate-400">Նախկինում տպված batch-եր</div>
                        </div>
                    </a>

                    <a href="{{ route('help.index') }}" class="p-3.5 rounded-xl transition-all border {{ request()->routeIs('help.*') ? 'bg-emerald-950/70 border-emerald-500/50 text-emerald-300' : 'bg-slate-800/60 border-slate-700/60 text-slate-300 hover:bg-slate-800 hover:text-white hover:border-slate-600' }} flex items-center space-x-3">
                        <span class="text-xl">❓</span>
                        <div>
                            <div class="font-bold text-sm">Օգնություն & Ուղեցույց</div>
                            <div class="text-[11px] text-slate-400">Հաճախ տրվող հարցեր</div>
                        </div>
                    </a>

                    <div class="sm:col-span-2 md:col-span-4 pt-3 mt-2 border-t border-slate-800 flex items-center justify-between">
                        <span class="text-xs text-slate-400">👤 Մուտք գործված է որպես՝ <b class="text-white">{{ Auth::user()->name }}</b></span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl bg-rose-600/20 text-rose-300 hover:bg-rose-600 hover:text-white transition border border-rose-500/30 flex items-center space-x-1.5">
                                <span>🚪</span>
                                <span>Դուրս գալ</span>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="p-3.5 rounded-xl bg-slate-800/60 text-slate-300 hover:bg-slate-800 hover:text-white border border-slate-700 flex items-center space-x-3">
                        <span class="text-xl">🔑</span>
                        <span class="font-bold text-sm">Մուտք</span>
                    </a>
                    <a href="{{ route('register') }}" class="p-3.5 rounded-xl bg-emerald-600 text-white font-bold hover:bg-emerald-500 flex items-center space-x-3">
                        <span class="text-xl">✨</span>
                        <span class="font-bold text-sm">Գրանցվել</span>
                    </a>
                @endauth
            </div>
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

    <!-- Floating Cookie Consent Banner -->
    <div id="cookieConsentBanner" class="fixed bottom-4 left-4 right-4 md:left-auto md:right-6 md:max-w-md z-50 bg-slate-900/95 backdrop-blur-md text-white p-5 rounded-2xl shadow-2xl border border-slate-700/80 transition-all duration-300 transform translate-y-32 opacity-0 pointer-events-none">
        <div class="flex items-start space-x-3">
            <span class="text-2xl shrink-0">🍪</span>
            <div class="space-y-2">
                <h4 class="text-sm font-bold text-white">
                    Cookie Ֆայլերի Օգտագործում
                </h4>
                <p class="text-xs text-slate-300 leading-relaxed">
                    Մեր համակարգն օգտագործում է Cookie ֆայլեր՝ աշխատանքի հարմարավետությունն ու անվտանգությունն ապահովելու համար։ Ավելին իմանալու համար կարդացեք մեր <a href="{{ route('privacy') }}" class="text-emerald-400 underline hover:text-emerald-300 font-medium">Գաղտնիության Քաղաքականությունը</a>։
                </p>
                <div class="pt-1 flex items-center space-x-2">
                    <button type="button" id="acceptCookieBtn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-md transition border border-emerald-500">
                        ✅ Ընդունել Բոլորը
                    </button>
                    <button type="button" id="declineCookieBtn" class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold rounded-xl border border-slate-700 transition">
                        Մերժել
                    </button>
                </div>
            </div>
        </div>
    </div>

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

        // Cookie Consent Logic
        const cookieBanner = document.getElementById('cookieConsentBanner');
        const acceptCookieBtn = document.getElementById('acceptCookieBtn');
        const declineCookieBtn = document.getElementById('declineCookieBtn');

        if (cookieBanner) {
            const hasConsented = localStorage.getItem('cookie_consent');
            if (!hasConsented) {
                setTimeout(() => {
                    cookieBanner.classList.remove('translate-y-32', 'opacity-0', 'pointer-events-none');
                }, 500);
            }

            if (acceptCookieBtn) {
                acceptCookieBtn.addEventListener('click', function () {
                    localStorage.setItem('cookie_consent', 'accepted');
                    cookieBanner.classList.add('translate-y-32', 'opacity-0', 'pointer-events-none');
                });
            }

            if (declineCookieBtn) {
                declineCookieBtn.addEventListener('click', function () {
                    localStorage.setItem('cookie_consent', 'declined');
                    cookieBanner.classList.add('translate-y-32', 'opacity-0', 'pointer-events-none');
                });
            }
        }
    </script>

    @yield('scripts')
</body>
</html>
