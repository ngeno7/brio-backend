<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientEmployee extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'first_name', 'last_name', 'job_title', 'client_id',
    ];
}
