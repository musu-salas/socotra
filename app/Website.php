<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Website extends Model {

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'website'
    ];

    protected $casts = [
        'user_id' => 'integer'
    ];
}
