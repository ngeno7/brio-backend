<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\GlobalClientKpi;
use App\Models\GlobalKpi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if(!$client=Client::where('slug', $clientSlug)->first()) {

            return response()->json(['message' => 'Forbidden: Client Not Available'], 400);
        }

        DB::transaction(function() use($request, $client) {
            $client->update(['overridden' => true, 'average_kpi_score' => $request->input('average_kpi_score')]);

            foreach ($request->input('kpis') as $kpi) {
                GlobalClientKpi::where('client_id', $client->id)->where('id', $kpi['id'])->update(
                    ['score' => $kpi['score'],]
                );
            }
        });

        return response()->json(['message' => 'Client KPI Scores saved']);
    }

    public function updateDisplayText(Request $request, $clientSlug)
    {
        if(!$client=Client::where('slug', $clientSlug)->first()) {

            return response()->json(['message' => 'Client Unavailable'], 400);
        }

        DB::transaction(function() use($request, $client) {
            $data = $request->only('client');
            $client->update($data['client']);
            foreach ($request->input('kpis') as $kpi) {
                GlobalClientKpi::find($kpi['id'])->update(
                    [
                        'welcome_message' => $kpi['welcome_message'], 
                        'score_message' => $kpi['score_message']
                    ]);
            }
        });

        return response()->json(['message' =>'Display messages Updated successfully'],200);
    }

    public function destroy($clientSlug, $kpiSlug)
    {
        if(!$client = Client::where('slug', $clientSlug)->select()->first()){

            return response()->json(['message' => 'Client Unavailable'], 400);
        }

        if(!$globalKPI = GlobalClientKpi::where('slug', $kpiSlug)->where('client_id', $client->id)->first()) {

            return response()->json(['message' => 'KPI Unavailable'], 400);
        }

        $globalKPI->update(['active' => false]);

        return response()->json(['message' => 'KPI removed successfully'], 200);
    }
}
