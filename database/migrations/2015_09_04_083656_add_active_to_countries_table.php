<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Country;

class AddActiveToCountriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('active')->default(false);
            $table->index('active');
            $table->index('name');
        });

        if (Country::count()) {
            Artisan::call('db:seed', array('--class' => 'CountriesTableSeeder'));
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn('active');
            $table->dropIndex(['name']);
        });
    }
}
