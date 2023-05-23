<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->string('name', 255);
            $table->string('number', 255);
            $table->string('cvv', 255);
            $table->date('expiration_date');
            $table->boolean('default')
                ->default(1);
            $table->timestamps();

            $table->unique(['client_id', 'number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
