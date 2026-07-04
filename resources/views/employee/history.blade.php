@extends('employee.layout')
@section('title', 'Riwayat Absensi')

@section('content')

{{-- Header Card --}}
<div class="bg-gradient-to-br from-indigo-600 to-indigo-500
            rounded-2xl p-6 text-white mb-6 shadow-lg">
    <p class="text-indigo-200 text-sm">
        {{ now()->format('l, d F Y') }}
    </p>
    <h2 class="text-xl font-bold mt-1">
        Riwayat Absensi
    </h2>
    <p class="text-indigo-200 text-sm mt-1">
        Rekap kehadiran kamu
    </p>
</div>

{{-- Stats Card --}}
@php
    $total = $attendances->count();
    $hadir = $attendances->where('status', 'Hadir')->count();
    $terlambat = $attendances->where('status', 'Terlambat')->count();
    $leaveCount = $attendances->whereIn('status', ['Izin', 'Sakit'])->count();
@endphp

<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    {{-- Total --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 text-center">
        <p class="text-2xl font-bold text-indigo-600">{{ $total }}</p>
        <p class="text-xs text-slate-400 mt-1">Total</p>
    </div>

    {{-- Hadir --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 text-center">
        <p class="text-2xl font-bold text-green-500">{{ $hadir }}</p>
        <p class="text-xs text-slate-400 mt-1">Hadir</p>
    </div>

    {{-- Terlambat --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 text-center">
        <p class="text-2xl font-bold text-red-500">{{ $terlambat }}</p>
        <p class="text-xs text-slate-400 mt-1">Terlambat</p>
    </div>

    {{-- Izin/Sakit --}}
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 text-center">
        <p class="text-2xl font-bold text-yellow-600">{{ $leaveCount }}</p>
        <p class="text-xs text-slate-400 mt-1">Izin/Sakit</p>
    </div>
</div>

{{-- List Riwayat --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-50">
        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">
            Semua Riwayat
        </p>
    </div>

    @forelse($attendances as $item)

        @php
            $isLeave = in_array($item->status, ['Izin','Sakit']);
            $isHadir = $item->status === 'Hadir';
            $isTerlambat = $item->status === 'Terlambat';
            $isSakit = $item->status === 'Sakit';
        @endphp

        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50 last:border-0">

            {{-- Left: icon + info --}}
            <div class="flex items-center gap-3">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                    {{ $isHadir ? 'bg-green-100' : ($isTerlambat ? 'bg-red-100' : ($isSakit ? 'bg-red-100' : 'bg-yellow-100')) }}">

                    @if($isHadir)
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-green-600"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    @elseif($isTerlambat)
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-red-500"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                    @elseif($isSakit)
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-red-500"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-yellow-600"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="12" y1="18" x2="12" y2="12"/>
                            <line x1="9" y1="15" x2="15" y2="15"/>
                        </svg>
                    @endif
                </div>

                {{-- Info --}}
                <div>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                    </p>

                    @if($isLeave)
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $item->status }} • Tidak masuk kerja
                        </p>
                    @else
                        <div class="flex items-center gap-2 mt-0.5">
                            {{-- Jam Masuk --}}
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-3 h-3 text-green-400"
                                     viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2">
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                    <path d="M5 21h14"/>
                                </svg>
                                <span class="text-xs text-slate-400">
                                    {{ $item->time_in ? \Carbon\Carbon::parse($item->time_in)->format('H:i') : '-' }}
                                </span>
                            </div>

                            <span class="text-slate-200">|</span>

                            {{-- Jam Pulang --}}
                            <div class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="w-3 h-3 text-red-400"
                                     viewBox="0 0 24 24" fill="none"
                                     stroke="currentColor" stroke-width="2">
                                    <polyline points="7 16 12 21 17 16"/>
                                    <line x1="12" y1="21" x2="12" y2="9"/>
                                    <path d="M5 3h14"/>
                                </svg>
                                <span class="text-xs text-slate-400">
                                    {{ $item->time_out ? \Carbon\Carbon::parse($item->time_out)->format('H:i') : 'Belum checkout' }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Badge Status --}}
            <span class="text-xs font-semibold px-3 py-1 rounded-full shrink-0
                {{ $isHadir
                    ? 'bg-green-50 text-green-600'
                    : ($isTerlambat
                        ? 'bg-red-50 text-red-500'
                        : ($isSakit
                            ? 'bg-red-50 text-red-500'
                            : 'bg-yellow-50 text-yellow-600')) }}">
                {{ $item->status }}
            </span>

        </div>
    @empty
        <div class="flex flex-col items-center justify-center py-12">
            <div class="w-14 h-14 bg-slate-100 rounded-2xl
                        flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-7 h-7 text-slate-400"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <p class="text-slate-500 font-medium text-sm">
                Belum ada riwayat absensi
            </p>
            <p class="text-slate-400 text-xs mt-1">
                Scan QR untuk mulai absen
            </p>
        </div>
    @endforelse

</div>

@endsection