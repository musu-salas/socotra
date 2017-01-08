<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupAmenity extends Model {

    protected $fillable = [
        'group_id',
        'name'
    ];

    protected $casts = [
        'id' => 'integer',
        'group_id' => 'integer'
    ];


    public function group()
    {
        return $this->belongsTo('App\Group');
    }

}