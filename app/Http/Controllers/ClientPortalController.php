<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\GlobalClientKpi;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function profile(Request $request) 
    {
        if(!$client = Client::where('token', $request->bearerToken())->with('employees')->first()) {

            return response()->json(['message' => 'Client Unavailable'], 400);
        }

        return response()->json($client, 200);
    }

    public function scoreDetails(Request $request) 
    {

        if(!$cl = Client::where('token', $request->bearerToken())->select('id')->first()) {

            return response()->json(['message' => 'client unavailable in our records.'],400);
        }

        $score = GlobalClientKpi::where('client_id', $cl->id)->with([ 'kpiItems', 'clientKpiItems' => function($query) {
            return $query->whereNotNull('client_kpi_item_id');
        }])->get();

        return response()->json($score);
    }

    public function kpiScoreDetails(Request $request, $slug)
    {
        if(!$cl = Client::where('token', $request->bearerToken())->select('id')->first()) {

            return response()->json(['message' => 'client unavailable in our records.'],400);
        }

        $score = GlobalClientKpi::where('client_id', $cl->id)->where('slug', $slug)
            ->with([ 'kpiItems', 'clientKpiItems' => function($query) {
                $query->whereNotNull('client_kpi_item_id');
            }])->first();

        return response()->json($score);
    }
}
