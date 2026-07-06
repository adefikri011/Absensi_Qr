<x-filament-widgets::widget class="fi-wi-top-performer">
    <x-filament::section
        heading="🏆 Top Kehadiran 7 Hari"
        description="Karyawan dengan kehadiran paling stabil dalam satu minggu terakhir"
    >
        @php
            $topEmployees = $this->getTopEmployees();
        @endphp

        <div class="space-y-3">
            @forelse($topEmployees as $item)
                @php
                    $name = $item->user->name ?? '-';
                    $initials = collect(preg_split('/\s+/', trim($name)) ?: [])
                        ->filter()
                        ->map(fn (string $part) => mb_substr($part, 0, 1))
                        ->take(2)
                        ->implode('');
                @endphp

                <div class="flex items-center justify-between rounded-2xl border border-slate-100 bg-slate-50/80 px-4 py-3 transition hover:-translate-y-0.5 hover:border-indigo-200 hover:bg-indigo-50/50 hover:shadow-sm">
                    <div class="flex min-w-0 items-center gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-amber-400 text-sm font-bold text-white shadow-sm">
                            {{ $initials !== '' ? $initials : 'K' }}
                        </div>

                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">
                                {{ $name }}
                            </p>
                            <p class="truncate text-xs text-slate-500">
                                {{ $item->user->position->name ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <span class="shrink-0 rounded-full bg-emerald-50 px-3 py-1 text-sm font-semibold text-emerald-700">
                        {{ $item->total }}x
                    </span>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-10 text-center">
                    <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm ring-1 ring-slate-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-slate-600">
                        Belum ada data
                    </p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>