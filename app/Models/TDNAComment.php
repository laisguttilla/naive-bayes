<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class TDNAComment extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'tdna_comments';

    protected $fillable = [
        'msisdn', 'grade', 'la', 'answer_id', 'comment'
    ];
}
