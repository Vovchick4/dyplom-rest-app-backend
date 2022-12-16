<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SuperAdminsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(array(
            0 =>
            array(
                'email' => 'superadmin@admin.com',
                'name' => 'Super',
                'lastname' => 'Admin',
                'image' => 'users/default.png',
                'password' => '$2a$08$i3sG4lT7cVHzfbxX./giCu2CtBY0cjvpyYrUQy1yGFpL7B2rKv/O.', //JnR390XB USE BCRYPT MethoD
                'remember_token' => 'dvFDbgrDJ1SZPQPBgBUtmv3Rpobcm9NHgHyDT9Ta5ESJqiPeaYhzNZ2cnX3m',
                'role' => 'super-admin',
                'restaurant_id' => 1,
            ),
        ));
    }
}
