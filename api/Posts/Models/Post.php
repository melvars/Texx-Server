<?php

namespace Api\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Post extends Model
{
    use HasApiTokens, Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_types_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('Api\Users\Models\User');
    }

    public function post_type()
    {
        return $this->belongsTo('Api\Posts\Models\PostType', 'post_types_id', 'id');
    }

    public function media_post()
    {
        return $this->hasOne('Api\Posts\Models\MediaPost');
    }

    public function text_post()
    {
        return $this->hasOne('Api\Posts\Models\TextPost');
    }
}
