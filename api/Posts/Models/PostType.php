<?php

namespace Api\Posts\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TextPost extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = "post_types";

    public function posts()
    {
        return $this->hasMany('Api\Posts\Models\Post');
    }
}
