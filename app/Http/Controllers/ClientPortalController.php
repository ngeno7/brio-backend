<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\GlobalKpi;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    public function profile(Request $request) 
    {
        if(!$client = Client::where('token', $request->bearerToken())->first()) {

            return response()->json(['message' => 'Client Unavailable'], 400);
        }

        return response()->json($client, 200);
    }

    public function scoreDetails(Request $request) 
    {

        if(!$cl = Client::where('token', $request->bearerToken())->select('id')->first()) {

            return response()->json(['message' => 'client unavailable in our records.'],400);
        }

        $score = GlobalKpi::with([ 'kpiItems', 'clientKpiItems' => function($query) use($cl) {
            $query->where('client_id', $cl->id);
        }])->get();

        return response()->json($score);
    }
}
