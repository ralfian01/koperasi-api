<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            [
                'outlet_id' => 1,
                'name' => 'PPN',
                'rate' => 11,
                'type' => 'PERCENTAGE'
            ],
            [
                'outlet_id' => 2,
                'name' => 'PPN',
                'rate' => 11,
                'type' => 'PERCENTAGE'
            ],
        ];

        foreach ($taxes as $member) {
            Tax::updateOrCreate(
                [
                    'outlet_id' => $member['outlet_id'],
                    'name' => $member['name'],
                    'rate' => $member['rate'],
                    'type' => $member['type'],
                ],
                $member
            );
        }
    }
}
