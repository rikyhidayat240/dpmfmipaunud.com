<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PenilaianMonev>
 */
class PenilaianMonevFactory extends Factory
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
                'aspek' => 'Perencanaan Kegiatan',
                'kriteria' => 'Kejelasan tujuan, kelengkapan dokumen, dsb.',
            ],
            [
                'aspek' => 'Pelaksanaan Kegiatan',
                'kriteria' => 'Pelaksanaan tugas sesuai jadwal, tanggung jawab.',
            ],
            [
                'aspek' => 'Komunikasi dan Kolaborasi',
                'kriteria' => 'Efektivitas komunikasi dan kerja tim.',
            ],
            [
                'aspek' => 'Hasil dan Dampak Kegiatan',
                'kriteria' => 'Pencapaian tujuan dan dampak positif.',
            ],
            [
                'aspek' => 'Evaluasi dan Perbaikan',
                'kriteria' => 'Kemampuan melakukan evaluasi dan perbaikan.',
            ],
        ];
    }
}
