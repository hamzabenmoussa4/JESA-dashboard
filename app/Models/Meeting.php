<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meeting extends Model
{
    protected $fillable = [
        'user_id',
        'meeting_name',
        'date_of_meeting',
        'time',
        'prepared_by',
        'location',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
