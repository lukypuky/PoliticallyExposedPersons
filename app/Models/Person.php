<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'osoba_meno',
        'osoba_priezvisko',
        'osoba_datum_narodenia',
        'id_pep_category'
    ];
}
