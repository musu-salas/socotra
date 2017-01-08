<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistrictToGroupLocations extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('group_locations', function (Blueprint $table) {
            $table->string('district', 255)->nullable();
            $table->index('district');

            $table->string('how_to_find', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('group_locations', function (Blueprint $table) {
            $table->dropIndex('group_locations_district_index');
            $table->dropColumn('district');

            $table->string('how_to_find', 255)->change();
        });
    }
}
