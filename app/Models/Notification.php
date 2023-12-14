<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'event_id',
        'type',
    ];

    public function event()
    {
        return $this->belongsTo(SystemEvent::class, 'event_id');
    }
}
