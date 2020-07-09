<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public function game()
    {
        return $this->belongsTo(Games::class, 'id'); 
    }
}
