<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('code')
                ->unsigned();
            $table->foreignId('restaurant_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreignId('client_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null')
                ->onUpdate('no action');
            $table->enum('status', array('new', 'viewed', 'in_process', 'completed', 'canceled'))
                ->default('new');
            $table->enum('payment_status', array('pending', 'paid', 'not_paid'))
                ->default('pending');
            $table->string('table', 255)
                ->nullable();
            $table->string('name', 255)
                ->nullable();
            $table->float('price', 8, 2)
                ->default(0);
            $table->integer('person_quantity')
                ->default('1');
            $table->integer('people_for_quantity')
                ->default('1');
            $table->boolean('is_takeaway')
                ->default(0);
            $table->boolean('is_online_payment')
                ->default(0);
            $table->enum('payment_method', array('cash', 'paypal', 'google', 'apple'))
                ->default('cash');
            $table->string('payment_id', 255)
                ->nullable();
            $table->json('payment_response')
                ->nullable();
            $table->timestamps();

            $table->unique(['code', 'restaurant_id']);
            $table->unique(['payment_id']);
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('orders');
        Schema::enableForeignKeyConstraints();
    }
}
