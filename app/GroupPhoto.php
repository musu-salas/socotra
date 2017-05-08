<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupPhoto extends Model {
    protected $fillable = [
        'group_id',
        'hash',
        'ext',
        'large_width',
        'large_height',
        'thumbnail_width',
        'thumbnail_height'
    ];

    protected $hidden = [
        'group',
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'original_src',
        'large_src',
        'thumbnail_src',
        'is_cover'
    ];

    protected $casts = [
        'id' => 'integer',
        'large_width' => 'integer',
        'large_height' => 'integer',
        'thumbnail_width' => 'integer',
        'thumbnail_height' => 'integer',
        'is_cover' => 'boolean'
    ];

    /** Relationships */
    public function group() {
        return $this->belongsTo('App\Group');
    }

    /** Accessors */
    public function getIsCoverAttribute() {
        return $this->group->cover_photo_id === $this->id;
    }

    public function getOriginalSrcAttribute() {
        return config('custom.aws.s3_bucket_link') . config('custom.aws.class.original_photos_path') . "{$this->hash}.{$this->ext}";
    }

    public function getLargeSrcAttribute() {
        if ($this->large_width) {
            return config('custom.aws.s3_bucket_link') . config('custom.aws.class.photos_path') . "{$this->hash}_l.{$this->ext}";
        }
    }

    public function getThumbnailSrcAttribute() {
        if ($this->thumbnail_width) {
            return config('custom.aws.s3_bucket_link') . config('custom.aws.class.photos_path') . "{$this->hash}_t.{$this->ext}";
        }
    }
}
