<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center h-screen">
    <div class="text-center p-6 bg-white rounded-2xl shadow-xl max-w-md border border-slate-100">
        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-12 h-12">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
            </svg>
        </div>
        <h1 class="text-6xl font-black text-slate-800 mb-2">403</h1>
        <p class="text-xl font-bold text-slate-700 mb-4">Akses Ditolak</p>
        <p class="text-slate-500 mb-6">
            {{-- Anda tidak memiliki hak akses untuk membuka halaman atau melakukan aksi ini. --}}
            {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka halaman atau melakukan aksi ini.' }}
        </p>
        <a href="/dashboard" class="inline-block px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition shadow-lg shadow-indigo-100">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>