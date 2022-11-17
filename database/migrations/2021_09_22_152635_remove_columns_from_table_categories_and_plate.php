<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromTableCategoriesAndPlate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(["name", "restaurant_id"]);
            $table->dropColumn('name');
        });

        Schema::table('plates', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name', 255);
            $table->unique(["name", "restaurant_id"]);
        });

        Schema::table('plates', function (Blueprint $table) {
            $table->string('name', 255);
            $table->text('description')->nullable();
        });
    }
}
