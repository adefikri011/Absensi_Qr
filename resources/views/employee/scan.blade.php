@extends('employee.layout')
@section('title', 'Scan QR')

@section('content')

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

    @if($today && $today->time_out)
        {{-- Sudah lengkap --}}
        <div class="flex items-center gap-3 mb-5 p-4
                    bg-green-50 rounded-xl border border-green-100">
            <div class="w-10 h-10 bg-green-100 rounded-xl
                        flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-green-600"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-green-700 text-sm">
                    Absensi Hari Ini Sudah Lengkap
                </p>
                <p class="text-xs text-green-500 mt-0.5">
                    Masuk: {{ $today->time_in }} —
                    Pulang: {{ $today->time_out }}
                </p>
            </div>
        </div>

    @elseif($today && !$today->time_out)
        {{-- Sudah check in, belum check out --}}
        <div class="flex items-center gap-3 mb-5 p-4
                    bg-blue-50 rounded-xl border border-blue-100">
            <div class="w-10 h-10 bg-blue-100 rounded-xl
                        flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-blue-600"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div>
                <p class="font-semibold text-blue-700 text-sm">
                    Sudah Check-in
                </p>
                <p class="text-xs text-blue-500 mt-0.5">
                    Jam masuk: {{ $today->time_in }} •
                    Scan lagi untuk Check-out
                </p>
            </div>
        </div>

    @else
        {{-- Belum absen --}}
        <div class="flex items-center gap-3 mb-5 p-4
                    bg-orange-50 rounded-xl border border-orange-100">
            <div class="w-10 h-10 bg-orange-100 rounded-xl
                        flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-orange-500"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
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

    {{-- QR Scanner --}}
    <div class="rounded-xl overflow-hidden border-2 border-dashed
                border-indigo-200 bg-slate-50">
        <div id="reader" class="w-full"></div>
    </div>

    <p class="text-xs text-slate-400 text-center mt-3">
        Pastikan kamera sudah diizinkan di browser kamu
    </p>

</div>

{{-- Result Notification --}}
<div id="result-box" class="hidden rounded-2xl p-4 mb-6 
                             flex items-center gap-3">
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    let scanned = false;

    function showResult(message, isSuccess) {
        const box = document.getElementById('result-box');
        box.classList.remove('hidden');

        if (isSuccess) {
            box.className = 'rounded-2xl p-4 mb-6 flex items-center gap-3 bg-green-50 border border-green-200';
            box.innerHTML = `
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-green-700 text-sm">${message}</p>
                </div>`;
        } else {
            box.className = 'rounded-2xl p-4 mb-6 flex items-center gap-3 bg-red-50 border border-red-200';
            box.innerHTML = `
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-red-700 text-sm">${message}</p>
                </div>`;
        }

        // Reload setelah 2 detik
        setTimeout(() => {
            window.location.reload();
        }, 2000);
    }

    function onScanSuccess(decodedText) {
        if (scanned) return;
        scanned = true;

        fetch('/process-scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: decodedText })
        })
        .then(res => res.json())
        .then(data => {
            const isSuccess = !data.error;
            showResult(data.message, isSuccess);
        })
        .catch(() => {
            showResult('Terjadi kesalahan. Coba lagi.', false);
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            rememberLastUsedCamera: true,
        },
        false
    );

    html5QrcodeScanner.render(onScanSuccess);
</script>
@endpush