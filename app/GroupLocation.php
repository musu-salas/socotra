<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\GroupPricing;
use App\Country;

class GroupLocation extends Model {

    protected $fillable = [
        'group_id',
        'country_id',
        'address_line_1',
        'address_line_2',
        'city',
        'district',
        'state',
        'zip',
        'latlng',
        'how_to_find',
        'currency_id'
    ];


    protected $casts = [
        'id' => 'integer',
        'group_id' => 'integer',
        'country_id' => 'integer',
        'currency_id' => 'integer'
    ];


    protected $appends = [
        'country'
    ];


    public function group() {
        return $this->belongsTo('App\Group');
    }


    public function pricings() {
        return $this->belongsToMany(
            'App\GroupPricing',
            'group_pricings_locations',
            'group_location_id',
            'group_pricing_id'

        )->withPivot('price')->withTimestamps();
    }


    public function getCountryAttribute() {
        return Country::find($this->country_id);
    }


    public function getCurrencyAttribute() {
        return Currency::find($this->currency_id);
    }


    public function getFullAddressAttribute() {
        $str = $this->address_line_1 . ', ';

        if ($this->address_line_2) {
            $str .= $this->address_line_2 . ', ';
        }

        $str .= $this->zip . ' ' . $this->city;

        if ($this->state) {
            $str .= ', ' . $this->state;
        }

        return $str;
    }


    public function getIsFullAttribute() {
        return $this->address_line_1 && $this->city && $this->zip && $this->country_id;
    }


    public function getKeywordsAttribute() {
        $group = $this->group;
        $keywords = [$group->creative_field1];

        if ($group->creative_field2) {
            array_push($keywords, $group->creative_field2);
        }

        if ($this->is_full) {
            array_push($keywords, $this->city/*, $this->zip*/);

            if ($this->district) {
                array_push($keywords, $this->district);
            }

            if ($this->state) {
                array_push($keywords, $this->state);
            }

            array_push($keywords, $this->country->name);
        }

        return $keywords;
    }
}