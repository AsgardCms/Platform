<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'file_translations',
            function (Blueprint $table) {
                $table->increments('id');
                $table->integer('file_id')->unsigned();
                $table->string('locale')->index();
                $table->string('description');
                $table->string('alt_attribute');
                $table->string('keywords');
                $table->unique(['file_id', 'locale']);
                $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('file_translations');
    }

}
