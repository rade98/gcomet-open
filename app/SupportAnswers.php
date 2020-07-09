<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupportAnswers extends Model
{
    protected $fillable = ['tid', 'user', 'message'];

    public function TicketAnswers()
    {
        return $this->belongsTo(SupportTicket::class, 'id'); 
    }
}
