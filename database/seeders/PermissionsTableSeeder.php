<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 1,
                'key' => 'browse_admin',
                'table_name' => NULL,
                'updated_at' => '2021-08-12 08:47:39',
            ),
            1 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 2,
                'key' => 'browse_bread',
                'table_name' => NULL,
                'updated_at' => '2021-08-12 08:47:39',
            ),
            2 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 3,
                'key' => 'browse_database',
                'table_name' => NULL,
                'updated_at' => '2021-08-12 08:47:39',
            ),
            3 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 4,
                'key' => 'browse_media',
                'table_name' => NULL,
                'updated_at' => '2021-08-12 08:47:39',
            ),
            4 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 5,
                'key' => 'browse_compass',
                'table_name' => NULL,
                'updated_at' => '2021-08-12 08:47:39',
            ),
            5 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 6,
                'key' => 'browse_menus',
                'table_name' => 'menus',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            6 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 7,
                'key' => 'read_menus',
                'table_name' => 'menus',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            7 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 8,
                'key' => 'edit_menus',
                'table_name' => 'menus',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            8 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 9,
                'key' => 'add_menus',
                'table_name' => 'menus',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            9 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 10,
                'key' => 'delete_menus',
                'table_name' => 'menus',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            10 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 11,
                'key' => 'browse_roles',
                'table_name' => 'roles',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            11 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 12,
                'key' => 'read_roles',
                'table_name' => 'roles',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            12 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 13,
                'key' => 'edit_roles',
                'table_name' => 'roles',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            13 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 14,
                'key' => 'add_roles',
                'table_name' => 'roles',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            14 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 15,
                'key' => 'delete_roles',
                'table_name' => 'roles',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            15 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 16,
                'key' => 'browse_users',
                'table_name' => 'users',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            16 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 17,
                'key' => 'read_users',
                'table_name' => 'users',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            17 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 18,
                'key' => 'edit_users',
                'table_name' => 'users',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            18 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 19,
                'key' => 'add_users',
                'table_name' => 'users',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            19 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 20,
                'key' => 'delete_users',
                'table_name' => 'users',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            20 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 21,
                'key' => 'browse_settings',
                'table_name' => 'settings',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            21 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 22,
                'key' => 'read_settings',
                'table_name' => 'settings',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            22 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 23,
                'key' => 'edit_settings',
                'table_name' => 'settings',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            23 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 24,
                'key' => 'add_settings',
                'table_name' => 'settings',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            24 => 
            array (
                'created_at' => '2021-08-12 08:47:39',
                'id' => 25,
                'key' => 'delete_settings',
                'table_name' => 'settings',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            25 => 
            array (
                'created_at' => '2021-08-12 09:33:56',
                'id' => 26,
                'key' => 'browse_admins',
                'table_name' => 'admins',
                'updated_at' => '2021-08-12 09:33:56',
            ),
            26 => 
            array (
                'created_at' => '2021-08-12 09:33:56',
                'id' => 27,
                'key' => 'read_admins',
                'table_name' => 'admins',
                'updated_at' => '2021-08-12 09:33:56',
            ),
            27 => 
            array (
                'created_at' => '2021-08-12 09:33:56',
                'id' => 28,
                'key' => 'edit_admins',
                'table_name' => 'admins',
                'updated_at' => '2021-08-12 09:33:56',
            ),
            28 => 
            array (
                'created_at' => '2021-08-12 09:33:56',
                'id' => 29,
                'key' => 'add_admins',
                'table_name' => 'admins',
                'updated_at' => '2021-08-12 09:33:56',
            ),
            29 => 
            array (
                'created_at' => '2021-08-12 09:33:56',
                'id' => 30,
                'key' => 'delete_admins',
                'table_name' => 'admins',
                'updated_at' => '2021-08-12 09:33:56',
            ),
            30 => 
            array (
                'created_at' => '2021-08-12 09:44:19',
                'id' => 31,
                'key' => 'browse_restaurants',
                'table_name' => 'restaurants',
                'updated_at' => '2021-08-12 09:44:19',
            ),
            31 => 
            array (
                'created_at' => '2021-08-12 09:44:19',
                'id' => 32,
                'key' => 'read_restaurants',
                'table_name' => 'restaurants',
                'updated_at' => '2021-08-12 09:44:19',
            ),
            32 => 
            array (
                'created_at' => '2021-08-12 09:44:19',
                'id' => 33,
                'key' => 'edit_restaurants',
                'table_name' => 'restaurants',
                'updated_at' => '2021-08-12 09:44:19',
            ),
            33 => 
            array (
                'created_at' => '2021-08-12 09:44:19',
                'id' => 34,
                'key' => 'add_restaurants',
                'table_name' => 'restaurants',
                'updated_at' => '2021-08-12 09:44:19',
            ),
            34 => 
            array (
                'created_at' => '2021-08-12 09:44:19',
                'id' => 35,
                'key' => 'delete_restaurants',
                'table_name' => 'restaurants',
                'updated_at' => '2021-08-12 09:44:19',
            ),
            35 => 
            array (
                'created_at' => '2021-08-12 09:52:26',
                'id' => 36,
                'key' => 'browse_clients',
                'table_name' => 'clients',
                'updated_at' => '2021-08-12 09:52:26',
            ),
            36 => 
            array (
                'created_at' => '2021-08-12 09:52:26',
                'id' => 37,
                'key' => 'read_clients',
                'table_name' => 'clients',
                'updated_at' => '2021-08-12 09:52:26',
            ),
            37 => 
            array (
                'created_at' => '2021-08-12 09:52:26',
                'id' => 38,
                'key' => 'edit_clients',
                'table_name' => 'clients',
                'updated_at' => '2021-08-12 09:52:26',
            ),
            38 => 
            array (
                'created_at' => '2021-08-12 09:52:26',
                'id' => 39,
                'key' => 'add_clients',
                'table_name' => 'clients',
                'updated_at' => '2021-08-12 09:52:26',
            ),
            39 => 
            array (
                'created_at' => '2021-08-12 09:52:26',
                'id' => 40,
                'key' => 'delete_clients',
                'table_name' => 'clients',
                'updated_at' => '2021-08-12 09:52:26',
            ),
            40 => 
            array (
                'created_at' => '2021-08-12 10:08:36',
                'id' => 41,
                'key' => 'browse_orders',
                'table_name' => 'orders',
                'updated_at' => '2021-08-12 10:08:36',
            ),
            41 => 
            array (
                'created_at' => '2021-08-12 10:08:36',
                'id' => 42,
                'key' => 'read_orders',
                'table_name' => 'orders',
                'updated_at' => '2021-08-12 10:08:36',
            ),
            42 => 
            array (
                'created_at' => '2021-08-12 10:08:36',
                'id' => 43,
                'key' => 'edit_orders',
                'table_name' => 'orders',
                'updated_at' => '2021-08-12 10:08:36',
            ),
            43 => 
            array (
                'created_at' => '2021-08-12 10:08:36',
                'id' => 44,
                'key' => 'add_orders',
                'table_name' => 'orders',
                'updated_at' => '2021-08-12 10:08:36',
            ),
            44 => 
            array (
                'created_at' => '2021-08-12 10:08:36',
                'id' => 45,
                'key' => 'delete_orders',
                'table_name' => 'orders',
                'updated_at' => '2021-08-12 10:08:36',
            ),
            45 => 
            array (
                'created_at' => '2021-08-12 10:10:28',
                'id' => 46,
                'key' => 'browse_categories',
                'table_name' => 'categories',
                'updated_at' => '2021-08-12 10:10:28',
            ),
            46 => 
            array (
                'created_at' => '2021-08-12 10:10:28',
                'id' => 47,
                'key' => 'read_categories',
                'table_name' => 'categories',
                'updated_at' => '2021-08-12 10:10:28',
            ),
            47 => 
            array (
                'created_at' => '2021-08-12 10:10:28',
                'id' => 48,
                'key' => 'edit_categories',
                'table_name' => 'categories',
                'updated_at' => '2021-08-12 10:10:28',
            ),
            48 => 
            array (
                'created_at' => '2021-08-12 10:10:28',
                'id' => 49,
                'key' => 'add_categories',
                'table_name' => 'categories',
                'updated_at' => '2021-08-12 10:10:28',
            ),
            49 => 
            array (
                'created_at' => '2021-08-12 10:10:28',
                'id' => 50,
                'key' => 'delete_categories',
                'table_name' => 'categories',
                'updated_at' => '2021-08-12 10:10:28',
            ),
            50 => 
            array (
                'created_at' => '2021-08-12 10:13:26',
                'id' => 51,
                'key' => 'browse_plates',
                'table_name' => 'plates',
                'updated_at' => '2021-08-12 10:13:26',
            ),
            51 => 
            array (
                'created_at' => '2021-08-12 10:13:26',
                'id' => 52,
                'key' => 'read_plates',
                'table_name' => 'plates',
                'updated_at' => '2021-08-12 10:13:26',
            ),
            52 => 
            array (
                'created_at' => '2021-08-12 10:13:26',
                'id' => 53,
                'key' => 'edit_plates',
                'table_name' => 'plates',
                'updated_at' => '2021-08-12 10:13:26',
            ),
            53 => 
            array (
                'created_at' => '2021-08-12 10:13:26',
                'id' => 54,
                'key' => 'add_plates',
                'table_name' => 'plates',
                'updated_at' => '2021-08-12 10:13:26',
            ),
            54 => 
            array (
                'created_at' => '2021-08-12 10:13:26',
                'id' => 55,
                'key' => 'delete_plates',
                'table_name' => 'plates',
                'updated_at' => '2021-08-12 10:13:26',
            ),
        ));
        
        
    }
}