<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Politically_exposed_person extends Model
{
    use HasFactory;

    protected $fillable = [
        'category'
    ];
}
