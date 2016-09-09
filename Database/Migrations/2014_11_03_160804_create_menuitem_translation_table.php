<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMenuitemTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu__menuitem_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('menuitem_id')->unsigned();
            $table->string('locale')->index();

            $table->tinyInteger('status')->default(0);
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('uri')->nullable();

            $table->unique(['menuitem_id', 'locale']);
            $table->foreign('menuitem_id')->references('id')->on('menu__menuitems')->onDelete('cascade');
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
        Schema::drop('menu__menuitem_translations');
    }
}
