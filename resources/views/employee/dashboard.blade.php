@extends('employee.layout')
@section('title', 'Dashboard')

@section('content')

    {{-- Greeting Card --}}
    <div class="bg-gradient-to-br from-indigo-600 to-indigo-500 
            rounded-2xl p-6 text-white mb-6 shadow-lg">
        <p class="text-indigo-200 text-sm">
            {{ now()->format('l, d F Y') }}
        </p>
        <h2 class="text-xl font-bold mt-1">
            Halo, {{ auth()->user()->name }} 👋
        </h2>
        <p class="text-indigo-200 text-sm mt-1">
            {{ auth()->user()->position->name ?? 'Karyawan' }}
        </p>
    </div>

    {{-- Status Hari Ini --}}
    @php
        $today = \App\Models\Attendance::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        $isLeave = $today && in_array($today->status, ['Izin', 'Sakit']);
        $isPresent = $today && !$isLeave;
    @endphp

    <div class="bg-white rounded-2xl p-5 mb-6 shadow-sm border border-slate-100">
        <p class="text-xs text-slate-400 font-medium mb-3 uppercase tracking-wide">
            Status Hari Ini
        </p>

        {{-- BELUM ADA ABSENSI SAMA SEKALI --}}
        @if (!$today)
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-orange-100 rounded-xl
                        flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-500" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-slate-800">Belum Absen</p>
                    <p class="text-sm text-slate-400">
                        Silakan scan QR untuk absen
                    </p>
                </div>
            </div>

            {{-- IZIN ATAU SAKIT --}}
        @elseif($isLeave)
            <div class="flex items-center gap-4">

                {{-- Icon --}}
                <div
                    class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0
                {{ $today->status === 'Sakit' ? 'bg-red-100' : 'bg-yellow-100' }}">
                    @if ($today->status === 'Sakit')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-yellow-500" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="12" y1="18" x2="12" y2="12" />
                            <line x1="9" y1="15" x2="15" y2="15" />
                        </svg>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="font-semibold text-slate-800">
                            {{ $today->status === 'Sakit' ? 'Sakit' : 'Izin' }}
                        </p>
                        <span
                            class="px-2 py-0.5 rounded-full text-xs font-semibold
                        {{ $today->status === 'Sakit' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600' }}">
                            Disetujui
                        </span>
                    </div>
                    <p class="text-sm text-slate-400">
                        Kamu sedang
                        {{ $today->status === 'Sakit' ? 'sakit' : 'izin' }}
                        hari ini. Istirahat yang baik ya!
                    </p>
                </div>

            </div>

            {{-- HADIR ATAU TERLAMBAT --}}
        @else
            <div class="space-y-3">

                {{-- Jam --}}
                <div class="grid grid-cols-2 gap-3">

                    {{-- Jam Masuk --}}
                    <div class="bg-green-50 rounded-xl p-3 border border-green-100">
                        <div class="flex items-center gap-1 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-green-500" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                                <path d="M5 21h14" />
                            </svg>
                            <p class="text-xs text-green-600 font-medium">
                                Jam Masuk
                            </p>
                        </div>
                        <p class="text-lg font-bold text-slate-800">
                            {{ \Carbon\Carbon::parse($today->time_in)->format('H:i') }}
                        </p>
                    </div>

                    {{-- Jam Pulang --}}
                    <div
                        class="rounded-xl p-3 border
                    {{ $today->time_out ? 'bg-blue-50 border-blue-100' : 'bg-slate-50 border-slate-100' }}">
                        <div class="flex items-center gap-1 mb-1">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-3 h-3
                             {{ $today->time_out ? 'text-blue-500' : 'text-slate-400' }}"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="7 16 12 21 17 16" />
                                <line x1="12" y1="21" x2="12" y2="9" />
                                <path d="M5 3h14" />
                            </svg>
                            <p
                                class="text-xs font-medium
                            {{ $today->time_out ? 'text-blue-600' : 'text-slate-400' }}">
                                Jam Pulang
                            </p>
                        </div>
                        @if ($today->time_out)
                            <p class="text-lg font-bold text-slate-800">
                                {{ \Carbon\Carbon::parse($today->time_out)->format('H:i') }}
                            </p>
                        @else
                            <p class="text-sm text-slate-400 font-medium">
                                Belum checkout
                            </p>
                        @endif
                    </div>

                </div>

                {{-- Status Badge --}}
                <div class="flex items-center justify-between">
                    <p class="text-xs text-slate-400">
                        Status kehadiran hari ini
                    </p>
                    <span
                        class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $today->status === 'Hadir' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $today->status }}
                    </span>
                </div>

            </div>
        @endif

    </div>

    {{-- Quick Action --}}
    <div class="grid grid-cols-2 gap-4 mb-6">

        <a href="/scan"
            class="bg-indigo-600 hover:bg-indigo-700 transition
              text-white p-5 rounded-2xl shadow-md
              flex flex-col items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <rect x="3" y="3" width="7" height="7" />
                <rect x="14" y="3" width="7" height="7" />
                <rect x="3" y="14" width="7" height="7" />
                <path d="M14 14h3v3h-3zM17 17h3v3h-3zM14 20h3" />
            </svg>
            <span class="text-sm font-semibold">Scan QR</span>
        </a>

        <a href="/employee/history"
            class="bg-white hover:bg-slate-50 transition
              text-slate-700 p-5 rounded-2xl shadow-sm
              border border-slate-100
              flex flex-col items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-400" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
            </svg>
            <span class="text-sm font-semibold">Riwayat</span>
        </a>

    </div>

    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">

        <div class="flex items-center justify-between mb-4">
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">
                Absensi Terakhir
            </p>

            <a href="/employee/history" class="text-xs text-indigo-600 font-medium hover:underline">
                Lihat semua
            </a>
        </div>

        @php
            $recent = \App\Models\Attendance::where('user_id', auth()->id())
                ->latest()
                ->take(3)
                ->get();
        @endphp

        @forelse($recent as $item)
            @php
                $isLeave = in_array($item->status, ['Izin', 'Sakit']);
                $isLate = $item->status === 'Terlambat';
                $isPresent = $item->status === 'Hadir';
            @endphp

            <div class="flex items-center justify-between py-4
                    border-b border-slate-50 last:border-0">

                {{-- Left Side --}}
                <div class="flex items-center gap-3">

                    {{-- ICON --}}
                    <div
                        class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                    {{ $isPresent
                        ? 'bg-green-100'
                        : ($isLate
                            ? 'bg-red-100'
                            : ($item->status === 'Sakit'
                                ? 'bg-red-100'
                                : 'bg-yellow-100')) }}">

                        {{-- HADIR --}}
                        @if ($isPresent)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>

                            {{-- TERLAMBAT --}}
                        @elseif($isLate)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>

                            {{-- SAKIT --}}
                        @elseif($item->status === 'Sakit')
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                            </svg>

                            {{-- IZIN --}}
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="12" y1="18" x2="12" y2="12" />
                                <line x1="9" y1="15" x2="15" y2="15" />
                            </svg>
                        @endif
                    </div>

                    {{-- DATE & DETAIL --}}
                    <div>
                        <p class="text-sm font-semibold text-slate-800">
                            {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                        </p>

                        {{-- Detail Jam atau Keterangan --}}
                        @if ($isLeave)
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $item->status }} (Tidak masuk kerja)
                            </p>
                        @else
                            <p class="text-xs text-slate-400 mt-0.5">
                                {{ $item->time_in ? \Carbon\Carbon::parse($item->time_in)->format('H:i') : '-' }}
                                —
                                {{ $item->time_out ? \Carbon\Carbon::parse($item->time_out)->format('H:i') : 'Belum checkout' }}
                            </p>
                        @endif
                    </div>

                </div>

                {{-- STATUS BADGE --}}
                <span
                    class="text-xs font-semibold px-3 py-1 rounded-full shrink-0
                {{ $isPresent
                    ? 'bg-green-50 text-green-600'
                    : ($isLate
                        ? 'bg-red-50 text-red-500'
                        : ($item->status === 'Sakit'
                            ? 'bg-red-50 text-red-500'
                            : 'bg-yellow-50 text-yellow-600')) }}">
                    {{ $item->status }}
                </span>

            </div>

        @empty

            <div class="flex flex-col items-center justify-center py-10">
                <div
                    class="w-12 h-12 bg-slate-100 rounded-2xl
                        flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </div>
                <p class="text-sm text-slate-500 font-medium">
                    Belum ada riwayat absensi
                </p>
            </div>
        @endforelse

    </div>

@endsection
