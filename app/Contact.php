<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['clientId', 'firstName', 'lastName', 'email', 'phone', 'subject', 'message'];
}
