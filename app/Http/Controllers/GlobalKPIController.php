<?php

namespace App\Http\Controllers;

use App\Models\GlobalKpi;
use Illuminate\Http\Request;
use IlluminateAgnostic\Str\Support\Str;

class GlobalKPIController extends Controller
{
    public function index()
    {
  
        return response()->json(GlobalKpi::with(['kpiItems'])->get([
            'name', 'slug', 'id', 'file_path', 'kpi_items'
        ]));
    }

    public function store(Request $request)
    {

        $this->validate($request,[
            'name' => 'required|unique:global_kpis,name',
        ]);

        if($request->hasFile('file')) {
            $this->validate($request,[ 'file' => 'image',]);
            $image = $request->file('file');
            $name = Str::kebab($request->input('name')).'.'.$image->getClientOriginalExtension();
            $destinationPath = storage_path('/app/images');
            $request->file('file')->move($destinationPath, $name);
        }

        $data = [
            'name' => $request->input('name'),
            'file_path'=> $name,
            'slug' => Str::kebab($request->input('name')),
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

        $name = $request->input('file_path');

        if($request->hasFile('file')) {
            $this->validate($request,[ 'file' => 'image',]);
            $image = $request->file('file');
            $name = Str::kebab($request->input('name')).'.'.$image->getClientOriginalExtension();
            $destinationPath = storage_path('/app/images');
            $request->file('file')->move($destinationPath, $name);
        }

        $data = [
            'name' => $request->input('name'),
            'file_path'=> $name,
            'slug' => Str::kebab($request->input('name')),
        ];

        $gKPI->update($data);

        return response()->json(['message' => 'Global KPI Updated'], 200);
    }
}
