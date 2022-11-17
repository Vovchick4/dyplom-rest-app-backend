<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MenuItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('menu_items')->delete();
        
        \DB::table('menu_items')->insert(array (
            0 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-boat',
                'id' => 1,
                'menu_id' => 1,
                'order' => 1,
                'parameters' => NULL,
                'parent_id' => NULL,
                'route' => 'voyager.dashboard',
                'target' => '_self',
                'title' => 'Dashboard',
                'updated_at' => '2021-08-12 08:47:39',
                'url' => '',
            ),
            1 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-images',
                'id' => 2,
                'menu_id' => 1,
                'order' => 4,
                'parameters' => NULL,
                'parent_id' => NULL,
                'route' => 'voyager.media.index',
                'target' => '_self',
                'title' => 'Media',
                'updated_at' => '2021-08-12 09:46:12',
                'url' => '',
            ),
            2 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-person',
                'id' => 3,
                'menu_id' => 1,
                'order' => 2,
                'parameters' => NULL,
                'parent_id' => 17,
                'route' => 'voyager.users.index',
                'target' => '_self',
                'title' => 'Users',
                'updated_at' => '2021-08-12 11:16:56',
                'url' => '',
            ),
            3 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-lock',
                'id' => 4,
                'menu_id' => 1,
                'order' => 2,
                'parameters' => NULL,
                'parent_id' => NULL,
                'route' => 'voyager.roles.index',
                'target' => '_self',
                'title' => 'Roles',
                'updated_at' => '2021-08-12 08:47:39',
                'url' => '',
            ),
            4 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-tools',
                'id' => 5,
                'menu_id' => 1,
                'order' => 5,
                'parameters' => NULL,
                'parent_id' => NULL,
                'route' => NULL,
                'target' => '_self',
                'title' => 'Tools',
                'updated_at' => '2021-08-12 09:46:12',
                'url' => '',
            ),
            5 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-list',
                'id' => 6,
                'menu_id' => 1,
                'order' => 1,
                'parameters' => NULL,
                'parent_id' => 5,
                'route' => 'voyager.menus.index',
                'target' => '_self',
                'title' => 'Menu Builder',
                'updated_at' => '2021-08-12 08:48:32',
                'url' => '',
            ),
            6 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-data',
                'id' => 7,
                'menu_id' => 1,
                'order' => 2,
                'parameters' => NULL,
                'parent_id' => 5,
                'route' => 'voyager.database.index',
                'target' => '_self',
                'title' => 'Database',
                'updated_at' => '2021-08-12 08:48:32',
                'url' => '',
            ),
            7 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-compass',
                'id' => 8,
                'menu_id' => 1,
                'order' => 3,
                'parameters' => NULL,
                'parent_id' => 5,
                'route' => 'voyager.compass.index',
                'target' => '_self',
                'title' => 'Compass',
                'updated_at' => '2021-08-12 08:48:32',
                'url' => '',
            ),
            8 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-bread',
                'id' => 9,
                'menu_id' => 1,
                'order' => 4,
                'parameters' => NULL,
                'parent_id' => 5,
                'route' => 'voyager.bread.index',
                'target' => '_self',
                'title' => 'BREAD',
                'updated_at' => '2021-08-12 08:48:32',
                'url' => '',
            ),
            9 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 08:47:39',
                'icon_class' => 'voyager-settings',
                'id' => 10,
                'menu_id' => 1,
                'order' => 6,
                'parameters' => NULL,
                'parent_id' => NULL,
                'route' => 'voyager.settings.index',
                'target' => '_self',
                'title' => 'Settings',
                'updated_at' => '2021-08-12 09:46:12',
                'url' => '',
            ),
            10 => 
            array (
                'color' => '#000000',
                'created_at' => '2021-08-12 09:33:56',
                'icon_class' => 'voyager-person',
                'id' => 11,
                'menu_id' => 1,
                'order' => 3,
                'parameters' => 'null',
                'parent_id' => NULL,
                'route' => 'voyager.admins.index',
                'target' => '_self',
                'title' => 'Admins',
                'updated_at' => '2021-08-12 09:46:12',
                'url' => '',
            ),
            11 => 
            array (
                'color' => '#000000',
                'created_at' => '2021-08-12 09:44:19',
                'icon_class' => 'voyager-shop',
                'id' => 12,
                'menu_id' => 1,
                'order' => 1,
                'parameters' => 'null',
                'parent_id' => 17,
                'route' => 'voyager.restaurants.index',
                'target' => '_self',
                'title' => 'Restaurants',
                'updated_at' => '2021-08-12 11:16:47',
                'url' => '',
            ),
            12 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 09:52:26',
                'icon_class' => 'voyager-group',
                'id' => 13,
                'menu_id' => 1,
                'order' => 6,
                'parameters' => NULL,
                'parent_id' => 17,
                'route' => 'voyager.clients.index',
                'target' => '_self',
                'title' => 'Clients',
                'updated_at' => '2021-08-12 11:22:56',
                'url' => '',
            ),
            13 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 10:08:36',
                'icon_class' => 'voyager-basket',
                'id' => 14,
                'menu_id' => 1,
                'order' => 3,
                'parameters' => NULL,
                'parent_id' => 17,
                'route' => 'voyager.orders.index',
                'target' => '_self',
                'title' => 'Orders',
                'updated_at' => '2021-08-12 11:17:02',
                'url' => '',
            ),
            14 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 10:10:28',
                'icon_class' => 'voyager-categories',
                'id' => 15,
                'menu_id' => 1,
                'order' => 4,
                'parameters' => NULL,
                'parent_id' => 17,
                'route' => 'voyager.categories.index',
                'target' => '_self',
                'title' => 'Categories',
                'updated_at' => '2021-08-12 11:17:06',
                'url' => '',
            ),
            15 => 
            array (
                'color' => NULL,
                'created_at' => '2021-08-12 10:13:26',
                'icon_class' => 'voyager-pizza',
                'id' => 16,
                'menu_id' => 1,
                'order' => 5,
                'parameters' => NULL,
                'parent_id' => 17,
                'route' => 'voyager.plates.index',
                'target' => '_self',
                'title' => 'Plates',
                'updated_at' => '2021-08-12 11:17:08',
                'url' => '',
            ),
            16 => 
            array (
                'color' => '#000000',
                'created_at' => '2021-08-12 11:16:26',
                'icon_class' => 'voyager-folder',
                'id' => 17,
                'menu_id' => 1,
                'order' => 7,
                'parameters' => '',
                'parent_id' => NULL,
                'route' => NULL,
                'target' => '_self',
                'title' => 'Content',
                'updated_at' => '2021-08-12 11:22:44',
                'url' => '',
            ),
        ));
        
        
    }
}