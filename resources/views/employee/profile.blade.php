@extends('employee.layout')
@section('title', 'Profil')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-br from-indigo-600 to-indigo-500
            rounded-2xl p-6 text-white mb-6 shadow-lg">
    <h2 class="text-xl font-bold">
        Profil Saya
    </h2>
    <p class="text-indigo-200 text-sm mt-1">
        Kelola informasi akun kamu
    </p>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200
                rounded-xl p-4 mb-6 text-green-700 text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">

    <form method="POST"
          action="{{ route('employee.profile.update') }}"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf

        {{-- Avatar --}}
        <div class="flex flex-col items-center gap-3">
            <div class="w-24 h-24 rounded-full overflow-hidden
                        border-4 border-indigo-100 shadow-sm">
                @if(auth()->user()->avatar)
                    <img src="{{ asset('storage/'.auth()->user()->avatar) }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-indigo-100
                                flex items-center justify-center">
                        <span class="text-2xl font-bold text-indigo-600">
                            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                        </span>
                    </div>
                @endif
            </div>

            <input type="file" name="avatar"
                   class="text-xs text-slate-500">
        </div>

        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Nama
            </label>
            <input type="text"
                   name="name"
                   value="{{ auth()->user()->name }}"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200
                          focus:ring-2 focus:ring-indigo-500">
        </div>

        {{-- Email (readonly) --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Email
            </label>
            <input type="text"
                   value="{{ auth()->user()->email }}"
                   disabled
                   class="w-full px-4 py-3 rounded-xl border border-slate-200
                          bg-slate-50 text-slate-400">
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Password Baru
            </label>
            <input type="password"
                   name="password"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200">
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">
                Konfirmasi Password
            </label>
            <input type="password"
                   name="password_confirmation"
                   class="w-full px-4 py-3 rounded-xl border border-slate-200">
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700
                       text-white py-3 rounded-xl font-semibold">
            Simpan Perubahan
        </button>

    </form>

</div>

@endsection