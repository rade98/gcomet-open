<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Games extends Model
{
    protected $fillable = ['panelgameid', 'name', 'active', 'minslots', 'maxslots', 'slotincreament', 'order'];

    public function prices()
    {
        return $this->hasMany(Prices::class, 'gameid');
    }

    public function mods()
    {
        return $this->hasMany(Mod::class, 'gameid');
    }

    public function order()
    {
        return $this->belongsTo(Orders::class, 'gameid', 'id');
    }
}
