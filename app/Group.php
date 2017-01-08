<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\GroupPhoto as Photo;

class Group extends Model {

    protected $fillable = [
        'user_id',
        'creative_field1',
        'creative_field2',
        'title',
        'uvp',
        'description',
        'for_who'
    ];


    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'is_public' => 'boolean',
        'cover_photo_id' => 'integer',
        'cover_photo_offset' => 'integer'
    ];


    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
    }


    public function pricing() {
        return $this->hasMany('App\GroupPricing');
    }


    public function schedule() {
        return $this->hasMany('App\GroupSchedule');
    }


    public function amenities() {
        return $this->hasMany('App\GroupAmenity');
    }


    public function locations() {
        return $this->hasMany('App\GroupLocation');
    }


    public function photos() {
        return $this->hasMany('App\GroupPhoto');
    }


    public function getPhotosCountAttribute() {
        return count($this->photos);
    }


    public function getCoverPhotoAttribute() {
        if ($this->cover_photo_id) {
            return Photo::find($this->cover_photo_id);
        }

        if ($this->photos_count) {
            return $this->photos[0];
        }
    }


    public function getMenuAttribute() {
        $steps = 4;

        $hasPhotos = boolval($this->photos_count);
        $steps -= ($hasPhotos ? 1 : 0);

        $hasPricing = boolval(count($this->pricing));
        $steps -= ($hasPricing ? 1 : 0);

        $hasLocation = $this->locations()->first();
        $hasLocation = $hasLocation ? $hasLocation->is_full : null;
        $steps -= ($hasLocation ? 1 : 0);

        $hasOverview = $this->creative_field1 && $this->title;
        $steps -= ($hasOverview ? 1 : 0);

        $hasSchedule = boolval(count($this->schedule));

        if ($steps < 1 && !$this->is_public) {
            $this->is_public = true;
            $this->save();

        } elseif ($steps > 0 && $this->is_public) {
            $this->is_public = false;
            $this->save();
        }

        return (object) [
            'contact' => boolval($this->phone),
            'photos' => $hasPhotos,
            'pricing' => $hasPricing,
            'location' => $hasLocation,
            'overview' => $hasOverview,
            'schedule' => $hasSchedule,
            'steps_to_complete' => $steps
        ];
    }
}
