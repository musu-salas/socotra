<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupPricing extends Model {

    protected $fillable = [
        'group_id',
        'title',
        'description'
    ];

    protected $casts = [
        'id' => 'integer',
        'group_id' => 'integer'
    ];


    public function group() {
        return $this->belongsTo('App\Group');
    }

    public function locations() {
        return $this->belongsToMany(
            'App\GroupLocation',
            'group_pricings_locations',

            // TODO: Not sure whether ids order is right – currently opposite to what GroupLocation has.,
            'group_pricing_id',
            'group_location_id'

        )->withPivot('price')->withTimestamps();
    }
}