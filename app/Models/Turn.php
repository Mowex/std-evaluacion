<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turn extends Model
{
    use HasFactory;

    protected $fillable = [
        'hour',
        'is_active'
    ];

    protected $appends = [
        'is_active_string'
    ];

    public function getIsActiveStringAttribute() {
        $value = $this->is_active === 0 ? 'Inactivo' : 'Activo';
        return $value;
    }

    public function movie(){
        return $this->belongsTo(Movie::class);
    }
}
