<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RescheduleRequest extends Model
{
    protected $fillable = [
        'schedule_id',
        'lecturer_name',
        'lecturer_code',
        'course_name',
        'course_code',
        'class_group',
        'from_day',
        'from_time_slot',
        'from_end_slot',
        'from_room_id',
        'target_day',
        'target_time_slot',
        'target_end_slot',
        'target_room_id',
        'reason',
        'status',
        'admin_notes',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function fromRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'from_room_id');
    }

    public function targetRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'target_room_id');
    }
}
