<?php

namespace App\Http\Controllers\Passport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use DB;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $client = DB::table('oauth_clients')
                    ->where([
                        ['id', request('client_id')],
                        ['secret', request('client_secret')]
                    ])->first();

        if(!$client) { return response()->json(['error' => 'Invalid OAuth Credentials'], 400); }

        $v = validator($request->only('email', 'name', 'password'), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($v->fails()) {
            return response()->json($v->errors()->all(), 400);
        }

        $data = request()->only('client_id','client_secret','email','name','password');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $data['client_id'],
            'client_secret' => $data['client_secret'],
            'username'      => $data['email'],
            'password'      => $data['password'],
            'scope'         => null,
        ]);
        
        $token = Request::create(
            'oauth/token',
            'POST'
        );

        return \Route::dispatch($token);
    }
}
