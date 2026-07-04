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

    {{-- User Info + Logout --}}
    <div class="flex items-center gap-3">

        {{-- Avatar + Name --}}
        <div class="flex items-center gap-2">

            {{-- Avatar --}}
            <div class="w-8 h-8 rounded-full overflow-hidden
                        flex items-center justify-center shrink-0">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-indigo-100
                                flex items-center justify-center">
                        <span class="text-indigo-600 font-semibold text-xs">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Name & Position --}}
            <div class="text-right hidden sm:block">
                <p class="text-xs font-medium text-slate-800">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-slate-400">
                    {{ auth()->user()->position->name ?? 'Karyawan' }}
                </p>
            </div>

        </div>

        {{-- Divider --}}
        <div class="w-px h-6 bg-slate-200 hidden sm:block"></div>

        {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-1.5 text-xs text-slate-500
                           hover:text-red-500 transition-colors duration-200
                           group">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 group-hover:text-red-500 transition"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                <span class="hidden sm:block">Keluar</span>
            </button>
        </form>

    </div>

</nav>