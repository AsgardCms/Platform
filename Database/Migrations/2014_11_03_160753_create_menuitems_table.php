<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuitemsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menuitems', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('menu_id')->unsigned();
			$table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
			$table->integer('page_id')->unsigned();
			$table->string('target');
			$table->string('module_name');
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
		Schema::drop('menuitems');
	}

}
