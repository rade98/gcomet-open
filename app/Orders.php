<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = ['clientid', 'serverid', 'gameid', 'coupon', 'method', 'slots', 'price', 'ip', 'text', 'uplatnice', 'body', 'modid', 'transactionId'];

    public function games()
    {
        return $this->belongsTo(Games::class, 'gameid', 'id');
    }
}
