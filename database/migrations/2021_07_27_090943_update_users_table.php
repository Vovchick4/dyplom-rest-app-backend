<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('restaurant_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('no action')
                ->after('id');
            $table->string('lastname', 255)
                ->nullable()
                ->after('name');
            $table->string('image', 255)
                ->nullable()
                ->after('lastname');
            $table->enum('role', array('owner', 'admin'))
                ->after('image');
        });
    }

    public function down()
    {
    }
}
