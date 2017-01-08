<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupSchedule extends Model {

    protected $fillable = [
        'group_id',
        'location_id',
        'title',
        'description',
        'starts',
        'ends',
        'week_day'
    ];


    protected $casts = [
        'id' => 'integer',
        'group_id' => 'integer',
        'location_id' => 'integer',
        'week_day' => 'integer'
    ];


    public function group() {
        return $this->belongsTo('App\Group');
    }


    public function location() {
        return $this->belongsTo('App\GroupLocation');
    }
}