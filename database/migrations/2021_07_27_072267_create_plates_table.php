<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlatesTable extends Migration
{
    public function up()
    {
        Schema::create('plates', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')
                ->nullable();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null')
                ->onUpdate('no action');
            $table->foreignId('restaurant_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->string('image', 255);
            $table->float('price', 8, 2);
            $table->boolean('active')
                ->default(1);
            $table->integer('quantity')
                ->default('1');
            $table->string('weight', 255)
                ->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('plates');
        Schema::enableForeignKeyConstraints();
    }
}
