<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Additional fields of templates.
 */
class CreateAdditionalFieldTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_field_template', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('template_id')->index();
            $table->unsignedInteger('additional_field_id')->index();
            $table->boolean('important')->default(true);
            $table->boolean('primary')->default(false);
            $table->boolean('required')->default(false);
            $table->boolean('active')->default(true)->index();

            $table->unique(['template_id', 'additional_field_id'], 'unique_af_of_template');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_field_template');
    }
}
