<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menus')->delete();
        
        \DB::table('menus')->insert(array (
            0 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 1,
                'name' => 'admin',
                'updated_at' => '2021-08-12 08:47:39',
            ),
        ));
        
        
    }
}