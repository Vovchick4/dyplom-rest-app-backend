<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admins')->delete();
        
        \DB::table('admins')->insert(array (
            0 => 
            array (
                'avatar' => 'users/default.png',
                'created_at' => '2021-08-12 08:46:17',
                'email' => 'admin@admin.com',
                'id' => 1,
                'name' => 'Admin',
                'password' => '$2y$10$Wy1NND9k1CWPy0k2MaPEWeRdf/Nz1mq4C1S1CYnB9EgjNZFRWPOtm',
                'remember_token' => 'dvFDbgrDJ1SZPQPBgBUtmv3Rpobcm9NHgHyDT9Ta5ESJqiPeaYhzNZ2cnX3m',
                'role_id' => 1,
                'settings' => NULL,
                'updated_at' => '2021-08-12 08:46:17',
            ),
        ));
        
        
    }
}