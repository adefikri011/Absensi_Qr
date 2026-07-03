<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:600,700|figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-display {
            font-family: 'Sora', ui-sans-serif, system-ui, sans-serif;
        }

        @keyframes scan-move {
            0% {
                top: 6%;
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                top: 94%;
                opacity: 0;
            }
        }

        .scan-line {
            animation: scan-move 2.8s ease-in-out infinite;
        }

        @keyframes glow-pulse {

            0%,
            100% {
                opacity: 0.35;
                transform: scale(1);
            }

            50% {
                opacity: 0.55;
                transform: scale(1.06);
            }
        }

        .qr-glow {
            animation: glow-pulse 3.5s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-white min-h-screen antialiased">

    <div class="lg:grid lg:grid-cols-2 min-h-screen">

        {{-- Left panel — dark, QR showcase --}}
        <aside class="hidden lg:flex relative flex-col overflow-hidden bg-[#0F1115] px-14 py-12">

            {{-- Subtle dot grid texture --}}
            <div class="absolute inset-0 opacity-[0.25]"
                style="background-image: radial-gradient(#2A2E3A 1px, transparent 1px);
                        background-size: 18px 18px;">
            </div>

            {{-- Top: brand mark --}}
            <div class="relative z-10 flex items-center gap-2.5">
                <span class="text-white font-medium text-sm tracking-wide">Sistem Absensi</span>
            </div>

            {{-- Middle: greeting, clock, QR showcase --}}
            <div class="relative z-10 flex-1 flex flex-col items-center justify-center text-center">

                <p id="live-greeting" class="text-[#F5A524] text-sm font-medium mb-1">
                    {{ now()->hour < 11 ? 'Selamat pagi' : (now()->hour < 15 ? 'Selamat siang' : (now()->hour < 19 ? 'Selamat sore' : 'Selamat malam')) }}
                </p>
                <p id="live-clock"
                    class="font-display text-white text-3xl font-semibold tracking-tight tabular-nums mb-10">
                    {{ now()->format('H:i') }}
                </p>

                {{-- QR scan card --}}
                <div class="relative w-60 h-60">

                    {{-- glow behind card --}}
                    <div class="qr-glow absolute inset-0 rounded-[28px] bg-[#F5A524] blur-3xl"></div>

                    {{-- viewfinder brackets --}}
                    <div class="absolute -top-2 -left-2 w-9 h-9 border-t-2 border-l-2 border-[#F5A524] rounded-tl-xl">
                    </div>
                    <div class="absolute -top-2 -right-2 w-9 h-9 border-t-2 border-r-2 border-[#F5A524] rounded-tr-xl">
                    </div>
                    <div
                        class="absolute -bottom-2 -left-2 w-9 h-9 border-b-2 border-l-2 border-[#F5A524] rounded-bl-xl">
                    </div>
                    <div
                        class="absolute -bottom-2 -right-2 w-9 h-9 border-b-2 border-r-2 border-[#F5A524] rounded-br-xl">
                    </div>

                    {{-- card --}}
                    <div
                        class="relative w-full h-full rounded-2xl bg-white p-4 shadow-2xl shadow-black/40 overflow-hidden">
                        <img src="{{ asset('asset/image/qrcode.webp') }}" alt="QR Presensi"
                            class="w-full h-full object-contain" />

                        {{-- animated scan line --}}
                        <div
                            class="scan-line absolute left-3 right-3 h-[2px] bg-gradient-to-r from-transparent via-[#F5A524] to-transparent">
                        </div>
                    </div>
                </div>

                <p class="text-slate-400 text-sm mt-6 max-w-[240px] leading-relaxed">
                    Scan, catat, selesai. Presensi yang tepat waktu bikin hari kerja lebih terarah.
                </p>
            </div>

            {{-- Bottom: footer --}}
            <p class="relative z-10 text-slate-500 text-xs">
                © {{ date('Y') }} Sistem Absensi QR
            </p>
        </aside>

        {{-- Right panel — white, the form --}}
        <div class="flex flex-col justify-center px-6 py-12 sm:px-12 lg:px-16 xl:px-24">

            {{-- Mobile-only compact header --}}
            <div class="lg:hidden flex items-center gap-2.5 justify-center mb-10">
                <span class="text-slate-900 font-medium text-sm tracking-wide">Sistem Absensi</span>
            </div>

            <div class="w-full max-w-sm mx-auto">
                {{ $slot }}
            </div>

            <p class="lg:hidden text-center text-xs text-slate-400 mt-10">
                © {{ date('Y') }} Sistem Absensi QR
            </p>
        </div>
    </div>

    <script>
        (function() {
            var clockEl = document.getElementById('live-clock');
            var greetEl = document.getElementById('live-greeting');

            function pad(n) {
                return n.toString().padStart(2, '0');
            }

            function tick() {
                var now = new Date();
                if (clockEl) clockEl.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes());
                if (greetEl) {
                    var h = now.getHours(),
                        g = 'Selamat malam';
                    if (h < 11) g = 'Selamat pagi';
                    else if (h < 15) g = 'Selamat siang';
                    else if (h < 19) g = 'Selamat sore';
                    greetEl.textContent = g;
                }
            }
            tick();
            setInterval(tick, 30000);
        })();
    </script>

</body>

</html>
