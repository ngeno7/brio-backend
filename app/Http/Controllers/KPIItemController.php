<?php

namespace App\Http\Controllers;

use App\Models\GlobalKpi;
use App\Models\KpiItem;
use Illuminate\Http\Request;
use IlluminateAgnostic\Str\Support\Str;

class KPIItemController extends Controller
{

    public function index($slug)
    {
        return response()
                    ->json(GlobalKpi::with(['kpiItems'])->where('slug', $slug)->first());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'global_kpi_id' => 'required|exists:global_kpis,id',
            'name' => 'required',
        ]);

        GlobalKpi::find($request->input('global_kpi_id'))
            ->kpiItems()->create([
                'name' => $request->input('name'),
                'slug' => Str::kebab($request->input('name'))
            ]);

        return response()->json(['message' => 'KPi Added successfully'], 200);
    }

    public function update(Request $request, $id) 
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        if(!$kpiItem = KpiItem::find($id)){

            return response()->json(['message' => 'KPI item unavailable'], 400);
        }

        $data = [
            'name' => $request->input('name'),
            'slug' => Str::kebab($request->input('name'))
        ];

        $kpiItem->update($data);

        return response()->json(['message' => 'KPI item updated successfully'], 200);
    }

    public function destroy($slug) 
    {
        if(!$kpiItem = KpiItem::where('slug',$slug)->first()){

            return response()->json(['message' => 'KPI item unavailable'], 400);
        }

        $kpiItem->delete();

        return response()->json(['message' => 'KPI item deleted successfully'], 200);
    }
}
