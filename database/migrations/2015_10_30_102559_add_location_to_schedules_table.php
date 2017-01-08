<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_schedules', function (Blueprint $table) {
            $table->integer('location_id')->unsigned()->after('group_id');
            $table->foreign('location_id')->references('id')->on('group_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_schedules', function (Blueprint $table) {
            $table->dropForeign('group_schedules_location_id_foreign');
            $table->dropColumn('location_id');
        });
    }
}
