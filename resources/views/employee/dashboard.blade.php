<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Karyawan</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-2xl shadow-sm border border-slate-100 text-center">

        <h1 class="text-xl font-semibold text-slate-800 mb-2">
            Selamat Datang, {{ auth()->user()->name }}
        </h1>

        <p class="text-slate-500 text-sm mb-6">
            Silakan lakukan absensi hari ini.
        </p>

        <a href="/scan"
           class="inline-block bg-indigo-600 hover:bg-indigo-700
                  text-white px-6 py-3 rounded-xl transition">
            Scan QR Absensi
        </a>

    </div>

</body>
</html>