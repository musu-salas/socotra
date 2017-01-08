<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('group_photos', function(Blueprint $table)
		{
            $table->increments('id');

            $table->integer('group_id')->unsigned();
            $table->foreign('group_id')->references('id')->on('groups');

            $table->string('hash', 128);
            $table->string('ext', 16);

            $table->smallInteger('original_width');
            $table->smallInteger('original_height');
            $table->smallInteger('large_width')->nullable();
            $table->smallInteger('large_height')->nullable();
            $table->smallInteger('thumbnail_width')->nullable();
            $table->smallInteger('thumbnail_height')->nullable();

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
		Schema::drop('group_photos');
	}

}
