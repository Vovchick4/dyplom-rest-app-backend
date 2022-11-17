<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOrderPlateTable extends Migration
{
    public function up()
    {
        Schema::create('order_plate', function (Blueprint $table) {
            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreignId('plate_id')
                ->constrained()
                ->onDelete('restrict')
                ->onUpdate('no action');
            $table->float('price');
            $table->integer('amount')
                ->default('1');
            $table->text('comment')
                ->nullable();
            $table->timestamps();

            $table->primary(['order_id', 'plate_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_plate');
    }
}
