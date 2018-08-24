<?php

namespace Api\Posts\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MediaPost extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = "media_posts";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'media_path'
    ];

    public function post()
    {
        return $this->belongsTo('Api\Posts\Models\Post');
    }
}
