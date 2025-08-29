<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivilegeSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['tp_code' => 'ACCOUNT_MANAGE_VIEW', 'tp_description' => 'View account list'],
            ['tp_code' => 'ACCOUNT_MANAGE_ADD', 'tp_description' => 'Add or delete account'],
            ['tp_code' => 'ACCOUNT_MANAGE_MODIFY', 'tp_description' => 'Modify account'],
            ['tp_code' => 'ACCOUNT_MANAGE_SUSPEND', 'tp_description' => 'Suspend or activate account'],
            ['tp_code' => 'ACCOUNT_MANAGE_PRIVILEGE', 'tp_description' => 'Set account privileges'],

            ['tp_code' => 'ADMIN_MANAGE_ADD', 'tp_description' => 'Add or delete admin'],
            ['tp_code' => 'ADMIN_MANAGE_VIEW', 'tp_description' => 'View admin list'],
            ['tp_code' => 'ADMIN_MANAGE_SUSPEND', 'tp_description' => 'Suspend or activate admin'],
            ['tp_code' => 'ADMIN_MANAGE_PRIVILEGE', 'tp_description' => 'Set admin privileges'],

            ['tp_code' => 'POSITION_MANAGE_VIEW', 'tp_description' => 'Manager add or delete position'],
            ['tp_code' => 'POSITION_MANAGE_ADD', 'tp_description' => 'Manager view position'],
            ['tp_code' => 'POSITION_MANAGE_MODIFY', 'tp_description' => 'Manager modify position'],

            ['tp_code' => 'SYSTEM_OPT_MANAGE_VIEW', 'tp_description' => 'Manager add or delete system options'],
            ['tp_code' => 'SYSTEM_OPT_MANAGE_ADD', 'tp_description' => 'Manager view system options'],
            ['tp_code' => 'SYSTEM_OPT_MANAGE_MODIFY', 'tp_description' => 'Manager modify system options'],

            ['tp_code' => 'IN_MAIL_MANAGE_VIEW', 'tp_description' => 'Manager add or delete incoming mail'],
            ['tp_code' => 'IN_MAIL_MANAGE_ADD', 'tp_description' => 'Manager view incoming mail'],
            ['tp_code' => 'IN_MAIL_MANAGE_MODIFY', 'tp_description' => 'Manager modify incoming mail'],

            ['tp_code' => 'OUT_MAIL_MANAGE_VIEW', 'tp_description' => 'Manager add or delete outcoming mail'],
            ['tp_code' => 'OUT_MAIL_MANAGE_ADD', 'tp_description' => 'Manager view outcoming mail'],
            ['tp_code' => 'OUT_MAIL_MANAGE_MODIFY', 'tp_description' => 'Manager modify outcoming mail'],
        ];

        DB::table('privilege')->insert($data);
    }
}
