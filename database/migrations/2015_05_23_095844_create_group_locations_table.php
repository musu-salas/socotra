<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_locations', function(Blueprint $table)
		{
			$table->increments('id');

            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups');

            $table->integer('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries');

			$table->string('address_line_1', 255)->nullable();
			$table->string('address_line_2', 255)->nullable();
			$table->string('city', 255)->nullable();
			$table->string('state', 255)->nullable();
			$table->string('zip', 15)->nullable();
			$table->string('latlng', 255)->nullable();
            $table->string('how_to_find', 255);
			
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('group_locations');
	}

}
