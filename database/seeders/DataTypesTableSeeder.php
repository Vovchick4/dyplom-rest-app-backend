<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'controller' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"desc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Users',
                'display_name_singular' => 'User',
                'generate_permissions' => 1,
                'icon' => 'voyager-person',
                'id' => 1,
                'model_name' => 'App\\Models\\User',
                'name' => 'users',
                'policy_name' => NULL,
                'server_side' => 1,
                'slug' => 'users',
                'updated_at' => '2021-08-12 14:06:51',
            ),
            1 => 
            array (
                'controller' => '',
                'created_at' => '2021-08-12 08:47:39',
                'description' => '',
                'details' => NULL,
                'display_name_plural' => 'Menus',
                'display_name_singular' => 'Menu',
                'generate_permissions' => 1,
                'icon' => 'voyager-list',
                'id' => 2,
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'name' => 'menus',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'menus',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            2 => 
            array (
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'created_at' => '2021-08-12 08:47:39',
                'description' => '',
                'details' => NULL,
                'display_name_plural' => 'Roles',
                'display_name_singular' => 'Role',
                'generate_permissions' => 1,
                'icon' => 'voyager-lock',
                'id' => 3,
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'name' => 'roles',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'roles',
                'updated_at' => '2021-08-12 08:47:39',
            ),
            3 => 
            array (
                'controller' => NULL,
                'created_at' => '2021-08-12 09:33:56',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Admins',
                'display_name_singular' => 'Admin',
                'generate_permissions' => 1,
                'icon' => 'voyager-person',
                'id' => 4,
                'model_name' => 'App\\Models\\Admin',
                'name' => 'admins',
                'policy_name' => NULL,
                'server_side' => 0,
                'slug' => 'admins',
                'updated_at' => '2021-08-13 09:24:19',
            ),
            4 => 
            array (
                'controller' => NULL,
                'created_at' => '2021-08-12 09:44:19',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Restaurants',
                'display_name_singular' => 'Restaurant',
                'generate_permissions' => 1,
                'icon' => 'voyager-shop',
                'id' => 5,
                'model_name' => 'App\\Models\\Restaurant',
                'name' => 'restaurants',
                'policy_name' => NULL,
                'server_side' => 1,
                'slug' => 'restaurants',
                'updated_at' => '2021-08-13 11:09:18',
            ),
            5 => 
            array (
                'controller' => NULL,
                'created_at' => '2021-08-12 09:52:26',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Clients',
                'display_name_singular' => 'Client',
                'generate_permissions' => 1,
                'icon' => 'voyager-group',
                'id' => 6,
                'model_name' => 'App\\Models\\Client',
                'name' => 'clients',
                'policy_name' => NULL,
                'server_side' => 1,
                'slug' => 'clients',
                'updated_at' => '2021-08-13 11:06:18',
            ),
            6 => 
            array (
                'controller' => NULL,
                'created_at' => '2021-08-12 10:08:36',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Orders',
                'display_name_singular' => 'Order',
                'generate_permissions' => 1,
                'icon' => 'voyager-basket',
                'id' => 7,
                'model_name' => 'App\\Models\\Order',
                'name' => 'orders',
                'policy_name' => NULL,
                'server_side' => 1,
                'slug' => 'orders',
                'updated_at' => '2021-08-13 08:47:12',
            ),
            7 => 
            array (
                'controller' => NULL,
                'created_at' => '2021-08-12 10:10:28',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Categories',
                'display_name_singular' => 'Category',
                'generate_permissions' => 1,
                'icon' => 'voyager-categories',
                'id' => 8,
                'model_name' => 'App\\Models\\Category',
                'name' => 'categories',
                'policy_name' => NULL,
                'server_side' => 1,
                'slug' => 'categories',
                'updated_at' => '2021-08-13 07:49:53',
            ),
            8 => 
            array (
                'controller' => NULL,
                'created_at' => '2021-08-12 10:13:26',
                'description' => NULL,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'display_name_plural' => 'Plates',
                'display_name_singular' => 'Plate',
                'generate_permissions' => 1,
                'icon' => 'voyager-pizza',
                'id' => 9,
                'model_name' => 'App\\Models\\Plate',
                'name' => 'plates',
                'policy_name' => NULL,
                'server_side' => 1,
                'slug' => 'plates',
                'updated_at' => '2021-08-13 08:03:18',
            ),
        ));
        
        
    }
}