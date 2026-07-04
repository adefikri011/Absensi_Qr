@extends('employee.layout')
@section('title', 'Pengajuan Izin')

@section('content')

{{-- Header --}}
<div class="bg-gradient-to-br from-indigo-600 to-indigo-500
            rounded-2xl p-6 text-white mb-6 shadow-lg">
    <p class="text-indigo-200 text-sm">
        {{ now()->format('l, d F Y') }}
    </p>
    <h2 class="text-xl font-bold mt-1">
        Pengajuan Izin / Sakit
    </h2>
    <p class="text-indigo-200 text-sm mt-1">
        Ajukan izin atau sakit di sini
    </p>
</div>

{{-- Success Message --}}
@if(session('success'))
    <div class="bg-green-50 border border-green-200
                rounded-2xl p-4 mb-6 flex items-center gap-3">
        <div class="w-8 h-8 bg-green-100 rounded-xl
                    flex items-center justify-center shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="w-4 h-4 text-green-600"
                 viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
        </div>
        <p class="text-sm font-medium text-green-700">
            {{ session('success') }}
        </p>
    </div>
@endif

{{-- Form --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">

    <form action="/employee/leave" method="POST"
          enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Tanggal --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Tanggal Izin
            </label>
            <input type="date"
                   name="date"
                   min="{{ now()->toDateString() }}"
                   value="{{ old('date') }}"
                   required
                   class="w-full px-4 py-3 rounded-xl border border-slate-200
                          focus:outline-none focus:ring-2 focus:ring-indigo-500
                          text-sm text-slate-800"/>
            @error('date')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tipe --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Jenis
            </label>
            <div class="grid grid-cols-2 gap-3">
                <label class="flex items-center gap-3 p-4 rounded-xl
                              border-2 cursor-pointer transition
                              border-slate-200 hover:border-indigo-400
                              has-[:checked]:border-indigo-500
                              has-[:checked]:bg-indigo-50">
                    <input type="radio" name="type"
                           value="izin" class="hidden"
                           {{ old('type') === 'izin' ? 'checked' : '' }}>
                    <div class="w-8 h-8 bg-indigo-100 rounded-lg
                                flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 text-indigo-600"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700">Izin</span>
                </label>

                <label class="flex items-center gap-3 p-4 rounded-xl
                              border-2 cursor-pointer transition
                              border-slate-200 hover:border-red-400
                              has-[:checked]:border-red-500
                              has-[:checked]:bg-red-50">
                    <input type="radio" name="type"
                           value="sakit" class="hidden"
                           {{ old('type') === 'sakit' ? 'checked' : '' }}>
                    <div class="w-8 h-8 bg-red-100 rounded-lg
                                flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4 text-red-500"
                             viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700">Sakit</span>
                </label>
            </div>
            @error('type')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alasan --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Alasan
            </label>
            <textarea name="reason"
                      rows="3"
                      required
                      placeholder="Tulis alasan izin kamu..."
                      class="w-full px-4 py-3 rounded-xl border border-slate-200
                             focus:outline-none focus:ring-2 focus:ring-indigo-500
                             text-sm text-slate-800 resize-none">{{ old('reason') }}</textarea>
            @error('reason')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Upload Surat --}}
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">
                Surat / Bukti
                <span class="text-slate-400 font-normal">(opsional)</span>
            </label>
            <label class="flex flex-col items-center justify-center
                          w-full h-32 border-2 border-dashed border-slate-200
                          rounded-xl cursor-pointer hover:border-indigo-400
                          hover:bg-indigo-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-8 h-8 text-slate-400 mb-2"
                     viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <polyline points="16 16 12 12 8 16"/>
                    <line x1="12" y1="12" x2="12" y2="21"/>
                    <path d="M20.39 18.39A5 5 0 0018 9h-1.26A8 8 0 103 16.3"/>
                </svg>
                <p class="text-xs text-slate-400">
                    Klik untuk upload foto/PDF
                </p>
                <input type="file" name="attachment"
                       accept="image/*,.pdf"
                       class="hidden"/>
            </label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full bg-indigo-600 hover:bg-indigo-700
                       text-white font-semibold py-3 rounded-xl
                       transition duration-200">
            Kirim Pengajuan
        </button>

    </form>

</div>

{{-- Riwayat Pengajuan --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

    <div class="px-5 py-4 border-b border-slate-50">
        <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">
            Riwayat Pengajuan
        </p>
    </div>

    @forelse(auth()->user()->leaveRequests()->latest()->get() as $leave)
        <div class="px-5 py-4 border-b border-slate-50 last:border-0">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-800">
                        {{ \Carbon\Carbon::parse($leave->date)->format('d M Y') }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ ucfirst($leave->type) }} •
                        {{ $leave->reason }}
                    </p>
                    @if($leave->note)
                        <p class="text-xs text-slate-500 mt-1 italic">
                            Catatan admin: {{ $leave->note }}
                        </p>
                    @endif
                </div>
                <span class="text-xs font-semibold px-3 py-1 rounded-full shrink-0
                    {{ $leave->status === 'approved'
                       ? 'bg-green-50 text-green-600'
                       : ($leave->status === 'rejected'
                          ? 'bg-red-50 text-red-500'
                          : 'bg-yellow-50 text-yellow-600') }}">
                    {{ ucfirst($leave->status) }}
                </span>
            </div>
        </div>
    @empty
        <div class="flex flex-col items-center justify-center py-10">
            <p class="text-slate-400 text-sm">
                Belum ada pengajuan
            </p>
        </div>
    @endforelse

</div>

@endsection