<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model {

	public $timestamps = false;

    protected $fillable = [
        'code',
        'symbol'
    ];

    protected $casts = [
        'id' => 'integer'
    ];
}
