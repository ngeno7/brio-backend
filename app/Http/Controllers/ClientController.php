<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateClientKPI;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

        $data = $request->only('email', 'name', 'email_2', 'is_client', 'number_of_employees','employees');
        $data['logo'] = $file;
        $data['slug'] = Str::kebab($request->input('name')).'-'.time();
        $password = Str::random(5);
        $data['password'] = Hash::make($password);
        // $data['password'] = Hash::make('123456');
        $data['token'] = Str::random(80);

        $client = DB::transaction(function() use($data) {

                $client = Client::create($data);
                foreach (json_decode(utf8_decode($data['employees']),true) as $emp) {
                    $client->employees()->create($emp);
                }

                (new GenerateClientKPI($client))->generateKPI();

                return $client;
        });

        $creds = ['email' => $data['email'], 'password' => $password];
        Mail::send('mail', $creds, function($message) use($data) {
            $message->to($data['email'])->subject('Brio Account');
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

        $data = $request->only('email', 'name', 'email_2', 'number_of_employees','employees');
        $data['logo'] = $file;

        $cl = DB::transaction(function() use($data, $client) {

                $client->update($data);
                $client->employees()->delete();
                foreach (json_decode(utf8_decode($data['employees']),true) as $emp) {
                    $client->employees()->create($emp);
                }

                return $client;
        });

        return response()->json(['message' =>'Client Updated successfully', 'data' => $cl,],200);
    }

    public function resetPasswordInit(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required|email|exists:clients,email',
        ]);

        $client = Client::where('email', $request->input('email'))->first();
        $code = Str::random(6);
        $client->update(['verification_code' => Hash::make($code)]);

        Mail::send('verification-code', ['code' => $code], function($message) use($request) {
            $message->to($request->input('email'))->subject('Brio Reset Password');
        });

        return response()->json(['message' => 'Verification code sent to email'], 200);
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:clients,email',
            'verification_code' => 'required',
            'password' => 'required',
        ]);

        $client=DB::table('clients')->where('email', $request->input('email'))->first();

        if(Hash::check($request->input('verification_code'), $client->verification_code)) {
            $token = Str::random(80);
            Client::find($client->id)->update([
                'token' => $token, 
                'password' => Hash::make($request->input('password')),
                'verification_code' => null,
            ]);
 
            Mail::send('mail', $request->all(), function($message) use($request) {
                $message->to($request->input('email'))->subject('Brio Account');
            });

            return response()->json(['message' => 'Password updated, Log in to your account '], 200);
        }

        return response()->json(['message' => 'Invalid information'], 400);
    }

    public function updateRating(Request $request, $slug) 
    {
        if(!$client=Client::where('slug', $slug)->first()) {

            return response()->json(['message' => 'Forbidden: Client Unavailable'], 400);
        }

        $client->update(['score' => $request->input('score')]);

        return response()->json(['message' => 'Client rating saved'], 201);
    }
}
