<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->onDelete('restrict')
                ->onUpdate('no action');
            $table->foreignId('restaurant_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->string('name', 255);
            $table->string('image', 255);
            $table->boolean('active')
                ->default(1);
            $table->timestamps();

            $table->unique(['name', 'restaurant_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
