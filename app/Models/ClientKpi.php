<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientKpi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'client_id', 'global_client_kpi_id', 'client_kpi_item_id',
    ];
}
