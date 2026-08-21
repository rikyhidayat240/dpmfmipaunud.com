<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\KategoriAspirasi;
use App\Models\KategoriSurat;
use App\Models\Lembaga;
use App\Models\PenilaianMonev;
use App\Models\ProgramStudi;
use App\Models\User;
use Database\Factories\DosenFactory;
use Database\Factories\KategoriAspirasiFactory;
use Database\Factories\KategoriSuratFactory;
use Database\Factories\LembagaFactory;
use Database\Factories\PenilaianMonevFactory;
use Database\Factories\ProgramStudiFactory;
use Database\Factories\UserFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (UserFactory::specific() as $userData) {
            User::factory()->create($userData);
        }
        foreach (DosenFactory::specific() as $dosenData) {
            Dosen::factory()->create($dosenData);
        }
        foreach (ProgramStudiFactory::specific() as $prodiData) {
            ProgramStudi::factory()->create($prodiData);
        }
        foreach (LembagaFactory::specific() as $lembagaData) {
            Lembaga::factory()->create($lembagaData);
        }
        foreach (PenilaianMonevFactory::specific() as $penilaianData) {
            PenilaianMonev::factory()->create($penilaianData);
        }
        foreach (KategoriAspirasiFactory::specific() as $kategoriData) {
            KategoriAspirasi::factory()->create($kategoriData);
        }
        foreach (KategoriSuratFactory::specific() as $kategoriData) {
            KategoriSurat::factory()->create($kategoriData);
        }
    }
}
