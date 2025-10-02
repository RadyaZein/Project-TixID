<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use softDeletes;

    protected $fillable = ['cinema_id', 'movie_id', 'hours', 'price'];

    // memanggil relasi ke cinema, schedule mempunyai FK cinema
    // karena one (cinema) to many (schedule) : nama tunggal
    public function cinema()
    {
        // untuk table yang memegang FK gunakan belongsTo
        return $this->belongsTo(Cinema::class);
    }

    // memanggil relasi ke movie, schedule mempunyai FK movie

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'Movie_id', 'id');
    }

    // cast : memastikan tipe data. agar json jadi array

    protected function casts(): array
    {
        return [
            'hours' => 'array'
        ];
    }

}
