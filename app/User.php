<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Storage;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'location',
        'newsletter',
        'facebook_id'
    ];

    protected $casts = [
        'id' => 'integer'
    ];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
    protected $hidden = [
        'password',
        'remember_token',
    ];


	public function myGroups() {
        return $this->hasMany('App\Group');
    }

    public function websites() {
        return $this->hasMany('App\Website');
    }

    public function groups() {
        return $this->belongsToMany(
            'App\Group', 'group_relations', 'user_id', 'group_id'
        );
    }


    public function getFullnameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }


    public function getHasPasswordAttribute() {
        return $this->password ? true : false;
    }


    public function getLocationTextAttribute() {
        if(!$this->location) {
            return '';
        }

        $location = json_decode($this->location);
        $city = $location->city ? $location->city : null;
        $state = $location->state ? $location->state : null;

        if ($location->country) {
            $split = explode('|', $location->country);
            $country = count($split) > 1 ? $split[1] : null;
        } else {
            $country = null;
        }

        return implode(', ', array_filter([$city, $state, $country]));
    }


    public function getIsExternalAvatarAttribute() {
        if (!$this->avatar) {
            return false;
        }

        return (bool) preg_match('/^http/', $this->avatar);
    }


    public function getAvatarSrcAttribute() {
        if ($this->avatar) {
            if ($this->isExternalAvatar) {
                return $this->avatar;
            }

            return config('custom.aws.s3_bucket_link') . config('custom.aws.user.avatar.path') . $this->avatar;
        }

        return url('images/default-avatar_v1.0.0.jpg');
    }
}
