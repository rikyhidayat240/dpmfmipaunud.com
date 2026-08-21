<x-layouts.user>
    <x-slot:title>Program Kerja</x-slot>
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
            animation-range: entry 0 cover 200px;
        }
        .animate-fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.6s ease-out;
        }
        .animate-show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <div class="font-[Poppins]">
        {{-- Awal --}}
        <div class="relative font-poppins min-h-screen">
            <img src="{{ asset('images/bg 3.png') }}" alt="" class="w-full object-cover min-h-screen">
            <div class="absolute top-0 left-0 w-full min-h-screen">
                <div
                    class="animate-fade-in fade-in container mx-auto content-center flex md:flex-row flex-col items-center justify-center gap-6 lg:gap-12 px-12 min-h-screen">
                    <div class="flex flex-col gap-2 md:gap-3 xl:gap-4 items-center">
                        <img src="{{ asset('images/LOGO DPM.png') }}" alt="" class="w-24">
                        <h1
                            class="text-wrap text-xl sm:text-2xl lg:text-3xl xl:text-4xl text-center font-bold text-white">
                            PROGRAM KERJA DPM FMIPA 2026</h1>
                        <p
                            class="text-wrap text-sm sm:text-base lg:text-lg xl:text-xl text-center font-light text-white">
                            Fakultas Matematika dan Ilmu Pengetahuan Alam, memliki beberapa rancangan program kerja yang
                            telah di sesuaikan dan juga disempurnakan sesuai degan aturan yang ada. Adapun program kerja
                            sama
                            dan kolaborasi dengan berbagai pihak lembaga mahasiswa lainnya yang dilakukan untuk
                            memperluas jaringan dan memberikan kesempatan
                            pengembangan diri bagi anggota organisasi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- INTI --}}
    <div class="animate-fade-in container mx-auto">
        <div class="">
            <h1 class="animate-fade-in fade-in mt-8 md:mt-12 xl:mt-16 text-center font-extrabold text-3xl lg:text-4xl text-blue-900">INTI</h1>
            <h3 class="animate-fade-in fade-in mt-1 mb-6 md:mb-8 xl:md-10 text-center font-semibold text-base lg:text-lg text-slate-500">DPM FMIPA</h3>
            <div class="animate-fade-in fade-in flex flex-col items-center justify-center w-full">
                <div class="w-full max-w-[900px] mx-auto relative">
                    <div class="px-4 sm:px-0">
                        <div class="w-full overflow-x-auto">
                            <ul
                                class="flex justify-center whitespace-nowrap border-b border-black text-center font-medium text-slate-400 text-[12px] sm:text-[16px] min-w-max">
                                @foreach ($inti as $i)
                                    <li class="flex-shrink-0">
                                        <a class="tab-link p-4 text-base md:text-lg inline-block cursor-pointer text-black" data-tab="tab-{{ $i->tab }}">
                                            {{ $i->tab }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg text-white w-full">
                    @foreach ($inti as $i)
                        <div id="tab-{{ $i->tab }}" class="tab-content hidden">
                            <x-judul :content="$i"></x-judul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- KOMISI I --}}
    <div class="animate-fade-in container mx-auto">
        <div class="">
            <h1 class="animate-fade-in fade-in mt-8 md:mt-12 xl:mt-16 text-center font-extrabold text-3xl lg:text-4xl text-blue-900">KOMISI I</h1>
            <h3 class="animate-fade-in fade-in mt-1 mb-6 md:mb-8 xl:md-10 text-center font-semibold text-base lg:text-lg text-slate-500">Legislator</h3>
            <div class="animate-fade-in fade-in flex flex-col items-center justify-center w-full">
                <div class="w-full max-w-[900px] mx-auto relative">
                    <div class="px-4 sm:px-0">
                        <div class="w-full overflow-x-auto">
                            <ul
                                class="flex justify-center whitespace-nowrap border-b border-black text-center font-medium text-slate-400 text-[12px] sm:text-[16px] min-w-max">
                                @foreach ($komisi1 as $i)
                                    <li class="flex-shrink-0">
                                        <a class="tab-link p-4 text-base md:text-lg inline-block cursor-pointer text-black" data-tab="tab-{{ $i->tab }}">
                                            {{ $i->tab }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg text-white w-full">
                    @foreach ($komisi1 as $i)
                        <div id="tab-{{ $i->tab }}" class="tab-content hidden">
                            <x-judul :content="$i"></x-judul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- KOMISI II --}}
    <div class="animate-fade-in container mx-auto">
        <div class="">
            <h1 class="animate-fade-in fade-in mt-8 md:mt-12 xl:mt-16 text-center font-extrabold text-3xl lg:text-4xl text-blue-900">KOMISI II</h1>
            <h3 class="animate-fade-in fade-in mt-1 mb-6 md:mb-8 xl:md-10 text-center font-semibold text-base lg:text-lg text-slate-500">Advisor & Corrector</h3>
            <div class="animate-fade-in fade-in flex flex-col items-center justify-center w-full">
                <div class="w-full max-w-[900px] mx-auto relative">
                    <div class="px-4 sm:px-0">
                        <div class="w-full overflow-x-auto">
                            <ul
                                class="flex justify-center whitespace-nowrap border-b border-black text-center font-medium text-slate-400 text-[12px] sm:text-[16px] min-w-max">
                                @foreach ($komisi2 as $i)
                                    <li class="flex-shrink-0">
                                        <a class="tab-link p-4 text-base md:text-lg inline-block cursor-pointer text-black" data-tab="tab-{{ $i->tab }}">
                                            {{ $i->tab }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg text-white w-full">
                    @foreach ($komisi2 as $i)
                        <div id="tab-{{ $i->tab }}" class="tab-content hidden">
                            <x-judul :content="$i"></x-judul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- KOMISI III --}}
    <div class="animate-fade-in container mx-auto">
        <div class="">
            <h1 class="animate-fade-in fade-in mt-8 md:mt-12 xl:mt-16 text-center font-extrabold text-3xl lg:text-4xl text-blue-900">KOMISI III</h1>
            <h3 class="animate-fade-in fade-in mt-1 mb-6 md:mb-8 xl:md-10 text-center font-semibold text-base lg:text-lg text-slate-500">Aspirator</h3>
            <div class="animate-fade-in fade-in flex flex-col items-center justify-center w-full">
                <div class="w-full max-w-[900px] mx-auto relative">
                    <div class="px-4 sm:px-0">
                        <div class="w-full overflow-x-auto">
                            <ul
                                class="flex justify-center whitespace-nowrap border-b border-black text-center font-medium text-slate-400 text-[12px] sm:text-[16px] min-w-max">
                                @foreach ($komisi3 as $i)
                                    <li class="flex-shrink-0">
                                        <a class="tab-link p-4 text-base md:text-lg inline-block cursor-pointer text-black" data-tab="tab-{{ $i->tab }}">
                                            {{ $i->tab }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg text-white w-full">
                    @foreach ($komisi3 as $i)
                        <div id="tab-{{ $i->tab }}" class="tab-content hidden">
                            <x-judul :content="$i"></x-judul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- KOMISI IV --}}
    <div class="animate-fade-in container mx-auto">
        <div class="">
            <h1 class="animate-fade-in fade-in mt-8 md:mt-12 xl:mt-16 text-center font-extrabold text-3xl lg:text-4xl text-blue-900">KOMISI IV</h1>
            <h3 class="animate-fade-in fade-in mt-1 mb-6 md:mb-8 xl:md-10 text-center font-semibold text-base lg:text-lg text-slate-500">Supervisor & Reminder</h3>
            <div class="animate-fade-in fade-in flex flex-col items-center justify-center w-full">
                <div class="w-full max-w-[900px] mx-auto relative">
                    <div class="px-4 sm:px-0">
                        <div class="w-full overflow-x-auto">
                            <ul
                                class="flex justify-center whitespace-nowrap border-b border-black text-center font-medium text-slate-400 text-[12px] sm:text-[16px] min-w-max">
                                @foreach ($komisi4 as $i)
                                    <li class="flex-shrink-0">
                                        <a class="tab-link p-4 text-base md:text-lg inline-block cursor-pointer text-black" data-tab="tab-{{ $i->tab }}">
                                            {{ $i->tab }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg text-white w-full">
                    @foreach ($komisi4 as $i)
                        <div id="tab-{{ $i->tab }}" class="tab-content hidden">
                            <x-judul :content="$i"></x-judul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- KOMISI V --}}
    <div class="animate-fade-in container mx-auto">
        <div class="">
            <h1 class="animate-fade-in fade-in mt-8 md:mt-12 xl:mt-16 text-center font-extrabold text-3xl lg:text-4xl text-blue-900">KOMISI V</h1>
            <h3 class="animate-fade-in fade-in mt-1 mb-6 md:mb-8 xl:md-10 text-center font-semibold text-base lg:text-lg text-slate-500">Media & Public Relation</h3>
            <div class="animate-fade-in fade-in flex flex-col items-center justify-center w-full">
                <div class="w-full max-w-[900px] mx-auto relative">
                    <div class="px-4 sm:px-0">
                        <div class="w-full overflow-x-auto">
                            <ul
                                class="flex justify-center whitespace-nowrap border-b border-black text-center font-medium text-slate-400 text-sm md:text-base min-w-max">
                                @foreach ($komisi5 as $i)
                                    <li class="flex-shrink-0">
                                        <a class="tab-link p-4 text-base md:text-lg inline-block cursor-pointer text-black" data-tab="tab-{{ $i->tab }}">
                                            {{ $i->tab }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-700 rounded-lg text-white w-full">
                    @foreach ($komisi5 as $i)
                        <div id="tab-{{ $i->tab }}" class="tab-content hidden">
                            <x-judul :content="$i"></x-judul>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script defer src="{{ asset('js/animation/programkerja.js') }}"></script>
</x-layouts.user>
