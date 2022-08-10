<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Log;
use App\Models\Personal_access_token;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use \DateTime;

class AuthController extends Controller
{
    public function login(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('name', $fields['name'])->first();

        if(!$user || $user->password != $fields['password']){
            Log::create([
                'function' => 'get_authorization_token()',
                'description' => 'zle prihlasovacie udaje (zadane meno:'.$fields['name'].')'
            ]);

            return response([
                "message" => 'Zlé prihlasovacie údaje'
            ], 401);
        }

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
                    'function' => 'get_authorization_token()',
                    'description' => 'pouzivatel je uz prihlaseny'
                ]);

                return response([
                    "message" => 'Používateľ je už prihlásený'
                ], 401);
            }
        }

        $expirationTime = new DateTime();
        $expirationTime->modify('+15 minutes');
        $token = $user->createToken('appTopken',[], $expirationTime)->plainTextToken;

        $lastCreatedToken = Personal_access_token::where('tokenable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        Log::create([
            'id_user' => $user->id,
            'token' => $lastCreatedToken->token,
            'function' => 'get_authorization_token()',
            'description' => 'prihlasenie pouzivatela (meno:'.$fields['name'].')'
        ]);

        return response([
            "token" => $token
        ], 201);
    }

    public function refreshToken(Request $request)
    {   
        $user = auth()->user();

        $token = Personal_access_token::where('tokenable_id', $user->id)
            ->orderBy('expires_at','desc')
            ->first();
        
        $newExpirationTime = new DateTime($token->expires_at);
        $newExpirationTime->modify('+15 minutes');

        Personal_access_token::where('id', $token->id)
            ->update(['expires_at' => $newExpirationTime]);

        Log::create([
            'id_user' => $user->id,
            'token' => $token->token,
            'function' => 'refresh_token()',
            'description' => 'token bol refreshnuty'
        ]);

        return response([
            "message" => 'Token bol refreshnutý'
        ], 201);
    }
}
