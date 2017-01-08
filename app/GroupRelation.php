<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupRelation extends Model {

    protected $fillable = [
        'user_id',
        'group_id',
        'relation_type'
    ];

    protected $casts = [
        'id' => 'integer',
        'group_id' => 'integer',
        'user_id' => 'integer'
    ];

}
