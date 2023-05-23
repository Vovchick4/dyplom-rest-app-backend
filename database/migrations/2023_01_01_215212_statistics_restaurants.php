<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StatisticsRestaurants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreignId('client_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->enum('review', array('Excellent', 'Good', 'Satisfactory', 'Poor', 'Bad'))->default('Excellent');
            $table->text('comment')->nullable();
            $table->integer("rest_id")->nullable();
            $table->integer("user_id")->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('statistics_restaurants');
        Schema::enableForeignKeyConstraints();
    }
}
