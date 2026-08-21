<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriAspirasi>
 */
class KategoriAspirasiFactory extends Factory
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
                'name' => 'Akademik',
                'description' => 'Hal yang terkait dengan akademik, seperti jadwal kuliah, materi kuliah, dan lainnya.',
            ],
            [
                'name' => 'Kemahasiswaan',
                'description' => 'Hal yang terkait dengan kemahasiswaan, seperti kegiatan kemahasiswaan, dan lainnya.',
            ],
            [
                'name' => 'Administrasi',
                'description' => 'Hal yang terkait dengan administrasi, seperti persuratan, perizinan, dan lainnya.',
            ],
            [
                'name' => 'Fasilitas',
                'description' => 'Hal yang terkait dengan fasilitas, seperti sarana dan prasarana penunjang pembelajaran, dan lainnya.',
            ],
            [
                'name' => 'Informasi',
                'description' => 'Hal yang terkait dengan penyebaran informasi, seperti pengumuman melalui website, media sosial, dan lainnya.',
            ],
            [
                'name' => 'Lembaga Mahasiswa',
                'description' => 'Hal yang terkait dengan lembaga mahasiswa, seperti organisasi mahasiswa, dan lainnya.',
            ],
        ];
    }
}
