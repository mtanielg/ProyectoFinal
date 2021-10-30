<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table='rol';
    public $timestamps=false;
    protected $fillable=[
        'id_rol', 'descripcion'
    ];

    protected $primaryKey='id_rol';
}
