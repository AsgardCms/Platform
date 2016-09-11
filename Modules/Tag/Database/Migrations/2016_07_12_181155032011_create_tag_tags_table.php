<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTagsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('tag__tags', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('namespace');
            $table->timestamps();
        });

        Schema::create('tag__tagged', function (Blueprint $table) {
            $table->increments('id');
            $table->string('taggable_type');
            $table->integer('taggable_id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->index(['taggable_type', 'taggable_id']);
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('tag__tags');
        Schema::drop('tag__tagged');
    }
}
