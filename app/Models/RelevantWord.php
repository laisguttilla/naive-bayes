<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class RelevantWord extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'relevant_words';

    protected $fillable = [
        'type', 'statement'
    ];
}
