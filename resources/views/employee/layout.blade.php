<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') — Absensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-50 min-h-screen flex flex-col">

    @include('employee.partials.navbar')

    <main class="flex-1 px-4 py-6 pb-28 max-w-lg mx-auto w-full">
        @yield('content')
    </main>

    @include('employee.partials.bottomnav')
    @stack('scripts')


</body>

</html>
