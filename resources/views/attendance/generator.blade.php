<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>QR Absensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-slate-900
             to-slate-800 min-h-screen
             flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-3xl
                p-10 text-center w-[460px]">

        {{-- Header --}}
        <div class="mb-6">
            <div class="flex justify-center mb-3">
                <div class="bg-indigo-100 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 text-indigo-600"
                         fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 
                                 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 
                                 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 
                                 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 
                                 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 
                                 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 
                                 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">
                Absensi QR Code
            </h1>
            <p class="text-slate-400 mt-1 text-sm">
                Arahkan kamera HP Anda ke QR Code di bawah ini
            </p>
        </div>

        {{-- QR Box --}}
        <div class="flex justify-center mb-6">
            <div class="p-4 bg-white rounded-2xl
                        border-2 border-slate-100 shadow-md">
                <object id="qr-object"
                        type="image/svg+xml"
                        data="{{ route('generate.qr') }}"
                        class="w-[220px] h-[220px]">
                </object>
            </div>
        </div>

        {{-- Countdown Bar --}}
        <div class="mb-4">
            <div class="w-full bg-slate-100 rounded-full h-1.5">
                <div id="progress-bar"
                     class="bg-indigo-500 h-1.5 rounded-full
                            transition-all duration-1000"
                     style="width: 100%">
                </div>
            </div>
        </div>

        {{-- Countdown Text --}}
        <div class="flex items-center justify-center gap-2 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-4 w-4 text-indigo-400"
                 fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 
                         0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-slate-500">
                QR diperbarui dalam
                <span id="timer"
                      class="font-bold text-indigo-600">10</span>
                detik
            </p>
        </div>

        {{-- Status Badge --}}
        <span id="status-badge"
              class="inline-block px-4 py-1 rounded-full
                     text-xs font-medium
                     bg-green-100 text-green-700">
            ● Sistem Aktif
        </span>

    </div>

<script>
    const totalTime = 10;
    let timeLeft = totalTime;

    function refreshQr() {
        const timestamp = new Date().getTime();
        const url = "{{ route('generate.qr') }}?t=" + timestamp;

        document.getElementById('qr-object').data = url;

        document.getElementById('status-badge').className =
            'inline-block px-4 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700';
        document.getElementById('status-badge').textContent = '● Sistem Aktif';

        timeLeft = totalTime;
    }

    setInterval(function () {
        timeLeft--;

        // Update countdown text
        document.getElementById('timer').textContent =
            timeLeft <= 0 ? '...' : timeLeft;

        // Update progress bar
        const percent = (timeLeft / totalTime) * 100;
        document.getElementById('progress-bar').style.width = percent + '%';

        if (timeLeft <= 0) {
            document.getElementById('status-badge').className =
                'inline-block px-4 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700';
            document.getElementById('status-badge').textContent = '⟳ Memperbarui...';
            refreshQr();
        }
    }, 1000);
</script>

</body>
</html>