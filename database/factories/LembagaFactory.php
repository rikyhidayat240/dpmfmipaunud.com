<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lembaga>
 */
class LembagaFactory extends Factory
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
                'username' => 'BEM FMIPA',
                'name' => 'Badan Eksekutif Mahasiswa Fakultas MIPA',
                'email' => 'humas@bemfmipaunud.com',
                'phone' => '081234567890',
                'description' => '<p>Ini adalah deskripsi singkat dari Lembaga Mahasiswa Badan Eksekutif Mahasiswa Fakultas MIPA Universitas Udayana</p>'
            ],
            [
                'username' => 'DPM FMIPA',
                'name' => 'Dewan Perwakilan Mahasiswa Fakultas MIPA',
                'email' => 'humas@dpmfmipaunud.com',
                'phone' => '081234567890',
                'description' => '<p>Ini adalah deskripsi singkat dari Lembaga Mahasiswa Dewan Perwakilan Mahasiswa Fakultas MIPA Universitas Udayana</p>'
            ],
            [
                'username' => 'HIMAKI',
                'name' => 'Himpunan Mahasiswa Kimia Fakultas MIPA',
                'email' => 'humas@himaki.com',
                'phone' => '081234567890',
                'description' => '<p>Ini adalah deskripsi singkat dari Lembaga Mahasiswa Himpunan Mahasiswa Kimia Fakultas MIPA Universitas Udayana</p>'
            ],
            [
                'username' => 'HIMAFI',
                'name' => 'Himpunan Mahasiswa Fisika Fakultas MIPA',
                'email' => 'humas@himafi.com',
                'phone' => '081234567890',
                'description' => '<p>Ini adalah deskripsi singkat dari Lembaga Mahasiswa Himpunan Mahasiswa Fisika Fakultas MIPA Universitas Udayana</p>'
            ],
            [
                'username' => 'HIMABIO',
                'name' => 'Himpunan Mahasiswa Biologi Fakultas MIPA',
                'email' => 'humas@himabio.com',
                'phone' => '081234567890',
                'description' => '<p>Ini adalah deskripsi singkat dari Lembaga Mahasiswa Himpunan Mahasiswa Biologi Fakultas MIPA Universitas Udayana</p>'
            ],
            [
                'username' => 'HIMATIKA',
                'name' => 'Himpunan Mahasiswa Matematika Fakultas MIPA',
                'email' => 'humas@himatika.com',
                'phone' => '081234567890',
                'description' => '<p>Ini adalah deskripsi singkat dari Lembaga Mahasiswa Himpunan Mahasiswa Matematika Fakultas MIPA Universitas Udayana</p>'
            ],
            [
                'username' => 'HIMAFARMA',
                'name' => 'Himpunan Mahasiswa Farmasi Fakultas MIPA',
                'email' => 'humas@himafarma.com',
                'phone' => '081234567890',
                'description' => '<p>Ini adalah deskripsi singkat dari Lembaga Mahasiswa Himpunan Mahasiswa Farmasi Fakultas MIPA Universitas Udayana</p>'
            ],
            [
                'username' => 'HIMAIF',
                'name' => 'Himpunan Mahasiswa Informatika Fakultas MIPA',
                'email' => 'humas@himaif.com',
                'phone' => '081234567890',
                'description' => '<p>Ini adalah deskripsi singkat dari Lembaga Mahasiswa Himpunan Mahasiswa Informatika Fakultas MIPA Universitas Udayana</p>'
            ],
        ];
    }
}
