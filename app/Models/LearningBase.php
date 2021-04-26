<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class LearningBase extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'learning_base';

    protected $fillable = [
        'type', 'statement'
    ];
}
