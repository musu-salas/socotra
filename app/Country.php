<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model {

    public $timestamps = false;

    protected $fillable = [
        'alpha_2',
        'name'
    ];

    protected $casts = [
        'id' => 'integer'
    ];
}
