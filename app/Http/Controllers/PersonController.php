<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Politically_exposed_person;
use App\Models\Personal_access_token;
use App\Models\Person;
use App\Models\Log;

class PersonController extends Controller
{
    public function getPepCategories(){
        $persons =  Politically_exposed_person::all();

        $this->log('get_pep_categories()');

        return response()->json([
            'politically_exposed_persons' => $persons
        ]);
    }

    public function setPepPerson(Request $request){
        $fields = $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'birthdate' => 'required|date_format:Y-m-d',
            'pep_category' => 'required'
        ]);

        $person = Person::create([
            'osoba_meno' => $fields['name'],
            'osoba_priezvisko' => $fields['surname'],
            'osoba_datum_narodenia' => $fields['birthdate'],
            'id_pep_category' => $fields['pep_category']
        ]);

        $this->log('set_pep_categories()');

        return response([
            'message' => 'Nová osoba bola pridaná',
        ], 201);
    }

    public function getPepPerson(Request $request){
        $person = Person::where('osoba_meno', 'like', '%' . $request->get('name') . '%')
            ->where('osoba_priezvisko', 'like', '%' . $request->get('surname') . '%')
            ->where('osoba_datum_narodenia', 'like', '%' . $request->get('birthdate') . '%')
            ->get();


        if($person->count() == 0){
            return response([
                'message' => 'Nenašiel sa žiaden záznam',
            ], 401);
        }

        return response()->json([
            'person' => $person
        ]);
    }

    public function log($functionName){
        $user = auth()->user();

        $lastToken = Personal_access_token::where('tokenable_id', $user->id)
            ->orderBy('expires_at','desc')
            ->first();

        Log::create([
            'id_user' => $user->id,
            'token' => $lastToken->token,
            'function' => $functionName
        ]);
    }

}
