<x-layouts.user>
    <x-slot:title>Beranda</x-slot>
    <style>
        @keyframes appear {
            from {
                opacity: 0;
                scale: 0.9;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                scale: 1;
                transform: translateY(0);
            }
        }
        .fade-in {
            animation: appear linear;
            animation-timeline: view();
            animation-range: entry 0 cover 30%;
        }
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
        {{-- Awal --}}
        <div class="relative font-poppins min-h-[450px] sm:min-h-[500px] lg:min-h-[600px]">
            <img src="{{ asset('images/bg 2.png') }}" alt="" class=" w-full object-cover min-h-screen">
            <div class="absolute top-0 left-0 w-full min-h-screen">
                <div class="animate-fade-in fade-in flex md:flex-row flex-col items-center justify-center gap-6 lg:gap-12 px-12 min-h-screen">
                    <img src="{{ asset('images/LOGO DPM.png') }}" alt=""
                        class="xl:w-96 xl:h-96 lg:w-80 lg:h-80 md:w-64 md:h-64 sm:w-56 sm:h-56 w-48 h-48">
                    <div class="flex flex-col gap-2 md:gap-3 xl:gap-4">
                        <h1
                            class="text-wrap text-xl sm:text-2xl lg:text-3xl xl:text-4xl text-center md:text-left font-bold text-white">
                            DEWAN PERWAKILAN MAHASISWA</h1>
                        <h1
                            class="text-wrap text-xl sm:text-2xl lg:text-3xl xl:text-4xl text-center md:text-left font-bold text-white">
                            FAKULTAS MATEMATIKA DAN<br>ILMU PENGETAHUAN ALAM</h1>
                        <h1
                            class="text-wrap text-xl sm:text-2xl lg:text-3xl xl:text-4xl text-center md:text-left font-bold text-white">
                            UNIVERSITAS UDAYANA</h1>
                    </div>
                </div>
            </div>
        </div>
        {{-- Arti Parlemen --}}
        <h1
            class="animate-fade-in fade-in mt-8 mb-6 md:mt-12 md:mb-8 xl:mt-16 xl:mb-10 text-center font-extrabold text-2xl sm:text-3xl lg:text-4xl text-blue-900">
            PARLEMEN</h1>
        <div class="animate-fade-in fade-in container mx-auto content-center px-8 sm:px-12 lg:px-24">
            <div class="flex flex-col md:flex-row gap-5 items-center">
                <div class="">
                    <img src="{{ asset('images/agniya vikrama.png') }}" class="w-full xl:w-[90%] rounded-lg">
                </div>
                <div class="">
                    <p class="text-base lg:text-lg font-medium text-justify text-slate-800">
                        Nama parlemen ini terinspirasi dari bahasa Sansekerta, dimana
                        <span class="italic font-extrabold">agniya</span> berarti Api, dan
                        <span class="italic font-extrabold">vikrama</span> bermakna Berani. Agniya vikrama
                        bisa diartikan sebegai langkah yang berani menuju perubahan berarti
                        dengan semangat berapi-api.
                    </p>
                </div>
            </div>
        </div>
        {{-- Visi --}}
        <h1
            class="animate-fade-in fade-in mt-8 mb-6 md:mt-12 md:mb-8 xl:mt-16 xl:mb-10 text-center font-extrabold text-2xl sm:text-3xl lg:text-4xl text-blue-900">
            VISI</h1>
        <div class="animate-fade-in fade-in container mx-auto content-center px-8 sm:px-12 lg:px-24">
            <p class="text-lg lg:text-xl hidden xl:block font-medium text-center text-slate-800">
                Mewujudkan DPM FMIPA 2026 sebagai lembaga aspiratif, responsif, transparan, dan kolaboratif
                <br>dalam menjalankan tugas dan wewenang, serta tanggung jawabnya demi tercipta
                <br>tata kelola organisasi yang demokratis dan berorientasi pada kemajuan mahasiswa FMIPA.
            </p>
            <p class="text-lg lg:text-xl block xl:hidden font-medium text-center text-slate-800">
                Mewujudkan DPM FMIPA yang responsif dan berintegritas sebagai badan legislatif
                untuk menciptakan lingkungan yang kondusif dan inklusif.
            </p>
        </div>
        {{-- Misi --}}
        <h1
            class="animate-fade-in fade-in mt-8 mb-6 md:mt-12 md:mb-8 xl:mt-16 xl:mb-10 text-center font-extrabold text-2xl sm:text-3xl lg:text-4xl text-blue-900">
            MISI</h1>
        <div class="animate-fade-in fade-in container mx-auto content-center px-8 sm:px-12 lg:px-24">
            <div class="grid lg:grid-cols-3 sm:grid-cols-2 grid-cols-1 w-full gap-8">
                <div
                    class="bg-zinc-50 shadow-lg rounded-lg p-6 text-center hover:scale-105 transition-transform duration-300">
                    <span class="text-2xl sm:text-3xl font-bold text-blue-900">1</span>
                    <p class="mt-4 text-slate-800">Menegakan fungsi legislasi, aspirasi, dan pengawasan untuk
                    memastikan terselenggaranya tata kelola organisasi yang efektif.</p>
                </div>
                <div
                    class="bg-zinc-50 shadow-lg rounded-lg p-6 text-center hover:scale-105 transition-transform duration-300">
                    <span class="text-2xl sm:text-3xl font-bold text-blue-900">2</span>
                    <p class="mt-4 text-slate-800">Menjalin komunikasi dan kerja sama yang harmonis dengan 
                    seluruh elemen mahasiswa, lembaga mahasiswa, serta pihak fakultas untuk mewujudkan sinergi yang produktif.</p>
                </div>
                <div
                    class="bg-zinc-50 shadow-lg rounded-lg p-6 text-center hover:scale-105 transition-transform duration-300">
                    <span class="text-2xl sm:text-3xl font-bold text-blue-900">3</span>
                    <p class="mt-4 text-slate-800">Mendorong partisipasi aktif mahasiswa dalam proses demokrasi
                    serta penyaluran aspirasi secara terbuka dan berkelanjutan.</p>
                </div>
                
            </div>
        </div>
        {{-- Fungsionaris --}}
        <div class="mt-16 md:mt-24">
            <div class="pt-40 pb-32 relative">
                <div
                    class="absolute w-full h-full top-0 left-0 bg-cover bg-center bg-no-repeat bg-fixed"
                     style="background-image: url('{{ asset('images/fungsionaris2026.jpg') }}')">
                </div>
                <div class="animate-fade-in fade-in items-center text-center justify-center mx-8">
                    <h1 class="text-white text-2xl md:text-4xl font-bold text-center relative">FUNGSIONARIS DPM FMIPA
                        2026</h1>
                    <a href="/fungsionaris" class="text-white text-base sm:text-xl text-center relative">Lihat lebih
                        lengkap ></a>
                </div>
            </div>
        </div>
        {{-- Program Kerja --}}
        <div class="animate-fade-in fade-in container mx-auto content-center px-8 lg:px-16">
            <h1
                class="mt-8 mb-6 md:mt-12 md:mb-8 xl:mt-16 xl:mb-10 text-center font-extrabold text-2xl sm:text-3xl lg:text-4xl text-blue-900">
                PROGRAM KERJA</h1>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-6 lg:gap-8 mt-10">
                @foreach ($blogs as $blog)
                    <div class="relative col-span-1 hover:scale-[102.5%] transition-transform duration-300">
                        <img src="{{ asset('storage/' . ($blog?->cover ?? '')) }}" class="w-full object-cover">
                        <div class="absolute right-0 top-0 left-0 bottom-0 bg-gradient-to-t from-black to-transparent">
                        </div>
                        <div class="absolute right-0 left-0 bottom-4 xl:bottom-6">
                            <h1 class="text-lg sm:text-base md:text-lg lg:text-xl font-semibold text-white truncate mx-4 xl:mx-6">
                                {{ $blog->title }}
                            </h1>
                            <h1 class="text-base sm:text-sm md:text-base text-white font-light truncate mt-0.5 lg:mt-1 mx-4 xl:mx-6">
                                {{ strip_tags(str_replace("&nbsp;", "", str_replace('</p>', "\n\n", $blog->description ?? '')), '<br><strong>') ??
                                    'Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore ipsa doloremque iusto, possimus omnis saepe vero voluptates a, odit similique quas itaque mollitia est atque facere rerum sequi. Est, illo. Lorem ipsum dolor sit amet consectetur adipisicing elit. Facilis natus vero voluptatum aspernatur sit in repellendus blanditiis consequatur! Accusantium reiciendis numquam excepturi voluptatibus earum recusandae exercitationem dolore in modi? Ducimus? Lorem ipsum, dolor sit amet consectetur adipisicing elit. Aperiam laboriosam, ipsa facilis corrupti molestias est at dolores illo, in reprehenderit natus cupiditate quisquam aliquid asperiores ratione dolor quo et fugiat! Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum dolores placeat libero ex voluptatum?' }}
                            </h1>
                        </div>
                    </div>
                @endforeach
            </div>
            <h1 class="text-lg md:text-xl mt-4 sm:mt-6 lg:mt-8 text-center font-medium no-underline">
                <a href="/program-kerja" class="text-slate-900 hover:text-slate-500">Lihat lebih lanjut ></a>
            </h1>
        </div>
        {{-- Aspirasi --}}
        <div class="my-20">
            <div class="pt-40 pb-32 relative">
                <div
                    class="absolute w-full h-full top-0 left-0 bg-cover bg-center bg-no-repeat bg-fixed bg-[url(/public/images/gedung.png)]">
                </div>
                <div class="animate-fade-in fade-in items-center text-center justify-center">
                    <h1 class="text-white text-2xl md:text-4xl font-bold text-center relative">SAMPAIKAN ASPIRASIMU!
                    </h1>
                    <a href="/aspirasi" class="text-white text-base sm:text-xl text-center relative">Lihat lebih lengkap
                        ></a>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/animation/fungsionaris.js') }}"></script>
</x-layouts.user>
