<x-guest-layout>

    <div class="mb-8">
        <h1 class="font-display text-2xl font-semibold text-[#1F2430] tracking-tight">
            Masuk ke akun
        </h1>
        <p class="text-sm text-slate-500 mt-1.5">
            Gunakan email kamu untuk melanjutkan
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-[#1F2430] mb-1.5">
                Email
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="email@perusahaan.com"
                   class="w-full px-4 py-2.5 rounded-lg border border-[#E8E5DF] bg-[#FAF9F7]
                          placeholder:text-slate-400 text-sm text-[#1F2430]
                          focus:outline-none focus:ring-2 focus:ring-[#F5A524]/25
                          focus:border-[#F5A524] focus:bg-white transition"/>
            @error('email')
                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-[#1F2430] mb-1.5">
                Password
            </label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full px-4 py-2.5 rounded-lg border border-[#E8E5DF] bg-[#FAF9F7]
                          placeholder:text-slate-400 text-sm text-[#1F2430]
                          focus:outline-none focus:ring-2 focus:ring-[#F5A524]/25
                          focus:border-[#F5A524] focus:bg-white transition"/>
            @error('password')
                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember --}}
        <div class="flex items-center justify-between text-sm pt-1">
            <label class="flex items-center text-slate-600 cursor-pointer select-none">
                <input type="checkbox"
                       name="remember"
                       class="rounded border-slate-300 text-[#F5A524] w-4 h-4
                              focus:ring-[#F5A524]/40 focus:ring-2 focus:ring-offset-0 mr-2">
                Ingat saya
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-[#1F2430] font-medium hover:text-[#F5A524] transition">
                    Lupa password?
                </a>
            @endif
        </div>

        {{-- Button --}}
        <button type="submit"
                class="w-full bg-[#F5A524] hover:bg-[#F7B54D] active:bg-[#E39415]
                       text-[#1F2430] text-sm font-semibold py-2.5 rounded-lg
                       transition duration-150">
            Masuk
        </button>

    </form>

</x-guest-layout>