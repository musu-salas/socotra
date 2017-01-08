<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('groups', function(Blueprint $table)
		{
			$table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');

            $table->boolean('is_public')->default(false);

            $table->text('uvp')->nullable();
			$table->text('description')->nullable();

            $table->integer('cover_photo_id')->nullable();

            $table->string('phone', 255)->nullable();

            $table->integer('currency_id')->unsigned()->default(1);
            $table->foreign('currency_id')->references('id')->on('currencies');

            $table->string('creative_field1', 255)->nullable();
            $table->string('creative_field2', 255)->nullable();

            $table->float('annual_fee')->default(0);
            $table->text('for_who')->nullable();

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
		Schema::drop('groups');
	}

}
