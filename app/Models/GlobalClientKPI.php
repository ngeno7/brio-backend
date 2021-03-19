<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GlobalClientKpi extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'slug', 'client_id', 'name', 'system',
        'description', 'active', 'file_path', 'score', 'icon',
        'welcome_message', 'score_message',
    ];

    public function kpiItems() 
    {

        return $this->hasMany(ClientKpiItem::class, 'global_client_kpi_id');
    }

    public function clientKpiItems()
    {

        return $this->hasMany(ClientKpi::class, 'global_client_kpi_id');
    }

    protected static function booted()
    {

        static::addGlobalScope('inActive', function (Builder $builder) {
            $builder->where('active', true);
        });
    }
}
