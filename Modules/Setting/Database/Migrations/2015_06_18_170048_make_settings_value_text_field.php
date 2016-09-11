<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MakeSettingsValueTextField extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('setting__settings', function (Blueprint $table) {
            $table->text('plainValue')->string('plainValue')->change();
        });
        Schema::table('setting__setting_translations', function (Blueprint $table) {
            $table->text('value')->string('value')->change();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('setting__settings', function (Blueprint $table) {
            $table->string('plainValue')->text('plainValue')->change();
        });
        Schema::table('setting__setting_translations', function (Blueprint $table) {
            $table->string('value')->text('value')->change();
        });
    }
}
