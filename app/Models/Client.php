<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'slug', 'is_client', 'email', 'name', 
        'number_of_employees', 'logo', 'password', 'verified',
        'token', 'verification_code', 'score', 'email_2'
    ];

    protected $hidden = [
        'password', 'token', 'verification_code',
    ];

    public function employees() 
    {

        return $this->hasMany(ClientEmployee::class, 'client_id');
    }

    public function kpis() 
    {

        return $this->hasMany(ClientKpi::class);
    }
}
