<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para los mensajes de contacto.
 * Permite almacenar las consultas de los usuarios.
 */
class Contact extends Model
{
    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message'
    ];
}
