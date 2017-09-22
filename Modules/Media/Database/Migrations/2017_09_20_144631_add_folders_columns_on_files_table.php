<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFoldersColumnsOnFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media__files', function (Blueprint $table) {
            $table->boolean('is_folder')->default(false)->after('id');

            $table->string('path')->nullable()->change();
            $table->string('extension')->nullable()->change();
            $table->string('mimetype')->nullable()->change();
            $table->string('filesize')->nullable()->change();
            $table->string('folder_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media__files', function (Blueprint $table) {
            $table->dropColumn('is_folder');

            $table->string('path')->nullable(false)->change();
            $table->string('extension')->nullable(false)->change();
            $table->string('mimetype')->nullable(false)->change();
            $table->string('filesize')->nullable(false)->change();
            $table->string('folder_id')->nullable(false)->change();
        });
    }
}
