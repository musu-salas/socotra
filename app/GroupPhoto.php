<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupPhoto extends Model {

    protected $fillable = [
        'group_id',
        'hash',
        'ext',
        'original_width',
        'original_height',
        'large_width',
        'large_height',
        'thumbnail_width',
        'thumbnail_height'
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    protected $casts = [
        'id' => 'integer',
        'original_width' => 'integer',
        'original_height' => 'integer',
        'large_width' => 'integer',
        'large_height' => 'integer',
        'thumbnail_width' => 'integer',
        'thumbnail_height' => 'integer'
    ];


    public function group() {
        return $this->belongsTo('App\Group');
    }


    public function getLargeSrcAttribute() {
        return config('custom.aws.s3_bucket_link') . config('custom.aws.class.photos_path') . $this->hash . '_l.' . $this->ext;
    }


    public function getThumbnailSrcAttribute() {
        return config('custom.aws.s3_bucket_link') . config('custom.aws.class.photos_path') . $this->hash . '_t.' . $this->ext;
    }
}
