<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientKPIItemExclusion;
use App\Models\GlobalKpi;
use Illuminate\Http\Request;
use IlluminateAgnostic\Str\Support\Str;

class GlobalKPIController extends Controller
{
    public function index()
    {
  
        return response()->json(GlobalKpi::get([
            'name', 'slug', 'id', 'file_path', 'system'
        ]));
    }

    public function show($slug) 
    {
        if(!$gKPI = GlobalKpi::where('slug',$slug)->with(['kpiItems'])->first([
            'id', 'slug', 'name', 'file_path', 'description'
        ])) {

            return response()->json(['message' => 'Global KPI unavailable'], 400);
        }

        return response()->json($gKPI);
    }

    public function single($kpi, $clientSlug) 
    {
        if(!$client = Client::where('slug', $clientSlug)->first()) {

            return response()->json(['message' => 'Client Unavailable'], 404);
        }

        $excludedKPIItems = ClientKPIItemExclusion::where('client_id', $client->id)->get(['kpi_item_id'])
            ->map(function($item) {
                return $item->kpi_item_id;
            });

        if(!$gKPI = GlobalKpi::where('slug',$kpi)->with(['kpiItems' => function($query) use($excludedKPIItems) {
            return $query->whereNotIn('id', $excludedKPIItems);
        }])->first([
            'id', 'slug', 'name', 'file_path', 'description'
        ])) {

            return response()->json(['message' => 'Global KPI unavailable'], 400);
        }

        return response()->json($gKPI);
    }

    public function store(Request $request)
    {

        $this->validate($request,[
            'name' => 'required|unique:global_kpis,name',
        ]);

        $file = "";

        if($request->hasFile('file')) {

            $this->validate($request, ['file' => 'image',]);
            $file = base64_encode(file_get_contents($request->file('file')));
        }

        $data = [
            'name' => $request->input('name'),
            'icon' => $file,
            'slug' => Str::kebab($request->input('name')).'-'.time(),
        ];

        GlobalKpi::create($data);

        return response()->json(['message' => 'Global KPI Created'], 200);
    }

    public function update(Request $request, $id) 
    {
        if(!$gKPI = GlobalKpi::find($id)) {
            return response()->json(['message' => 'Global KPI unavailable'],400);
        }

        $this->validate($request,[
            'name' => 'required|unique:global_kpis,name,'.$id,
        ]);

        $file = $gKPI->icon;

        if($request->hasFile('file')) {

            $this->validate($request, ['file' => 'image',]);
            $file = base64_encode(file_get_contents($request->file('file')));
        }

        $data = [
            'name' => $request->input('name'),
            'icon'=> $file,
        ];

        $gKPI->update($data);

        return response()->json(['message' => 'Global KPI Updated'], 200);
    }
}
