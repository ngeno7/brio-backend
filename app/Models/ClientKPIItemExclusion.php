<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientKPIItemExclusion extends Model
{
    protected $table='client_kpi_items_exclusions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'client_id', 'kpi_item_id', 'global_kpi_id',
    ];
}
