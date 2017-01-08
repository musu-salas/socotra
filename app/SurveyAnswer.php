<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model {

    protected $fillable = [
        'question_id',
        'user_id',
        'answer'
    ];

    protected $casts = [
        'id' => 'integer',
        'question_id' => 'integer',
        'user_id' => 'integer'
    ];


    public function question()
    {
        return $this->belongsTo('App\SurveyQuestion');
    }

}