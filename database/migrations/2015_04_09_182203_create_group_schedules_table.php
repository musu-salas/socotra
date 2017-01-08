<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_schedules', function(Blueprint $table)
		{
			$table->increments('id');

            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups');

			$table->string('title', 255);
			$table->text('description')->nullable();
			$table->string('starts', 5)->nullable();
			$table->string('ends', 5)->nullable();
			$table->tinyInteger('week_day');
			
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
		Schema::drop('group_schedules');
	}

}
