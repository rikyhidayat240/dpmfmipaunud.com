<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.css" />
    <link rel="icon" type="image/png" href="{{ asset('images/LOGO DPM.png') }}">
    <script src="https://cdn.ckeditor.com/ckeditor5/43.0.0/ckeditor5.umd.js"></script>
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
    <title>Form Pengajuan TTE | DPM FMIPA UNUD</title>
</head>

<body class="font-poppins bg-zinc-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-xl">
        <div class="bg-white text-black rounded-lg shadow-md px-4 py-6 space-y-2">
            <!-- Logo -->
            <div class="flex justify-center">
                <img src="{{ asset('images/LOGO DPM.png') }}" alt="DPM FMIPA UNUD" class="size-20">
            </div>

            <!-- Header -->
            <div class="text-center space-y-1 pb-4">
                <h1 class="font-bold text-lg">Formulir Pengajuan TTE</h1>
                <p class="font-normal text-base text-black/50">
                    Lengkapi formulir di bawah untuk mengajukan Tanda Tangan Elektronik
                </p>
                @if (session('success'))
                    <p class="font-normal text-base text-green-700 mt-2">
                        {{ session('success') }}
                    </p>
                @endif
            </div>

            <!-- Formulir -->
            <form method="POST" action="{{ route('signature.store') }}" enctype="multipart/form-data"
                class="space-y-4 mx-4">
                @csrf
                <!-- Nomor Surat -->
                <div>
                    <label for="nomor" class="block font-medium text-sm mb-2">
                        Nomor Surat
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor" placeholder="Masukkan Nomor Surat" required
                        class="rounded-md px-2.5 py-2 w-full border active:border-black text-sm placeholder:text-black/50" />
                </div>

                <!-- Lembaga Pemohon -->
                <div>
                    <label for="id_lembaga" class="block font-medium text-sm mb-2">
                        Lembaga Pemohon
                        <span class="text-red-500">*</span>
                    </label>
                    <select name="id_lembaga" id="id_lembaga" required
                        class="rounded-md px-2.5 py-2 w-full border active:border-black text-sm placeholder:text-black/50">
                        <option value="" disabled selected>Pilih Lembaga Pemohon</option>
                        @foreach ($lembagas as $lembaga)
                            <option value="{{ $lembaga->id }}">{{ $lembaga->name }} Universitas Udayana</option>
                        @endforeach
                    </select>
                </div>

                <!-- Keperluan Tanda Tangan -->
                <div>
                    <label for="keperluan" class="block font-medium text-sm mb-2">
                        Keperluan Tanda Tangan
                        <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keperluan" id="keperluan" rows="2" placeholder="Masukkan Keperluan Tanda Tangan" required
                        class="rounded-md px-2.5 py-2 w-full border active:border-black text-sm placeholder:text-black/50"></textarea>
                </div>

                <!-- File Surat -->
                <div>
                    <label for="file" class="block font-medium text-sm mb-2">
                        File Surat
                        <span class="text-red-500">*</span>
                    </label>
                    <input type="file" name="file" id="file" accept=".pdf" required
                        class="w-full text-sm text-black/50 file:mr-4 file:py-1 file:px-2 file:rounded
                        file:border-0 file:text-sm file:font-semibold file:bg-zinc-50 file:text-zinc-700
                        hover:file:bg-zinc-100 border border-black rounded p-1 sm:p-2" />
                </div>

                <!-- Submit Button -->
                <button type="submit" id="submitButton"
                    class="w-full rounded-md bg-blue-800 text-white hover:bg-blue-900 disabled:bg-blue-900 disabled:cursor-not-allowed my-4 py-2 font-semibold transition-colors">
                    Ajukan TTE
                </button>
            </form>

            <!-- Href -->
            <p class="text-black/75 text-base font-normal text-center pt-4">
                <span class="text-black/50">Sudah melakukan pengajuan? </span>
                <a href="/signature/search" class="underline">Lacak disini</a>
            </p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitButton = document.getElementById('submitButton');

            form.addEventListener('submit', function(e) {
                submitButton.disabled = true;
            });
        });
    </script>
</body>

</html>
