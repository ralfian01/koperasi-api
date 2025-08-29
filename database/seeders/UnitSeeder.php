<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Pcs', 'description' => 'Satuan untuk barang (Pieces).'],
            ['name' => 'Jam', 'description' => 'Satuan waktu per jam, untuk penyewaan.'],
            ['name' => 'Hari', 'description' => 'Satuan waktu per hari, untuk penyewaan.'],
            ['name' => 'Pax', 'description' => 'Satuan per orang/peserta, untuk event atau gedung.'],
            ['name' => '9 Holes', 'description' => 'Satuan untuk permainan golf 9 holes.'],
            ['name' => '18 Holes', 'description' => 'Satuan untuk permainan golf 18 holes.'],
            ['name' => 'Paket 200 Bola', 'description' => 'Satuan paket bola untuk driving range golf.'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['name' => $unit['name']],
                ['description' => $unit['description']]
            );
        }
    }
}
