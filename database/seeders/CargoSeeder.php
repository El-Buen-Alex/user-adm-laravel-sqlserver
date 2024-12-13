<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('ALTER TABLE cargos NOCHECK CONSTRAINT ALL');
        try {
            DB::beginTransaction();
            $cargos = [
                [
                    'nombre' => 'Administrador de Sistemas',
                    'codigo' => 'ADM',
                    'activo' => 1,
                    'idUsuarioCreacion' => -1
                ],
                [
                    'nombre' => 'Contador',
                    'codigo' => 'CON',
                    'activo' => 1,
                    'idUsuarioCreacion' => -1
                ],
                [
                    'nombre' => 'Vendedor',
                    'codigo' => 'VEN',
                    'activo' => 1,
                    'idUsuarioCreacion' => -1
                ]
            ];
            Cargo::insert($cargos);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::statement('ALTER TABLE cargos CHECK CONSTRAINT ALL');
    }
}
