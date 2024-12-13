<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'usuario', 'primerNombre', 'primerApellido', 'segundoApellido', 'idDepartamento', 'idCargo'
    ];

    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'idDepartamento');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'idCargo');
    }
}
