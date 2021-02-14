<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class KpiItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug', 'name', 'description', 'active', 'global_kpi_id'
    ];

    public function globalKPI() 
    {

        return $this->belongsTo(GlobalKpi::class, 'global_kpi_id');
    }

    protected static function booted()
    {

        static::addGlobalScope('inActive', function (Builder $builder) {
            $builder->where('active', true);
        });
    }
}
