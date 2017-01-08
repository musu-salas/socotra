<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePioneersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pioneers', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('location')->nullable();

            $table->string('creative_field1', 255)->nullable();
            $table->string('creative_field2', 255)->nullable();

            $table->string('website')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pioneers');
    }
}
