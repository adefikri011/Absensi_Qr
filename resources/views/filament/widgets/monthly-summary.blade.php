<x-filament::widget>
    <x-filament::card class="!p-0 overflow-hidden">
        @php
            $d = $this->getData();

            $colorMap = [
                'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'ring' => 'ring-emerald-100'],
                'rose'    => ['bg' => 'bg-rose-50',    'text' => 'text-rose-600',    'ring' => 'ring-rose-100'],
                'amber'   => ['bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'ring' => 'ring-amber-100'],
                'orange'  => ['bg' => 'bg-orange-50',  'text' => 'text-orange-600',  'ring' => 'ring-orange-100'],
            ];
        @endphp

        {{-- Header --}}
        <div class="px-5 pt-5 pb-4 flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">
                    Ringkasan Bulan Ini
                </h2>
                <p class="text-xs text-slate-400 mt-0.5">
                    {{ now()->translatedFormat('F Y') }}
                </p>
            </div>

            <div class="flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1.5">
                <x-heroicon-o-chart-bar class="w-3.5 h-3.5 text-indigo-500" />
                <span class="text-xs font-semibold text-indigo-600">
                    {{ $d['attendanceRate'] }}%
                </span>
            </div>
        </div>

        {{-- Rows --}}
        <div class="px-5 pb-4 space-y-1.5">
            @foreach ($d['rows'] as $row)
                @php $c = $colorMap[$row['color']]; @endphp

                <div class="flex items-center justify-between rounded-xl px-3 py-2.5 transition-colors hover:bg-slate-50">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-lg {{ $c['bg'] }} ring-1 {{ $c['ring'] }}">
                            <x-dynamic-component :component="$row['icon']" class="w-4 h-4 {{ $c['text'] }}" />
                        </span>
                        <span class="text-sm font-medium text-slate-600">
                            {{ $row['label'] }}
                        </span>
                    </div>

                    <span class="text-sm font-bold text-slate-900 tabular-nums">
                        {{ $row['value'] }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Attendance rate bar --}}
        <div class="px-5 pb-5 pt-1 border-t border-slate-100">
            <div class="flex items-center justify-between mb-2 pt-4">
                <p class="text-xs font-medium text-slate-400">
                    Attendance Rate
                </p>
                <p class="text-xs font-bold text-slate-900">
                    {{ $d['attendanceRate'] }}%
                </p>
            </div>

            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div
                    class="h-full rounded-full bg-gradient-to-r from-indigo-400 to-indigo-600 transition-all duration-500"
                    style="width: {{ $d['attendanceRate'] }}%"
                ></div>
            </div>
        </div>
    </x-filament::card>
</x-filament::widget>