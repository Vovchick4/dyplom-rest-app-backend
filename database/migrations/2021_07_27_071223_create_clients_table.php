<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)
                ->unique()
                ->nullable();
            $table->string('phone', 255)
                ->unique()
                ->nullable();
            $table->string('fb_id')->nullable();
            $table->string('google_id')->nullable();
            $table->string('password', 255);
            $table->timestamp('verified_at')
                ->nullable();
            $table->enum('payment_method', array('cash', 'paypal', 'google', 'apple'))
                ->default('cash');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
