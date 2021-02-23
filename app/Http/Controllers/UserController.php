<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use IlluminateAgnostic\Str\Support\Str;

class UserController extends Controller
{
    public function logInUser(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!$user = DB::table('users')->where('email', $request->input('email'))->first()) {

            return response()->json(['message' => 'Invalid Credentials'], 400);
        }

        if(Hash::check($request->input('password'), $user->password)) {
            $token = Str::random(80);

            User::find($user->id)->update(['token' => $token]);

            return response()->json([
                'message' => 'Log in successfull', 'token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid Credentials'], 400);
    }

    public function logInClient(Request $request) 
    {

        $this->validate($request, [
            'email' => 'required|email:',
            'password' => 'required',
        ]);

        if(!$user = DB::table('clients')->where('email', $request->input('email')->first())) {

            return response()->json(['message' => 'Invalid Credentials'], 400);
        }

        if(Hash::check($request->input('password'), $user->password)) {
            $token = Str::random(80);

            Client::find($user->id)->update(['token' => $token]);

            return response()->json([
                'message' => 'Log in successfull', 'token' => $token], 200);
        }

        return response()->json(['message' => 'Invalid Credentials'], 400);
    }
}
