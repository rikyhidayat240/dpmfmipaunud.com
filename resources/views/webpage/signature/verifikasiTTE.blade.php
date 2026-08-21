<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="data:,">
    @production
        @php
            $manifestPath = public_path('build/manifest.json');
            $manifest = json_decode(file_get_contents($manifestPath), true);
        @endphp
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
        <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endproduction
    <title>Verifikasi Tanda Tangan Elektronik</title>
</head>
<body class="bg-zinc-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-sm">
        <div class="bg-white rounded-lg shadow-md px-4 py-6 space-y-2">
            <!-- Logo -->
            <div class="flex justify-center">
                <img src="{{ asset('images/LOGO DPM.png') }}" alt="DPM FMIPA UNUD" class="size-16">
            </div>

            <!-- Header -->
            <div class="text-center text-blue-600 font-bold">
                <a href="{{ env('APP_URL') }}">
                    <h1>
                        <span class="text-black">Dokumen </span>
                        <span>Surat TTE</span>
                    </h1>
                    <h1>DPM FMIPA UNUD</h1>
                </a>
            </div>

            <!-- Status Verifikasi -->
            <div class="flex items-center justify-center gap-1 pb-2">
                <svg class="size-5 text-green-700" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-green-700 font-normal">Verifikasi Tanda Tangan Digital</span>
            </div>

            <!-- Data Surat -->
            <div class="space-y-2">
                <!-- Lembaga Pemohon -->
                <div>
                    <label class="block text-zinc-800 font-medium text-sm mb-1">Lembaga Pemohon:</label>
                    <p class="text-green-700 font-normal text-sm">
                        {{ $signature->lembaga->name . ' Universitas Udayana' ?? 'N/A' }}</p>
                </div>

                <!-- Nomor Surat -->
                <div>
                    <label class="block text-zinc-800 font-medium text-sm mb-1">Nomor Surat:</label>
                    <p class="text-green-700 font-normal text-sm">{{ $signature->nomor }}</p>
                </div>

                <!-- Tanggal Ditandatangani -->
                <div>
                    <label class="block text-zinc-800 font-medium text-sm mb-1">Tanggal Ditandatangani:</label>
                    <p class="text-green-700 font-normal text-sm">
                        {{ $signature->accepted_at ?? 'Belum ditandatangani' }}</p>
                </div>

                <!-- Ditandatangani oleh -->
                <div>
                    <label class="block text-zinc-800 font-medium text-sm mb-1">Ditandatangani oleh:</label>
                    <input type="text" value="{{ $signature->user->name ?? 'N/A' }}" readonly disabled
                        class="rounded px-3 py-2 w-full border border-zinc-300 bg-zinc-50 text-zinc-600/50 text-sm font-semibold" />
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-zinc-800 font-medium text-sm mb-1">Jabatan:</label>
                    <input type="text" value="{{ ($signature->user->specifiedRole . ' DPM FMIPA UNUD ' . now()->year) ?? 'N/A' }}" readonly disabled
                        class="rounded px-3 py-2 w-full border border-zinc-300 bg-zinc-50 text-zinc-600/50 text-sm font-semibold" />
                </div>

                <!-- Keperluan Tanda Tangan -->
                <div>
                    <label class="block text-zinc-800 font-medium text-sm mb-1">Keperluan Tanda Tangan:</label>
                    <textarea readonly disabled rows="3" class="rounded px-3 py-2 w-full border border-zinc-300 bg-zinc-50 text-zinc-600/50 text-sm font-semibold">{{ $signature->keperluan }}</textarea>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
