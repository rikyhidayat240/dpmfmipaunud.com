<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dosen>
 */
class DosenFactory extends Factory
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
                'name' => 'Prof. Dr. Irdhawati, S.Si., M.Si.',
                'nip' => '197201011999032002',
                'nidn' => '0000000000',
                'email' => 'irdhawati@unud.ac.id',
                'role' => 'Dospem',
            ],
            [
                'name' => 'Dr. Ida Bagus Made Suryatika, S.Si., M.Si.',
                'nip' => '196909171998021001',
                'nidn' => '0000000000',
                'email' => 'suryatikabiofisika@unud.ac.id',
                'role' => 'Dospem',
            ],
            [
                'name' => 'I K. Putra Juliantara, S.Pd., M.Si.',
                'nip' => '198907052022031004',
                'nidn' => '0000000000',
                'email' => 'putrajuliantara@unud.ac.id',
                'role' => 'Dospem',
            ],
            [
                'name' => 'I Wayan Sumarjaya, S.Si., M.Stats.',
                'nip' => '197704212005011001',
                'nidn' => '0000000000',
                'email' => 'sumarjaya@unud.ac.id',
                'role' => 'Dospem',
            ],
            [
                'name' => 'apt. I Wayan Martadi Santika, S.Farm., M.Si.',
                'nip' => '198806202019031009',
                'nidn' => '0000000000',
                'email' => 'martadisantika@unud.ac.id',
                'role' => 'Dospem',
            ],
            [
                'name' => 'Ir. I Gusti Ngurah Anom Cahyadi Putra, ST., M.Cs.',
                'nip' => '198403172019031005',
                'nidn' => '0000000000',
                'email' => 'anom.cp@unud.ac.id',
                'role' => 'Dospem',
            ],
            [
                'name' => 'Dr. Ida Ayu Gede Widihati, S.Si, M.Si.',
                'nip' => '196812311996032003',
                'nidn' => '0000000000',
                'email' => 'gedewidihati@unud.ac.id',
                'role' => 'Kaprodi',
            ],
            [
                'name' => 'Dr. Drs. Wayan Gede Suharta, M.Si.',
                'nip' => '196506161992031002',
                'nidn' => '0000000000',
                'email' => 'gede_suharta@unud.ac.id',
                'role' => 'Kaprodi',
            ],
            [
                'name' => 'Prof. Dr. I Ketut Ginantra, S.Pd., M.Si.',
                'nip' => '197106121999031001',
                'nidn' => '0000000000',
                'email' => 'ketut_ginantra@unud.ac.id',
                'role' => 'Kaprodi',
            ],
            [
                'name' => 'I Gusti Ayu Made Srinadi, S.Si., M.Si.',
                'nip' => '197112131997022001',
                'nidn' => '0000000000',
                'email' => 'srinadi@unud.ac.id',
                'role' => 'Kaprodi',
            ],
            [
                'name' => 'Dr. apt. Eka Indra Setyawan, S.Farm., M.Sc.',
                'nip' => '198203242008121001',
                'nidn' => '0000000000',
                'email' => 'indrasetyawan@unud.ac.id',
                'role' => 'Kaprodi',
            ],
            [
                'name' => 'Dr. Ir. I Ketut Gede Suhartana, S.Kom., M.Kom., IPM., ASEAN.Eng',
                'nip' => '197201102008121001',
                'nidn' => '0000000000',
                'email' => 'ikg.suhartana@unud.ac.id',
                'role' => 'Kaprodi',
            ],
            [
                'name' => 'Prof. Dra. Ni Luh Watiniasih, M.Sc., Ph.D.',
                'nip' => '196606091991032002',
                'nidn' => '0000000000',
                'email' => 'luhwatiniasih@unud.ac.id',
                'role' => 'Jajaran Dekan',
            ],
            [
                'name' => 'Dr. Ir. Ngurah Agus Sanjaya ER, S.Kom., M.Kom.',
                'nip' => '197803212005011001',
                'nidn' => '0000000000',
                'email' => 'agus_sanjaya@unud.ac.id',
                'role' => 'Jajaran Dekan',
            ],
            [
                'name' => 'Prof. Dr. Drs. I Made Sukadana, M.Si.',
                'nip' => '196805041994021001',
                'nidn' => '0000000000',
                'email' => 'im_sukadana@unud.ac.id',
                'role' => 'Jajaran Dekan',
            ],
            [
                'name' => 'Prof. Ni Nyoman Rupiasih, S.Si, M.Si., Ph.D.',
                'nip' => '196904081994122001',
                'nidn' => '0000000000',
                'email' => 'rupiasih@unud.ac.id',
                'role' => 'Jajaran Dekan',
            ],
        ];
    }
}
