<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan QR</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col">

    <div class="p-6 bg-white shadow-sm border-b">
        <h1 class="text-lg font-semibold text-slate-800">
            Scan QR Absensi
        </h1>
        <p class="text-sm text-slate-500">
            Arahkan kamera ke QR di monitor
        </p>
    </div>

    <div class="flex-1 flex items-center justify-center p-6">
        <div class="bg-white rounded-2xl shadow-sm p-4 w-full max-w-md">
            <div id="reader" class="rounded-xl overflow-hidden"></div>
        </div>
    </div>

<script>
    function onScanSuccess(decodedText) {

        fetch('/process-scan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ token: decodedText })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
        });
    }

    let html5QrcodeScanner = new Html5QrcodeScanner(
        "reader",
        { fps: 10, qrbox: 250 },
        false
    );

    html5QrcodeScanner.render(onScanSuccess);
</script>

</body>
</html>