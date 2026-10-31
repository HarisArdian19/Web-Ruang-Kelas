<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailNotification extends Model
{
    protected $fillable = [
        'lecturer_name',
        'lecturer_email',
        'type',
        'subject',
        'body',
        'class_info',
        'status',
    ];
}
