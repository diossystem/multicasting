<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Pages of the application.
 *
 * Storages instances of pages of the application.
 */
class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->timestamp('published_at')->nullable()->index();
            $table->string('state')->default('draft')->index();
            $table->string('slug')->nullable()->index();
            $table->string('title')->index();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('description_tag')->nullable();
            $table->string('keywords_tag')->nullable();
            $table->unsignedInteger('template_id')->nullable()->index();

            $table->index(['state', 'link']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
