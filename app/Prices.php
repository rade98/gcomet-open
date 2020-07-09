<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{
    protected $fillable = ['gameid', 'location', 'price'];

    public function game()
    {
        return $this->belongsTo(Games::class, 'id'); 
    }
}
