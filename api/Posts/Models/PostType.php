<?php

namespace Api\Posts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class PostType extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = "post_types";

    public function posts()
    {
        return $this->hasMany('Api\Posts\Models\Post');
    }
}
