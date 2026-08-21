<x-layouts.user>
    <x-slot:title>Aspirasi</x-slot>
    <style>
        .animate-fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }
        .animate-show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <div class="font-[Poppins]">
        <div class="animate-fade-in relative font-poppins min-h-[450px] sm:min-h-[500px] lg:min-h-[600px]">
            <img src="{{ asset('images/jumdek1.png') }}" alt="" class="w-full object-cover min-h-screen">
            <div class="absolute top-0 left-0 w-full min-h-screen">
                <div class="container mx-auto content-center flex md:flex-row flex-col items-center justify-center gap-6 lg:gap-12 px-12 min-h-screen">
                    <div class="flex flex-col gap-2 md:gap-3 xl:gap-4">
                        <h1
                            class="text-wrap text-xl sm:text-2xl lg:text-3xl xl:text-4xl text-center font-bold text-white">
                            AYO BERSATU UNTUK ASPIRASI</h1>
                        <p
                            class="text-wrap text-sm sm:text-base lg:text-lg xl:text-xl text-center font-light text-white">
                            Saatnya kita, sebagai mahasiswa, bergerak dan menyuarakan aspirasi kita untuk masa depan yang gemilang bagi FMIPA dan Udayana. Dengan bersatu dan bergerak bersama, suara kita akan menjadi kekuatan yang mampu mendorong perubahan positif untuk menciptakan lingkungan akademik yang lebih baik dan prestasi yang membanggakan. Melalui semangat kebersamaan dan tekad yang kuat, kita dapat mewujudkan visi FMIPA dan Udayana sebagai institusi pendidikan yang unggul dan berdaya saing di tingkat nasional maupun internasional.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="animate-fade-in">
            <form action="{{ route('aspirasi.store') }}" method="POST" enctype="multipart/form-data"
                class="formulir px-8 sm:px-12 lg:px-24 mt-8 mb-6 md:mt-12 md:mb-8 xl:mt-16 xl:mb-10 flex flex-col gap-4">
                @csrf
                <h1 class="text-blue-900 font-bold text-xl sm:text-2xl lg:text-3xl">ADA ASPIRASI YANG INGIN DISAMPAIKAN?</h1>
                <div class="h-0.5 lg:h-1 bg-slate-300 rounded-full"></div>
                <div class="sm:mt-1 lg:mt-2 flex md:flex-row flex-col justify-between w-full gap-4 sm:gap-6 lg:gap-8">
                    <div class="flex flex-1 flex-col gap-2 lg:gap-4">
                        <label class="text-blue-900 font-bold text-base sm:text-xl lg:text-2xl">PROGRAM STUDI*</label>
                        <select name="prodi" class="block px-3 py-2 border rounded border-blue-800 focus:border-blue-900 text-slate-800 text-sm md:text-base">
                            <option value="" disabled selected>Pilih Program Studi</option>
                            <option value="Kimia">Program Studi Kimia</option>
                            <option value="Fisika">Program Studi Fisika</option>
                            <option value="Biologi">Program Studi Biologi</option>
                            <option value="Matematika">Program Studi Matematika</option>
                            <option value="Farmasi">Program Studi Farmasi</option>
                            <option value="Informatika">Program Studi Informatika</option>
                        </select>
                        @error('prodi')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="flex flex-1 flex-col gap-2 lg:gap-4">
                        <label class="text-blue-900 font-bold text-base sm:text-xl lg:text-2xl">KATEGORI*</label>
                        <select name="kategori" class="block px-3 py-2 border rounded border-blue-800 focus:border-blue-900 text-slate-800 text-sm md:text-base">
                            <option value="" disabled selected>Pilih Kategori</option>
                            <option value="Akademik">Kategori Akademik</option>
                            <option value="Kemahasiswaan">Kategori Kemahasiswaan</option>
                            <option value="Administrasi">Kategori Administrasi</option>
                            <option value="Fasilitas">Kategori Fasilitas</option>
                            <option value="Informasi">Kategori Informasi</option>
                            <option value="Lembaga Mahasiswa">Kategori Lembaga Mahasiswa</option>
                        </select>
                        @error('kategori')
                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col gap-2 lg:gap-4 mt-3">
                    <label class="text-blue-900 font-bold text-base sm:text-xl lg:text-2xl">DESKRIPSI*</label>
                    <div id="editor" class="h-96" name="deskripsi"></div>
                </div>
                <div class="flex flex-col gap-2 lg:gap-4 mt-3">
                    <label class="text-blue-900 font-bold text-base sm:text-xl lg:text-2xl">FOTO PENDUKUNG</label>
                    <input type="file" name="dokumentasi" accept="image/*" multiple
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded
                        file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100 border border-blue-800 rounded p-1 sm:p-2"/>
                    <p class="text-xs md:text-sm text-slate-500">Format yang diterima: JPG, JPEG, PNG, WEBP.</p>
                    @error('images.*')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="konfirm w-full md:w-auto bg-blue-800 text-white hover:bg-blue-900 mb-10 font-semibold text-xl px-20 py-2 rounded">
                        KIRIM
                    </button>
                </div>
                <h1 class="text-center text-sm md:text-base text-slate-800 mb-4 md:mb-6 leading-8 sm:leading-normal">
                    Ingin memberikan aspirasi selengkapnya?
                    <button class=" bg-blue-800 rounded px-2">
                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSes0856S3ZsvsGjLhV7B3kQ1TBXbMtx_nQH3wBYwQx75YzQog/viewform" class="text-white font-semibold">Klik disini!</a>
                    </button>
                </h1>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/animation/aspirasi.js') }}"></script>
    <script src="{{ asset('js/animation/fungsionaris.js') }}"></script>
</x-layouts.user>
