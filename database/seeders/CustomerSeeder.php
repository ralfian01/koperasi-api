<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = [
            [
                'business_id' => 1,
                'name' => 'Sewa Harian',
            ],
            [
                'business_id' => 1,
                'name' => 'Sewa Per Jam',
            ],
            [
                'business_id' => 2,
                'name' => 'Driving Range',
            ],
            [
                'business_id' => 2,
                'name' => 'Driving Range',
            ],
        ];

        foreach ($customer as $member) {
            Customer::updateOrCreate(
                [
                    'business_id' => $member['business_id'],
                    'name' => $member['name'],
                ],
                $member
            );
        }
    }
}
