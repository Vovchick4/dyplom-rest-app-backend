<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'created_at' => '2021-08-12 08:46:17',
                'display_name' => 'Superadmin',
                'id' => 1,
                'name' => 'superadmin',
                'updated_at' => '2021-08-13 09:36:38',
            ),
            1 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'display_name' => 'Administrator',
                'id' => 2,
                'name' => 'admin',
                'updated_at' => '2021-08-13 09:37:25',
            ),
        ));
        
        
    }
}