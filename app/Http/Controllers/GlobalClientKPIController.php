<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\GlobalClientKpi;
use Illuminate\Http\Request;

class GlobalClientKPIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index($clientSlug)
    {
        if(!$client = Client::where('slug', $clientSlug)->select('id')->first()) {

            return response()->json(['message' => 'Forbidden: Client Unavailable'], 400);
        }

        $clientKPIs = GlobalClientKpi::where('client_id', $client->id)->get();

        return response()->json($clientKPIs, 200);
    }

    public function kpiItems($clientSlug, $kpiSlug)
    {
        if(!$client=Client::where('slug', $clientSlug)->select('id')->first()) {

            return response()->json(['message' => 'Forbidden: Client Unavailable'], 400);
        }

        if(!$gcKPI=GlobalClientKpi::where('slug', $kpiSlug)->where('client_id', $client->id)->with([
            'kpiItems', 'clientKpiItems'])->first()) {
            return response()->json(['message' => 'Forbidden: Client KPI Unavailable'], 400);
        }

        return response()->json($gcKPI, 200);
    }

    public function storeScore(Request $request, $clientSlug)
    {
        if(!$request->has('kpis')) {
            
            return response()->json(['message' => 'No KPis entered'], 400);
        }

        if(!$client=Client::where('slug', $clientSlug)->select('id')->first()) {

            return response()->json(['message' => 'Forbidden: Client Not Available'], 400);
        }

        foreach ($request->input('kpis') as $kpi) {
            GlobalClientKpi::where('client_id', $client->id)->where('id', $kpi['id'])->update(
                ['score' => $kpi['score'],]
            );
        }

        return response()->json(['message' => 'Client KPI Scores saved']);
    }
}
