<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'second_name', 'surname', 'birthday',
    ];

    protected $dates = ['birthday'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
