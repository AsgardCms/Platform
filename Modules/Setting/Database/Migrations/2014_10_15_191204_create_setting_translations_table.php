<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('setting_translations', function(Blueprint $table)
		{
            $table->increments('id');
			$table->integer('setting_id')->unsigned();
			$table->string('locale')->index();
			$table->string('value');
			$table->text('description');
			$table->unique(['setting_id','locale']);
			$table->foreign('setting_id')->references('id')->on('settings')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('setting_translations');
	}

}
