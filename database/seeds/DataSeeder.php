<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\User as User;
use App\Group as Group;
use App\Country as Country;
use App\SurveyQuestion as Question;
use App\SurveyAnswer as Answer;

class DataSeeder extends Seeder {
    
    public function run() {


        # ======================================================================
        # Seed <users> table
        # ======================================================================
        $user = User::updateOrCreate([
            'email' => 'nik.sumeiko@gmail.com'
        ], [
            'first_name' => 'System',
            'last_name'  => 'User',
            'email'      => 'nik.sumeiko@gmail.com',
            'password'   => Hash::make('123456')
        ]);


        # ======================================================================
        # Seed <group_relations> table
        # ======================================================================

        /*$stratos->groups()->attach($shishaClass->id, ['relation' => 'coach']);
        //$stratos->groups()->attach($yogaClass->id, ['relation' => 'attendee']);
        $stratos->groups()->attach($noNameClass->id, ['relation' => 'attendee']);

        //$nik->groups()->attach($yogaClass->id, ['relation' => 'coach']);
        $nik->groups()->attach($noNameClass->id, ['relation' => 'attendee']);

        $andrei->groups()->attach($noNameClass->id, ['relation' => 'coach']);
        //$andrei->groups()->attach($yogaClass->id, ['relation' => 'attendee']);
        $andrei->groups()->attach($shishaClass->id, ['relation' => 'attendee']);


        $this->command->info('Created class relations.');*/


        # ======================================================================
        # Seed <survey_questions> table
        # ======================================================================
        $q1 = Question::firstOrCreate([
            'question' => 'Do you have any other type of schedule than standard weekly repeatable sessions?',
            'answer_options' => '',
            'venues' => '',
            'targets' => '',
            'status' => 1,
        ]);

        $q2 = Question::firstOrCreate([
            'question' => 'Do you have multiple locations where you run classes?',
            'answer_options' => '',
            'venues' => '',
            'targets' => '',
            'status' => 1,
        ]);

        $q3 = Question::firstOrCreate([
            'question' => 'How many monthly attendees you have on average?',
            'answer_options' => '',
            'venues' => '',
            'targets' => '',
            'status' => 1,
        ]);


        # ======================================================================
        # Seed <survey_answers> table
        # ======================================================================
        Answer::firstOrCreate([
            'question_id' => $q1->id,
            'user_id' => $user->id,
            'answer' => 'Answer to question #'.$q1->id
        ]);

        Answer::firstOrCreate([
            'question_id' => $q2->id,
            'user_id' => $user->id,
            'answer' => 'Answer to question #'.$q2->id
        ]);

        Answer::firstOrCreate([
            'question_id' => $q3->id,
            'user_id' => $user->id,
            'answer' => 'Answer to question #'.$q3->id
        ]);

    }

}