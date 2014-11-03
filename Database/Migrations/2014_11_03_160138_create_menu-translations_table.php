<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTranslationsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu_translations', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('menu_id')->unsigned();
			$table->string('locale')->index();

			$table->tinyInteger('status')->default(0);
			$table->string('title');

			$table->unique(['menu_id', 'locale']);
			$table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
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
		Schema::drop('menu_translations');
	}

}
