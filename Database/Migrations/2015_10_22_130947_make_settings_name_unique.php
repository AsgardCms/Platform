<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakeSettingsNameUnique extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('setting__settings', function (Blueprint $table) {
            $table->unique('name', 'setting__settings_name_unique');
            $table->index('name', 'setting__settings_name_index');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('setting__settings', function (Blueprint $table) {
            $table->dropUnique('setting__settings_name_unique');
            $table->dropIndex('setting__settings_name_index');
        });
    }
}
