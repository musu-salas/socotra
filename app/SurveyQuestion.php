<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model {

    protected $fillable = [
        'question',
        'answer_options',
        'venues',
        'targets',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'status' => 'integer'
    ];

}