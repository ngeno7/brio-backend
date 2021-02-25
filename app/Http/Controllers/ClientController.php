<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use IlluminateAgnostic\Str\Support\Str;

class ClientController extends Controller
{
    public function index() 
    {
        return response()->json(Client::all());
    }

    public function recentClients() 
    {
        return response()->json(Client::latest()->take(4)->get(['slug', 'name', 'logo']));
    }

    public function single($slug)
    {
        if(!$client = Client::where('slug', $slug)->with('employees')->first()) {

            return response()->json(['message' => 'Client unavailable'], 400);
        }

        return response()->json($client);
    }

    public function store(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required|email|unique:clients,email',
            'name' => 'required',
            'number_of_employees' => 'required',
        ]);

        $file = "";

        if($request->hasFile('file')) {

            $this->validate($request, [
                'file' => 'image',
            ]);

            $file = base64_encode(file_get_contents($request->file('file')));
        }

        $data = $request->only('email', 'name', 'is_client', 'number_of_employees','employees');
        $data['logo'] = $file;
        $data['slug'] = Str::kebab($request->input('name')).'-'.time();
        // $data['password'] = Hash::make(Str::random(4));
        $data['password'] = Hash::make('123456');
        $data['token'] = Str::random(80);

        $client = DB::transaction(function() use($data) {

                $client = Client::create($data);
                foreach (json_decode(utf8_decode($data['employees']),true) as $emp) {
                    $client->employees()->create($emp);
                }

                return $client;
        });

        return response()->json(['message' =>'Client Saved successfully', 'data' => $client]);
    }
    
    public function update(Request $request, $slug)
    {

        if(!$client = Client::where('slug', $slug)->first()) {

            return response()->json(['message' => 'Client Unavailable'], 400);
        }

        $this->validate($request, [
            'email' => 'required|email',
            'name' => 'required',
            'number_of_employees' => 'required',
        ]);

        $file = $client->logo;

        if($request->hasFile('file')) {

            $this->validate($request, [
                'file' => 'image',
            ]);

            $file = base64_encode(file_get_contents($request->file('file')));
        }

        $data = $request->only('email', 'name', 'number_of_employees','employees');
        $data['logo'] = $file;

        $cl = DB::transaction(function() use($data, $client) {

                $client->update($data);
                $client->employees()->delete();
                foreach (json_decode(utf8_decode($data['employees']),true) as $emp) {
                    $client->employees()->create($emp);
                }

                return $client;
        });

        return response()->json(['message' =>'Client Updated successfully', 'data' => $cl,]);
    }
}
