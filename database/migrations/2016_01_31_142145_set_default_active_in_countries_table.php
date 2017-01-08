<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetDefaultActiveInCountriesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('active')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('active')->default(false)->change();
        });
    }
}
