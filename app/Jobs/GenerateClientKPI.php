<?php

namespace App\Jobs;

use App\Models\GlobalClientKpi;
use App\Models\GlobalKpi;
use App\Models\ClientKpiItem;

class GenerateClientKPI 
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;

        return $this;
    }

    public function generateKPI()
    {
        // dd($this->client->id);
        $kpis = GlobalKpi::select( 'id', 'name', 'description', 'slug', 'file_path', 'system')
                    ->with(['kpiItems' => function($query) {
            return $query->select('name', 'global_kpi_id', 'slug', 'description', 'active', 'active');
        }])->get()->toArray();

        foreach ($kpis as $kpi) {

            $cgKPI = GlobalClientKpi::create(
                collect($kpi)->merge(['client_id' => $this->client->id,])->toArray());

            foreach ($kpi['kpi_items'] as $kpiItem) {
                ClientKpiItem::create(
                    collect($kpiItem)->merge(
                        ['global_client_kpi_id' => $cgKPI->id, 
                        'client_id' => $this->client->id,])->toArray());
            }
        }
    }
}