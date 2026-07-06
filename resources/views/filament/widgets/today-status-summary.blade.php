<x-filament::widget>
    <x-filament::card>

        @php
            $d = $this->getData();
            $total = $d['total'];
            $hadirPct = $total ? round(($d['hadir'] / $total) * 100) : 0;
            $terlambatPct = $total ? round(($d['terlambat'] / $total) * 100) : 0;
            $izinPct = $total ? round(($d['izin'] / $total) * 100) : 0;
            $sakitPct = $total ? round(($d['sakit'] / $total) * 100) : 0;
        @endphp

        {{-- Title --}}
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-sm font-semibold text-slate-700">
                Kehadiran per Status — Hari Ini
            </h2>
            <span class="text-xs text-slate-400">
                {{ now()->format('d M Y') }}
            </span>
        </div>

        {{-- Number Summary --}}
        <div class="grid grid-cols-4 gap-4 mb-5">

            {{-- Hadir --}}
            <div class="border-l-4 border-green-500 pl-3">
                <p class="text-2xl font-bold text-slate-800">
                    {{ $d['hadir'] }}
                </p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">
                    Hadir
                </p>
            </div>

            {{-- Terlambat --}}
            <div class="border-l-4 border-red-500 pl-3">
                <p class="text-2xl font-bold text-slate-800">
                    {{ $d['terlambat'] }}
                </p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">
                    Terlambat
                </p>
            </div>

            {{-- Izin --}}
            <div class="border-l-4 border-yellow-400 pl-3">
                <p class="text-2xl font-bold text-slate-800">
                    {{ $d['izin'] }}
                </p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">
                    Izin
                </p>
            </div>

            {{-- Sakit --}}
            <div class="border-l-4 border-orange-400 pl-3">
                <p class="text-2xl font-bold text-slate-800">
                    {{ $d['sakit'] }}
                </p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">
                    Sakit
                </p>
            </div>

        </div>

        {{-- Stacked Bar --}}
        <div class="flex w-full h-3 rounded-full overflow-hidden gap-0.5">

            @if($hadirPct > 0)
                <div class="bg-green-500 h-full transition-all duration-500"
                     style="width: {{ $hadirPct }}%">
                </div>
            @endif

            @if($terlambatPct > 0)
                <div class="bg-red-500 h-full transition-all duration-500"
                     style="width: {{ $terlambatPct }}%">
                </div>
            @endif

            @if($izinPct > 0)
                <div class="bg-yellow-400 h-full transition-all duration-500"
                     style="width: {{ $izinPct }}%">
                </div>
            @endif

            @if($sakitPct > 0)
                <div class="bg-orange-400 h-full transition-all duration-500"
                     style="width: {{ $sakitPct }}%">
                </div>
            @endif

            {{-- Kalau semua 0 → tampil abu --}}
            @if($total <= 1 && $d['hadir'] == 0)
                <div class="bg-slate-200 h-full w-full rounded-full"></div>
            @endif

        </div>

        {{-- Legend --}}
        <div class="flex items-center gap-4 mt-3">
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-sm bg-green-500"></div>
                <span class="text-xs text-slate-500">Hadir {{ $hadirPct }}%</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-sm bg-red-500"></div>
                <span class="text-xs text-slate-500">Terlambat {{ $terlambatPct }}%</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-sm bg-yellow-400"></div>
                <span class="text-xs text-slate-500">Izin {{ $izinPct }}%</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded-sm bg-orange-400"></div>
                <span class="text-xs text-slate-500">Sakit {{ $sakitPct }}%</span>
            </div>
        </div>

    </x-filament::card>
</x-filament::widget>