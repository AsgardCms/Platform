<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
            $table->increments('id');
		    $table->string('filename');
		    $table->string('path');
		    $table->string('extension');
		    $table->string('mimetype');
		    $table->string('width');
		    $table->string('height');
		    $table->string('filesize');
		    $table->integer('folder_id')->unsigned();
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
		Schema::drop('files');
	}

}
