<nav class="fixed bottom-0 left-0 right-0 bg-white 
            border-t border-slate-100 shadow-lg z-50">
    <div class="flex justify-around items-center py-2">

        {{-- Dashboard --}}
        <a href="/employee/dashboard"
            class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl
                  {{ request()->is('employee/dashboard') ? 'text-indigo-600' : 'text-slate-400' }}
                  hover:text-indigo-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            <span class="text-xs font-medium">Home</span>
        </a>

        {{-- Scan QR --}}
        <a href="/scan" class="flex flex-col items-center gap-1 px-4 py-2">
            <div
                class="w-12 h-12 bg-indigo-600 rounded-2xl 
                        flex items-center justify-center shadow-lg
                        hover:bg-indigo-700 transition -mt-5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                    <path d="M14 14h3v3h-3zM17 17h3v3h-3zM14 20h3" />
                </svg>
            </div>
            <span class="text-xs font-medium text-indigo-600">Scan</span>
        </a>

        {{-- Riwayat --}}
        <a href="/employee/history"
            class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl
                  {{ request()->is('employee/history') ? 'text-indigo-600' : 'text-slate-400' }}
                  hover:text-indigo-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
                <polyline points="10 9 9 9 8 9" />
            </svg>
            <span class="text-xs font-medium">Riwayat</span>
        </a>

        {{-- Izin --}}
        <a href="/employee/leave"
            class="flex flex-col items-center gap-1 px-4 py-2 rounded-xl
          {{ request()->is('employee/leave') ? 'text-indigo-600' : 'text-slate-400' }}
          hover:text-indigo-600 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="12" y1="18" x2="12" y2="12" />
                <line x1="9" y1="15" x2="15" y2="15" />
            </svg>
            <span class="text-xs font-medium">Izin</span>
        </a>

    </div>
</nav>
