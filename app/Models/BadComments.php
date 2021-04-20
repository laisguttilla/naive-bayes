<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class BadComments extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'bad_comments';
}
