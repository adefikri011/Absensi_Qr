@extends('employee.layout')
@section('title', 'Scan QR')

@section('content')

    <style>
        #reader__scan_region img,
        #reader__scan_region svg,
        #reader__scan_region>div {
            display: none !important;
            border: none !important;
        }

        #reader__scan_region {
            border: none !important;
            box-shadow: none !important;
        }

        #reader {
            border: none !important;
            outline: none !important;
            box-shadow: none !important;
        }

        /* Hilangkan kotak scan putih bawaan */
        #qr-shaded-region {
            border-width: 0 !important;
        }

        /* Sembunyikan inner border */
        #qr-shaded-region div {
            display: none !important;
        }
    </style>

    {{-- Header Card --}}
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-500
            rounded-2xl p-6 text-white mb-6 shadow-lg">
        <p class="text-indigo-200 text-sm">
            {{ now()->format('l, d F Y') }}
        </p>
        <h2 class="text-xl font-bold mt-1">
            Scan QR Absensi
        </h2>
        <p class="text-indigo-200 text-sm mt-1">
            Arahkan kamera ke QR Code di monitor kantor
        </p>
    </div>

    {{-- Scanner Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">

        {{-- Status Info --}}
        @php
            $today = \App\Models\Attendance::where('user_id', auth()->id())
                ->whereDate('date', today())
                ->first();
        @endphp

        @if ($today && $today->time_out)
            <div class="flex items-center gap-3 mb-5 p-4
                    bg-green-50 rounded-xl border border-green-100">
                <div
                    class="w-10 h-10 bg-green-100 rounded-xl
                        flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-green-700 text-sm">
                        Absensi Hari Ini Sudah Lengkap
                    </p>
                    <p class="text-xs text-green-500 mt-0.5">
                        Masuk: {{ \Carbon\Carbon::parse($today->time_in)->format('H:i') }} —
                        Pulang: {{ \Carbon\Carbon::parse($today->time_out)->format('H:i') }}
                    </p>
                </div>
            </div>
        @elseif($today && !$today->time_out)
            <div class="flex items-center gap-3 mb-5 p-4
                    bg-blue-50 rounded-xl border border-blue-100">
                <div
                    class="w-10 h-10 bg-blue-100 rounded-xl
                        flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-blue-700 text-sm">
                        Sudah Check-in
                    </p>
                    <p class="text-xs text-blue-500 mt-0.5">
                        Jam masuk: {{ \Carbon\Carbon::parse($today->time_in)->format('H:i') }} •
                        Scan lagi untuk Check-out
                    </p>
                </div>
            </div>
        @else
            <div
                class="flex items-center gap-3 mb-5 p-4
                    bg-orange-50 rounded-xl border border-orange-100">
                <div
                    class="w-10 h-10 bg-orange-100 rounded-xl
                        flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-orange-700 text-sm">
                        Belum Absen Hari Ini
                    </p>
                    <p class="text-xs text-orange-500 mt-0.5">
                        Scan QR untuk Check-in
                    </p>
                </div>
            </div>
        @endif

        {{-- ✅ QR Scanner dengan Overlay Animasi --}}
        <div class="relative rounded-xl overflow-hidden bg-black">

            {{-- Camera --}}
            <div id="reader" class="w-full"></div>

            {{-- Dark Overlay --}}
            <div class="absolute inset-0 bg-black/30 pointer-events-none"></div>

            {{-- Scan Frame Overlay --}}
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                <div class="relative w-56 h-56" id="scan-frame">

                    {{-- Corner TL --}}
                    <div
                        class="absolute top-0 left-0 w-8 h-8
                            border-t-4 border-l-4 border-indigo-400
                            rounded-tl-xl transition-colors duration-300">
                    </div>
                    {{-- Corner TR --}}
                    <div
                        class="absolute top-0 right-0 w-8 h-8
                            border-t-4 border-r-4 border-indigo-400
                            rounded-tr-xl transition-colors duration-300">
                    </div>
                    {{-- Corner BL --}}
                    <div
                        class="absolute bottom-0 left-0 w-8 h-8
                            border-b-4 border-l-4 border-indigo-400
                            rounded-bl-xl transition-colors duration-300">
                    </div>
                    {{-- Corner BR --}}
                    <div
                        class="absolute bottom-0 right-0 w-8 h-8
                            border-b-4 border-r-4 border-indigo-400
                            rounded-br-xl transition-colors duration-300">
                    </div>

                    {{-- Laser Line --}}
                    <div id="laser-line"
                        class="absolute left-2 right-2 h-0.5
                            bg-gradient-to-r from-transparent via-indigo-400 to-transparent
                            shadow-[0_0_6px_2px_rgba(99,102,241,0.6)]
                            animate-scanLine">
                    </div>

                    {{-- Detected Flash --}}
                    <div id="detected-flash"
                        class="absolute inset-0 bg-green-400/0 rounded-xl
                            transition-all duration-300">
                    </div>

                </div>
            </div>

            {{-- Scanning Label --}}
            <div class="absolute bottom-3 left-0 right-0 flex justify-center pointer-events-none">
                <span id="scan-label" class="bg-black/50 text-white text-xs px-3 py-1 rounded-full">
                    Mencari QR Code...
                </span>
            </div>

        </div>

        <p class="text-xs text-slate-400 text-center mt-3">
            Pastikan kamera sudah diizinkan di browser kamu
        </p>

    </div>

    {{-- Result Notification --}}
    <div id="result-box" class="hidden rounded-2xl p-4 mb-6 flex items-center gap-3">
    </div>

@endsection

{{-- ✅ Success Overlay (di luar content supaya fullscreen) --}}
<div id="success-overlay"
    class="fixed inset-0 bg-black/70 backdrop-blur-sm
            hidden items-center justify-center z-[999]">

    <div id="success-card"
        class="bg-white rounded-3xl p-10 flex flex-col items-center mx-6
                scale-90 opacity-0 transition-all duration-300">

        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4" id="result-icon-wrapper">
        </div>

        <p class="text-lg font-bold text-slate-800 mb-1" id="result-title">
            Berhasil ✅
        </p>

        <p class="text-sm text-slate-500 text-center" id="result-desc">
            Data sedang diproses...
        </p>

    </div>
</div>

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        let scanned = false;

        // ✅ Detected flash (corners turn green saat QR terdeteksi)
        function flashDetected() {
            const flash = document.getElementById('detected-flash');
            const label = document.getElementById('scan-label');
            const corners = document.querySelectorAll('#scan-frame > div:not(#laser-line):not(#detected-flash)');

            // Flash green
            flash.classList.add('bg-green-400/30');
            label.textContent = 'QR Terdeteksi! ✅';

            corners.forEach(c => {
                c.classList.remove('border-indigo-400');
                c.classList.add('border-green-400');
            });

            setTimeout(() => {
                flash.classList.remove('bg-green-400/30');
            }, 300);
        }

        // ✅ Show fullscreen overlay
        function showOverlay(isSuccess, message, desc) {
            const overlay = document.getElementById('success-overlay');
            const card = document.getElementById('success-card');
            const iconWrapper = document.getElementById('result-icon-wrapper');
            const title = document.getElementById('result-title');
            const descEl = document.getElementById('result-desc');

            if (isSuccess) {
                iconWrapper.className = 'w-20 h-20 rounded-full flex items-center justify-center mb-4 bg-green-100';
                iconWrapper.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-10 h-10 text-green-600"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="3">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>`;
            } else {
                iconWrapper.className = 'w-20 h-20 rounded-full flex items-center justify-center mb-4 bg-red-100';
                iconWrapper.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-10 h-10 text-red-500"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="3">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>`;
            }

            title.textContent = message;
            descEl.textContent = desc;

            overlay.classList.remove('hidden');
            overlay.classList.add('flex');

            setTimeout(() => {
                card.classList.remove('scale-90', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 50);

            setTimeout(() => {
                window.location.reload();
            }, 2000);
        }

        // ✅ Main scan handler
        function onScanSuccess(decodedText) {
            if (scanned) return;
            scanned = true;

            // Flash detected animation
            flashDetected();

            // Vibrate
            if (navigator.vibrate) {
                navigator.vibrate([100, 50, 100]);
            }

            // Sound
            try {
                const ctx = new(window.AudioContext || window.webkitAudioContext)();
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, ctx.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(440, ctx.currentTime + 0.1);
                gainNode.gain.setValueAtTime(0.3, ctx.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
                oscillator.start(ctx.currentTime);
                oscillator.stop(ctx.currentTime + 0.3);
            } catch (e) {}

            // Fetch process
            fetch('/process-scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        token: decodedText
                    })
                })
                .then(res => res.json())
                .then(data => {
                    const isSuccess = data.message && !data.error;
                    showOverlay(
                        isSuccess,
                        data.message ?? 'Absensi Berhasil ✅',
                        isSuccess ? 'Data tersimpan. Halaman akan diperbarui...' : 'Silakan coba lagi.'
                    );
                })
                .catch(() => {
                    scanned = false;
                    showOverlay(false, 'Terjadi Kesalahan ❌', 'Coba scan ulang.');
                });
        }

        // ✅ Init scanner
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                },
                rememberLastUsedCamera: true,
            },
            false
        );

        html5QrcodeScanner.render(onScanSuccess);
    </script>
@endpush
