<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Storages a description of sheets.
 */
class CreateSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index();
            $table->string('name');
            $table->unsignedTinyInteger('height')->default(0);
            $table->unsignedTinyInteger('width')->default(0);
            $table->text('properties')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sheets');
    }
}
