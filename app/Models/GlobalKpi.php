<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalKpi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'slug', 'name', 'description', 'system', 'active', 'file_path', 'icon',
    ];

    public function kpiItems() 
    {

        return $this->hasMany(KpiItem::class, 'global_kpi_id');
    }

    public function clientKpiItems()
    {

        return $this->hasMany(ClientKpi::class, 'global_kpi_id');
    }
}
