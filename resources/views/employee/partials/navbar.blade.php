<nav class="bg-white border-b border-slate-100 px-6 py-4 
            flex items-center justify-between sticky top-0 z-50 shadow-sm">

    {{-- Logo & Brand --}}
    <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-indigo-600 rounded-lg 
                    flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-4 h-4 text-white" 
                 viewBox="0 0 24 24" fill="none" 
                 stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>
        </div>
        <span class="font-semibold text-slate-800 text-sm">
            Sistem Absensi
        </span>
    </div>

    {{-- User Info --}}
    <div class="flex items-center gap-2">
        <div class="w-8 h-8 bg-indigo-100 rounded-full 
                    flex items-center justify-center">
            <span class="text-indigo-600 font-semibold text-xs">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
        </div>
        <div class="text-right hidden sm:block">
            <p class="text-xs font-medium text-slate-800">
                {{ auth()->user()->name }}
            </p>
            <p class="text-xs text-slate-400">
                {{ auth()->user()->position->name ?? 'Karyawan' }}
            </p>
        </div>
    </div>

</nav>