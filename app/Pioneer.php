<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Pioneer extends Model {
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'location',
        'creative_field1',
        'creative_field2',
        'website'
    ];

}
