<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateIconColumnOnMenuitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu__menuitems', function (Blueprint $table) {
            $table->string('icon')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu__menuitems', function (Blueprint $table) {
            $table->string('icon')->default('')->nullable(false)->change();
        });
    }
}
