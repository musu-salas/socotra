<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePricingStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_pricings_locations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('group_pricing_id')->unsigned();
            $table->foreign('group_pricing_id')->references('id')->on('group_pricings');

            $table->integer('group_location_id')->unsigned();
            $table->foreign('group_location_id')->references('id')->on('group_locations');

            $table->float('price')->default(0);

            $table->timestamps();
        });

        Schema::table('group_pricings', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropForeign('groups_currency_id_foreign');
            $table->dropColumn('currency_id');
            $table->dropColumn('annual_fee');
        });

        Schema::table('group_locations', function (Blueprint $table) {
            $table->integer('currency_id')->unsigned()->default(1);
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('group_pricings_locations');

        Schema::table('group_pricings', function (Blueprint $table) {
            $table->float('price')->default(0)->after('description');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->integer('currency_id')->unsigned()->default(1)->after('phone');
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->float('annual_fee')->default(0)->after('creative_field2');
        });

        Schema::table('group_locations', function (Blueprint $table) {
            $table->dropForeign('group_locations_currency_id_foreign');
            $table->dropColumn('currency_id');
        });
    }
}
