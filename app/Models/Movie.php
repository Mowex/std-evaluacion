<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'publication_date',
        'image'
    ];

    public function turn() {
        return $this->hasOne(Turn::class);
    }
}
