<!DOCTYPE html>
<html lang="id" class="h-full bg-page">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Si Bakat Hebat — @yield('title', 'Tes Karakter TB-40')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-full bg-page text-ink-soft antialiased">
<div class="min-h-screen flex flex-col overflow-x-hidden">

    {{-- Header --}}
    <header class="w-full border-b border-line bg-surface" x-data="{ menuOpen: false }">
        <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 sm:px-8 py-3.5">
            <a href="{{ url('/') }}" class="flex items-center text-lg font-bold tracking-tight text-ink">
                <img src="{{ asset('img/navbarlogo.png') }}" alt="Si Bakat Hebat" class="h-20 w-auto">
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-4 text-sm">
                {{-- WhatsApp --}}
                @php
                    $wa = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));
                    if (str_starts_with($wa, '0')) {
                        $wa = '62'.substr($wa, 1); // 08xx -> 628xx untuk wa.me
                    }
                @endphp
                <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"
                   title="Hubungi Kami via WhatsApp"
                   class="inline-flex items-center gap-1.5 font-medium text-ink-soft hover:text-brand transition">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.8 4.9-1.3A10 10 0 1 0 12 2zm5.8 14.3c-.2.7-1.4 1.3-2 1.4-.5.1-1.2.1-1.9-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5.1-4.5-.1-.2-1.2-1.5-1.2-2.9s.7-2 1-2.3c.2-.2.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 2c.1.2.1.4 0 .5l-.3.5-.4.4c-.1.1-.3.3-.1.6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.3 2.4 1.5.3.1.5.1.6-.1l.7-.9c.2-.2.4-.2.6-.1l1.9.9c.3.2.5.2.5.4.1.1.1.6-.1 1.3z"/>
                    </svg>
                    <span>Hubungi Kami</span>
                </a>

                @auth
                    @if (auth()->user()->canViewDashboard())
                        <a href="{{ route('admin.dashboard') }}" class="font-medium text-ink-soft hover:text-brand transition">Dashboard</a>
                    @elseif (! auth()->user()->butuhLengkapiProfil())
                        <a href="{{ route('anak.index') }}" class="font-medium text-ink-soft hover:text-brand transition">Anak Didik</a>
                        <a href="{{ route('riwayat') }}" class="font-medium text-ink-soft hover:text-brand transition">Riwayat</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-full bg-brand px-4 py-2 font-semibold text-white hover:bg-brand-dark transition">
                            {{ auth()->user()->name }} &bull; Keluar
                        </button>
                    </form>
                @endauth
            </div>

            {{-- Mobile Hamburger Button --}}
            <button @click="menuOpen = !menuOpen" class="md:hidden p-2 text-ink-soft hover:text-brand transition">
                <svg x-show="!menuOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <svg x-show="menuOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu Dropdown --}}
        <div x-show="menuOpen" @click.outside="menuOpen = false"
             class="md:hidden border-t border-line bg-surface">
            <div class="mx-auto max-w-6xl px-4 py-4 space-y-3 text-sm">
                {{-- WhatsApp Mobile --}}
                @php
                    $wa = preg_replace('/\D/', '', (string) config('services.whatsapp.number'));
                    if (str_starts_with($wa, '0')) {
                        $wa = '62'.substr($wa, 1);
                    }
                @endphp
                <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"
                   class="flex items-center gap-1.5 font-medium text-ink-soft hover:text-brand transition py-2">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.8 4.9-1.3A10 10 0 1 0 12 2zm5.8 14.3c-.2.7-1.4 1.3-2 1.4-.5.1-1.2.1-1.9-.1-.4-.1-1-.3-1.7-.6-3-1.3-4.9-4.3-5.1-4.5-.1-.2-1.2-1.5-1.2-2.9s.7-2 1-2.3c.2-.2.5-.3.7-.3h.5c.2 0 .4 0 .6.5l.8 2c.1.2.1.4 0 .5l-.3.5-.4.4c-.1.1-.3.3-.1.6.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.3 2.4 1.5.3.1.5.1.6-.1l.7-.9c.2-.2.4-.2.6-.1l1.9.9c.3.2.5.2.5.4.1.1.1.6-.1 1.3z"/>
                    </svg>
                    Hubungi Kami
                </a>

                @auth
                    @if (auth()->user()->canViewDashboard())
                        <a href="{{ route('admin.dashboard') }}" class="font-medium text-ink-soft hover:text-brand transition block py-2">Dashboard</a>
                    @elseif (! auth()->user()->butuhLengkapiProfil())
                        <a href="{{ route('anak.index') }}" class="font-medium text-ink-soft hover:text-brand transition block py-2">Anak Didik</a>
                        <a href="{{ route('riwayat') }}" class="font-medium text-ink-soft hover:text-brand transition block py-2">Riwayat</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}" class="py-2">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-full bg-brand px-4 py-2 font-semibold text-white hover:bg-brand-dark transition">
                            {{ auth()->user()->name }} &bull; Keluar
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    {{-- Main --}}
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-10">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="w-full border-t border-line bg-surface px-4 py-5 text-center text-sm text-ink-soft">
        <p>&copy; {{ date('Y') }} <span class="font-semibold text-ink">Si Bakat Hebat</span> &bull; Tes Karakter TB-40</p>
    </footer>

</div>
</body>
</html>
