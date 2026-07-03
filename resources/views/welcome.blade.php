<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{-- Ini bagian paling penting: Memanggil Tailwind lewat Vite --}}
  @vite('resources/css/app.css')
</head>
<body class="bg-slate-100 flex items-center justify-center h-screen">

  <div class="bg-white p-10 rounded-2xl shadow-2xl text-center border-t-4 border-blue-500">
    <h1 class="text-3xl font-bold text-blue-600 mb-4">
      Tailwind Berhasil! 🎉
    </h1>
    <p class="text-gray-600">
      Jika kamu melihat kotak ini dengan bayangan (shadow) dan tulisan biru, berarti setup kamu <strong class="text-green-500 underline">SUDAH BENER</strong>.
    </p>
    <button class="mt-6 px-6 py-2 bg-blue-500 text-white rounded-full hover:bg-blue-700 transition duration-300">
        Tombol Test
    </button>
  </div>

</body>
</html>