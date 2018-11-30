<?php

namespace App\Models;

use App\Components\Model;

class Medicine extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'date_published',
        'site_url',
        'image_url',
        'author',
        'created_by',
        'updated_by',
        'topic_id',
        'content_id'
    ];

    // each Medicine BELONGS only to one content
    public function content() {

        return $this->belongsTo('App\Models\Content');
    }

    // each Medicine BELONGS only to one topic
    public function topic() {

        return $this->belongsTo('App\Models\topic');
    }


}
