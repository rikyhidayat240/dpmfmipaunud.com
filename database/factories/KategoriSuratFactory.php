<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriSurat>
 */
class KategoriSuratFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public static function specific(): array
    {
        return [
            [
                'name' => 'Surat Undangan',
                'description' => 'Kategori ini mencakup surat undangan kegiatan dari dan/atau kepada instansi, lembaga, atau program kerja.'
            ],
            [
                'name' => 'Surat Pemberitahuan',
                'description' => 'Kategori ini mencakup surat pemberitahuan informasi dari dan/atau kepada instansi, lembaga, atau program kerja.'
            ],
            [
                'name' => 'Surat Peminjaman',
                'description' => 'Kategori ini mencakup surat peminjaman barang dan/atau gedung kepada instansi, lembaga, atau pihak berwenang.'
            ],
            [
                'name' => 'Surat Permohonan',
                'description' => 'Kategori ini mencakup surat permohonan suatu perihal dari dan/atau kepada instansi, lembaga, atau pihak berwenang.'
            ],
        ];
    }
}
