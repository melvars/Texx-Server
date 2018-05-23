<?php

namespace UserFrosting\Sprinkle\ExtendUser\Database\Models;

use UserFrosting\Sprinkle\Core\Database\Models\Model;

class MemberAux extends Model
{
    public $timestamps = FALSE;

    /**
     * @var string The name of the table for the current model.
     */
    protected $table = 'members';

    protected $fillable = [
        'city',
        'country'
    ];
}
