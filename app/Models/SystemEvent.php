<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SystemEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'month',
        'day',
        'type',
        'isCustom',
        'notification_message'
    ];

    protected $casts = [
        'month' => 'integer',
        'day' => 'integer',
    ];


    public function notifications()
    {
        return $this->hasMany(Notification::class, 'event_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
