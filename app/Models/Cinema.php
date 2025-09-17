<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    //mendaftarkan softdeletes
    use SoftDeletes;
    // mendaftarkan nama-column yang akan diisi,
    // nama-nama column selain id dan timestamp
    protected $fillable = [
    'name',
    'email',
    'location',
    'password',
];

}
