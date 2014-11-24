<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuitemTranslationTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menuitem_translations', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('menuitem_id')->unsigned();
			$table->string('locale')->index();

			$table->tinyInteger('status')->default(0);
			$table->string('title');
			$table->string('url');
			$table->string('uri');

			$table->unique(['menuitem_id', 'locale']);
			$table->foreign('menuitem_id')->references('id')->on('menuitems')->onDelete('cascade');
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
		Schema::drop('menuitem_translations');
	}

}
