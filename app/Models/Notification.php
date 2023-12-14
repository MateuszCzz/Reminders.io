<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'notification_date',
        'event_id',
        'type',
        
    ];

    public function event()
    {
        return $this->belongsTo(SystemEvent::class, 'event_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
