<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use App\Models\Personal_access_token;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('name', $fields['name'])->first();

        $lastToken = Personal_access_token::where('tokenable_id', $user->id)
            ->orderBy('expires_at','desc')
            ->first();

        if(is_null($lastToken) != 1){
            $currentTime = Carbon::parse(Carbon::now());
            $expirationTime = Carbon::parse($lastToken['expires_at']);
            $diff = $currentTime->diffInSeconds($expirationTime, false);

            if($diff > 0){
                Log::create([
                    'id_user' => $user->id,
                    'token' => $lastToken->token,
                    'function' => 'get_authorization_token()'
                ]);

                return response([
                    "message" => 'Používateľ je už prihlásený'
                ], 401);
            }
        }

        if(!$user || $user->password != $fields['password']){
            Log::create([
                'function' => 'get_authorization_token()'
            ]);

            return response([
                "message" => 'Zlé prihlasovacie údaje'
            ], 401);
        }

        $token = $user->createToken('appTopken')->plainTextToken;

        $expirationTime = date('Y-m-d H:i:s', strtotime(Carbon::now() . ' + 15 minute')); ;
        Personal_access_token::where('tokenable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->update(['expires_at' => $expirationTime]);

        $lastCreatedToken = Personal_access_token::where('tokenable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        Log::create([
            'id_user' => $user->id,
            'token' => $lastCreatedToken->token,
            'function' => 'get_authorization_token()'
        ]);

        return response([
            "token" => $token
        ], 201);
    }

    //ten isty token, len posunut platnost 
    public function refreshToken(Request $request)
    {
        // $user = auth()->user();
        // $user->tokens()->delete();

        // $token = $user->createToken('appTopken')->plainTextToken;

        // $response = [
        //     'message' => 'Token bol resfreshnuty',
        //     'token' => $token
        // ];

        // return response($response, 201);
    }
}
