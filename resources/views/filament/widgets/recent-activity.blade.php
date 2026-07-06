<x-filament::widget>
    <x-filament::card class="!p-0 overflow-hidden">

        {{-- Header --}}
        <div class="px-5 pt-5 pb-4 flex items-center justify-between border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-indigo-50 ring-1 ring-indigo-100">
                    <x-heroicon-o-clock class="w-4 h-4 text-indigo-500" />
                </span>
                <h2 class="text-sm font-semibold text-slate-900">
                    Aktivitas Terbaru
                </h2>
            </div>

            <span class="text-xs text-slate-400">
                5 terbaru
            </span>
        </div>

        @php
            $activities = $this->getActivities();

            // Pakai kode warna asli (hex) + inline style, BUKAN class Tailwind,
            // supaya tidak mungkin ke-purge saat build produksi.
            $statusMap = [
                'Hadir' => [
                    'solid' => '#10b981',
                    'soft'  => '#ecfdf5',
                    'ring'  => '#a7f3d0',
                    'icon'  => 'heroicon-o-check-circle',
                ],
                'Terlambat' => [
                    'solid' => '#f43f5e',
                    'soft'  => '#fff1f2',
                    'ring'  => '#fecdd3',
                    'icon'  => 'heroicon-o-clock',
                ],
                'Izin' => [
                    'solid' => '#f59e0b',
                    'soft'  => '#fffbeb',
                    'ring'  => '#fde68a',
                    'icon'  => 'heroicon-o-document-text',
                ],
                'Sakit' => [
                    'solid' => '#fb923c',
                    'soft'  => '#fff7ed',
                    'ring'  => '#fed7aa',
                    'icon'  => 'heroicon-o-heart',
                ],
            ];

            $default = [
                'solid' => '#94a3b8',
                'soft'  => '#f8fafc',
                'ring'  => '#e2e8f0',
                'icon'  => 'heroicon-o-user',
            ];
        @endphp

        {{-- List --}}
        <div class="px-3 py-2">

            @forelse ($activities as $item)
                @php
                    $style = $statusMap[$item->status] ?? $default;
                    $initial = strtoupper(substr($item->user->name ?? '?', 0, 1));
                @endphp

                <div class="flex items-center gap-4 px-2 py-3 rounded-xl transition-colors hover:bg-slate-50">

                    {{-- Avatar --}}
                    <span
                        class="flex items-center justify-center w-10 h-10 shrink-0 rounded-full text-white text-sm font-semibold"
                        style="background-color: {{ $style['solid'] }};"
                    >
                        {{ $initial }}
                    </span>

                    {{-- Nama + badge status --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">
                            {{ $item->user->name ?? 'Tanpa Nama' }}
                        </p>

                        <span
                            class="inline-flex items-center gap-1 mt-1 rounded-full px-2 py-0.5"
                            style="background-color: {{ $style['soft'] }}; border: 1px solid {{ $style['ring'] }};"
                        >
                            <x-dynamic-component :component="$style['icon']" class="w-3 h-3" style="color: {{ $style['solid'] }};" />
                            <span class="text-[11px] font-medium" style="color: {{ $style['solid'] }};">
                                {{ $item->status }}
                            </span>
                        </span>
                    </div>

                    {{-- Tanggal & jam --}}
                    <div class="text-right shrink-0 w-20">
                        <p class="text-xs text-slate-400">
                            {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M') }}
                        </p>
                        <p class="text-sm font-bold text-slate-900 tabular-nums">
                            {{ $item->time_out
                                ? \Carbon\Carbon::parse($item->time_out)->format('H:i')
                                : \Carbon\Carbon::parse($item->time_in)->format('H:i') }}
                        </p>
                    </div>

                </div>

                @if (!$loop->last)
                    <div class="border-b border-slate-50 mx-2"></div>
                @endif

            @empty
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <x-heroicon-o-inbox class="w-8 h-8 text-slate-300 mb-2" />
                    <p class="text-sm text-slate-400">
                        Belum ada aktivitas.
                    </p>
                </div>
            @endforelse

        </div>
    </x-filament::card>
</x-filament::widget>