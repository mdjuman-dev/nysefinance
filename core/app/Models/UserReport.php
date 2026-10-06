<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class)->withDefault();
    }


    public function reported_user(){
        return $this->belongsTo(User::class, 'repoted_user_id', 'id')->withDefault();
    }
}
