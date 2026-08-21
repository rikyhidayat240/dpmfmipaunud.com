<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgramStudi>
 */
class ProgramStudiFactory extends Factory
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
                'name' => 'Program Studi Kimia',
                'description' => '<p>Ini adalah deskripsi singkat dari program studi kimia universitas udayana</p>',
                'id_kaprodi' => 7,
                'id_dospem' => 1,
            ],
            [
                'name' => 'Program Studi Fisika',
                'description' => '<p>Ini adalah deskripsi singkat dari program studi fisika universitas udayana</p>',
                'id_kaprodi' => 8,
                'id_dospem' => 2,
            ],
            [
                'name' => 'Program Studi Biologi',
                'description' => '<p>Ini adalah deskripsi singkat dari program studi biologi universitas udayana</p>',
                'id_kaprodi' => 9,
                'id_dospem' => 3,
            ],
            [
                'name' => 'Program Studi Matematika',
                'description' => '<p>Ini adalah deskripsi singkat dari program studi matematika universitas udayana</p>',
                'id_kaprodi' => 10,
                'id_dospem' => 4,
            ],
            [
                'name' => 'Program Studi Farmasi',
                'description' => '<p>Ini adalah deskripsi singkat dari program studi farmasi universitas udayana</p>',
                'id_kaprodi' => 11,
                'id_dospem' => 5,
            ],
            [
                'name' => 'Program Studi Informatika',
                'description' => '<p>Ini adalah deskripsi singkat dari program studi informatika universitas udayana</p>',
                'id_kaprodi' => 12,
                'id_dospem' => 6,
            ],
        ];
    }
}
