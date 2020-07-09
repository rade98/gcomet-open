<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $fillable = ['ip', 'boxid'];

    public function server()
    {
        return $this->belongsTo(Server::class, 'id'); 
    }
}


