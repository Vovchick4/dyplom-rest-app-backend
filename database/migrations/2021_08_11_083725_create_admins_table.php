<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            $table->foreignId('role_id')->nullable()->constrained();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('avatar')->nullable()->default('users/default.png');
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->text('settings')->nullable()->default(null);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
