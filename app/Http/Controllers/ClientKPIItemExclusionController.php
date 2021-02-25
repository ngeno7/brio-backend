<?php

namespace App\Http\Controllers;

use App\Models\ClientKPIItemExclusion;
use Illuminate\Http\Request;

class ClientKPIItemExclusionController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'kpi_item_id' => 'required|exists:kpi_items,id',
            'global_kpi_id' => 'required|exists:global_kpis,id',
            'client_id' => 'required|exists:clients,id',
        ]);

        ClientKPIItemExclusion::create($request->only('kpi_item_id', 'global_kpi_id', 'client_id'));

        return response()->json(['message' => 'KPI Item excluded from analysis'], 201);
    }
}
