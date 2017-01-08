<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('survey_questions', function(Blueprint $table)
		{
            $table->increments('id');

            $table->string('question', 140);
            $table->string('answer_options', 90)->nullable();
            $table->text('venues')->nullable();
            $table->text('targets')->nullable();
            $table->boolean('status'); // 1 or 0

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
		Schema::drop('survey_questions');
	}

}
