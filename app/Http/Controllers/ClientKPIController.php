<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientKpi;
use App\Models\ClientKPIItemExclusion;
use App\Models\GlobalClientKpi;
use App\Models\GlobalKpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientKPIController extends Controller
{
    public function index($client) 
    {

        if(!$cl = Client::where('slug', $client)->select('id')->first()) {

            return response()->json(['message' => 'client unavailable in our records.'],400);
        }

        return response()->json(ClientKpi::where('client_id', $cl->id)->get());
    }

    public function score($client) 
    {
        if(!$cl=Client::where('slug', $client)->select('id')->first()) {

            return response()->json(['message' => 'Forbidden: client unavailable in our records.'],400);
        }

        $score = GlobalClientKpi::with([ 'kpiItems', 'clientKpiItems'])->where('client_id', $cl->id)->get();

        return response()->json($score);
    }

    public function clientKPI($client, $kpi)
    {

        if(!$cl = Client::where('slug', $client)->select('id')->first()) {

            return response()->json(['message' => 'client unavailable in our records.'], 400);
        }

        if(!$gKPI = GlobalKpi::where('slug', $kpi)->select('id')->first()) {

            return response()->json(['message' => 'KPI unavailable in our records.'],400);
        }

        return response()->json(
            ClientKpi::where('client_id', $cl->id)
                ->where('global_client_kpi_id', $gKPI->id)->get());
    }

    public function store(Request $request, $clientSlug, $kpiSlug) 
    {

        if(!$client = Client::where('slug', $clientSlug)->select('id')->first()) {

            return response()->json(['message' => 'Forbidden: Client Unavailabe'], 400);
        }

        if(!$gKPI = GlobalClientKpi::where('client_id', $client->id)
            ->where('slug', $kpiSlug)->select('id')->first()) {

            return response()->json(['message' => 'Forbidden: KPI Unavailabe'], 400);
        }

        if($request->has('kpi_items')) {

            DB::transaction(function() use($request, $client, $gKPI) {
                ClientKpi::where('client_id', $client->id)
                    ->where('global_client_kpi_id',$gKPI->id)->delete();

                foreach ($request->input('kpi_items') as $kpiItem) {
                    ClientKpi::create([
                        'client_id' => $client->id,
                        'global_client_kpi_id' => $gKPI->id,
                        'client_kpi_item_id' => $kpiItem,
                    ]);
                }

                return true;
            });

            return response()->json(['message' => 'Client Score Saved']);
        }
        return response()->json(null);
    }
}
