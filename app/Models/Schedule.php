<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'room_id',
        'day',
        'time_slot',
        'course_code',
        'course_name',
        'lecturer_code',
        'lecturer_name',
        'class_group',
        'status',
        'cancellation_reason',
        'cancelled_at',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
