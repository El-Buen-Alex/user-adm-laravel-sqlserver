<?php

namespace Database\Seeders;

use App\Models\Cargo;
use App\Models\Departamento;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $departamento = Departamento::where('nombre', 'Sistemas')->first();
        $cargo = Cargo::where('nombre', 'Administrador de Sistemas')->first();
        DB::statement("SET IDENTITY_INSERT users ON");
        $user = User::create([
            'usuario' => 'alexp',
            'primerNombre' => 'Alex',
            'segundoNombre' => 'David',
            'primerApellido' => 'Perez',
            'segundoApellido' => 'Saldaña',
            'email' => 'sistemas@prueba.com',
            'idDepartamento' => $departamento->id,
            'idCargo' => $cargo->id,
        ]);
        DB::statement("SET IDENTITY_INSERT users OFF");
        Departamento::where('idUsuarioCreacion', -1)->update(['idUsuarioCreacion' => $user->id]);
        Cargo::where('idUsuarioCreacion', -1)->update(['idUsuarioCreacion' => $user->id]);
        DB::commit();
    }
}
