@php
    $pendingLeave = auth()->user()
        ->leaveRequests()
        ->where('status', 'pending')
        ->count();
@endphp

<nav class="fixed bottom-0 left-0 right-0 z-50">

    <div class="bg-white/90 dark:bg-slate-900/90 backdrop-blur-md
                border-t border-slate-200 dark:border-slate-800
                shadow-[0_-6px_30px_rgba(0,0,0,0.08)]">

        {{-- Floating Scan Button --}}
        <div class="absolute left-1/2 -translate-x-1/2 -top-7 z-20">

            <a href="/scan"
               class="relative flex items-center justify-center
                      w-16 h-16 rounded-full
                      bg-gradient-to-br from-indigo-500 to-indigo-700
                      shadow-xl shadow-indigo-300/40
                      ring-4 ring-white dark:ring-slate-900
                      transition-transform duration-150
                      hover:scale-105 active:scale-95">

                {{-- Pulse effect --}}
                <span class="absolute inset-0 rounded-full
                             bg-indigo-400 opacity-20 animate-ping"></span>

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-7 h-7 text-white relative z-10"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"/>
                    <rect x="14" y="3" width="7" height="7"/>
                    <rect x="3" y="14" width="7" height="7"/>
                </svg>
            </a>

            <span class="block text-center text-[10px] font-semibold
                         text-indigo-600 mt-1">
                Scan
            </span>
        </div>


        {{-- Navigation Grid --}}
        <div class="grid grid-cols-5 items-end
                    max-w-md mx-auto
                    px-3 pt-3 pb-3
                    pb-[env(safe-area-inset-bottom,12px)]">

            {{-- HOME --}}
            <a href="/employee/dashboard"
               class="relative flex flex-col items-center gap-1 py-2 text-xs
               {{ request()->is('employee/dashboard')
                    ? 'text-indigo-600'
                    : 'text-slate-400 hover:text-indigo-500' }}
               transition-all duration-150 active:scale-95">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path d="M3 10.5 12 3l9 7.5"/>
                    <path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"/>
                </svg>

                <span>Home</span>

                @if(request()->is('employee/dashboard'))
                    <span class="absolute -bottom-1 w-5 h-1 bg-indigo-600 rounded-full"></span>
                @endif
            </a>


            {{-- RIWAYAT --}}
            <a href="/employee/history"
               class="relative flex flex-col items-center gap-1 py-2 text-xs
               {{ request()->is('employee/history')
                    ? 'text-indigo-600'
                    : 'text-slate-400 hover:text-indigo-500' }}
               transition-all duration-150 active:scale-95">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <circle cx="12" cy="12" r="8.5"/>
                    <path d="M12 7.5V12l3 2"/>
                </svg>

                <span>Riwayat</span>

                @if(request()->is('employee/history'))
                    <span class="absolute -bottom-1 w-5 h-1 bg-indigo-600 rounded-full"></span>
                @endif
            </a>


            {{-- GAP --}}
            <div></div>


            {{-- IZIN --}}
            <a href="/employee/leave"
               class="relative flex flex-col items-center gap-1 py-2 text-xs
               {{ request()->is('employee/leave')
                    ? 'text-indigo-600'
                    : 'text-slate-400 hover:text-indigo-500' }}
               transition-all duration-150 active:scale-95">

                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-5 h-5"
                         viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <rect x="4" y="4.5" width="16" height="15" rx="2.5"/>
                        <line x1="12" y1="18" x2="12" y2="12"/>
                    </svg>

                    @if($pendingLeave > 0)
                        <span class="absolute -top-1 -right-2
                                     bg-red-500 text-white text-[9px]
                                     w-4 h-4 flex items-center justify-center
                                     rounded-full">
                            {{ $pendingLeave }}
                        </span>
                    @endif
                </div>

                <span>Izin</span>

                @if(request()->is('employee/leave'))
                    <span class="absolute -bottom-1 w-5 h-1 bg-indigo-600 rounded-full"></span>
                @endif
            </a>


            {{-- PROFIL --}}
            <a href="/employee/profile"
               class="relative flex flex-col items-center gap-1 py-2 text-xs
               {{ request()->is('employee/profile')
                    ? 'text-indigo-600'
                    : 'text-slate-400 hover:text-indigo-500' }}
               transition-all duration-150 active:scale-95">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <circle cx="12" cy="8" r="3.5"/>
                    <path d="M5.5 20a6.5 6.5 0 0 1 13 0"/>
                </svg>

                <span>Profil</span>

                @if(request()->is('employee/profile'))
                    <span class="absolute -bottom-1 w-5 h-1 bg-indigo-600 rounded-full"></span>
                @endif
            </a>

        </div>

    </div>
</nav>