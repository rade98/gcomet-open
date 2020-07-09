<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = ['priority', 'serverid', 'subject', 'type', 'body', 'cid', 'status', 'server'];
    

    public function answers()
    {
        return $this->hasMany(SupportAnswers::class, 'id');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'cid', 'id');
    }
}