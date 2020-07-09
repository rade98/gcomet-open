<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    protected $fillable = ['name', 'game', 'slots'];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'clientid', 'id');
    }

    public function ips()
    {
        return $this->hasMany(Ip::class, 'id', 'ipid');
    }
}