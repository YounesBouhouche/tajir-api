<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'email';
}
