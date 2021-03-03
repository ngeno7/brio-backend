<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientKpiItem;
use App\Models\GlobalClientKpi;
use Illuminate\Http\Request;
use IlluminateAgnostic\Str\Support\Str;

class ClientKPIItemController extends Controller
{
    public function store(Request $request, $kpiSlug, $clientSlug) 
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        if(!$client = Client::where('slug', $clientSlug)->select('id')->first()) {

            return response()->json(['message' => 'Forbidden: Client Unavailable'], 400);
        }
    
        if(!$kpi = GlobalClientKpi::where('client_id', $client->id)->where('slug', $kpiSlug)->first()) {

            return response()->json(['message' => 'Forbidden: KPI Unavailable'], 400);
        }

        ClientKPIItem::create([
            'global_client_kpi_id' => $kpi->id,
            'name' => $request->input('name'),
            'slug' => Str::kebab($request->input('name')),
        ]);

        return response()->json(['message' => 'Client KPI Saved successfully.'], 200);
    }

    public function destroy($id) 
    {
        if(!$kpiItem = ClientKpiItem::find($id)) {

            return response()->json(['message' => 'Client KPI Item Unavailable.']);
        }

        $kpiItem->delete();

        return response()->json(['message' => 'KPI Item Deleted.']);
    }
}
