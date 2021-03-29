<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use IlluminateAgnostic\Str\Support\Str;

class UserController extends Controller
{
    public function index() 
    {
        return response()->json(User::all());
    }

    public function create(Request $request) 
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
        ]);
        $data = $request->only('first_name', 'last_name', 'email', 'active', 'password', 'is_admin');
        // $password = rand(1000, 99999);
        $data['password'] = Hash::make($request->input('password'));
        $data['token'] = Str::random(80);
        $data['active'] = $request->input('active') ?? false;
        $data['is_admin'] = $request->input('is_admin') ?? false;

        User::create($data);

        $creds=[
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        Mail::send('user-mail', $creds, function($message) use($data) {
            $message->to($data['email'])->subject('Brio Account');
        });

        return response()->json(['message' => 'User added successfully.'], 201);
    }

    public function update(Request $request, $id) 
    {
        if(!$user = User::find($id)) {
    
            return response()->json(['message' => 'User unavailable'], 400);
        }

        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            // 'password' => 'required',
        ]);

        $data = $request->only('first_name', 'last_name', 'email', 'active');
        $data['active'] = $request->input('active') ?? false;
        $data['is_admin'] = $request->input('is_admin') ?? false;

        if($request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        if($request->input('password')) {
            $creds = [
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ];
            Mail::send('user-mail', $creds, function($message) use($data) {
                $message->to($data['email'])->subject('Brio Account');
            });
        }

        return response()->json(['message' => 'User updated successfully.'], 201);
    }

    public function destroy($id) 
    {
        if(!$user=User::find($id)) {
            return response()->json(['message' => 'User Unavailable'], 400);
        }
        if($user->id < 2) {
            return response()->json(['message' => 'System User cannot be deleted'], 400);
        }


        $user->delete();

        return response()->json(['message' => 'User deleted successfully.'], 201);
    }

    public function clients($id) 
    {
        return response()->json(Client::where('user_id', $id)
            ->select('name', 'email', 'slug', 'number_of_employees',  'average_kpi_score', 'is_client')->get());
    }

    public function logInUser(Request $request) 
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!$user = DB::table('users')->where('email', $request->input('email'))
            ->where('active', true)->first()) {

            return response()->json(['message' => 'Invalid Credentials'], 400);
        }

        if(Hash::check($request->input('password'), $user->password)) {

            return response()->json([
                'message' => 'Log in successfull', 'token' => $user->token], 200);
        }

        return response()->json(['message' => 'Invalid Credentials'], 400);
    }

    public function logInClient(Request $request) 
    {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(!$user = DB::table('clients')->where('email', $request->input('email'))->first()) {

            return response()->json(['message' => 'Invalid Credentials'], 400);
        }

        if(Hash::check($request->input('password'), $user->password)) {

            return response()->json([
                'message' => 'Log in successfull', 'token' => $user->token], 200);
        }

        return response()->json(['message' => 'Invalid Credentials'], 400);
    }
}
