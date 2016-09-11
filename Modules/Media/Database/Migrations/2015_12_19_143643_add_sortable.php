<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSortable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media__imageables', function (Blueprint $table) {
            $table->integer('order')->nullable()->after('zone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media__imageables', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
}
