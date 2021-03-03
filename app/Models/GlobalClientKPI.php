<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalClientKpi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'slug', 'client_id', 'name', 
        'description', 'active', 'file_path', 'score',
    ];

    public function kpiItems() 
    {

        return $this->hasMany(ClientKpiItem::class, 'global_client_kpi_id');
    }

    public function clientKpiItems()
    {

        return $this->hasMany(ClientKpi::class, 'global_client_kpi_id');
    }
}
