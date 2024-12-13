<?php

namespace Database\Seeders;

use App\Models\Departamento;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('ALTER TABLE departamentos NOCHECK CONSTRAINT ALL');
        try {
            DB::beginTransaction();
            $departamentos = [
                [
                    'nombre' => 'Sistemas',
                    'codigo' => 'SIS',
                    'activo' => 1,
                    'idUsuarioCreacion' => -1
                ],
                [
                    'nombre' => 'Contabilidad',
                    'codigo' => 'CON',
                    'activo' => 1,
                    'idUsuarioCreacion' => -1
                ],
                [
                    'nombre' => 'Ventas',
                    'codigo' => 'VEN',
                    'activo' => 1,
                    'idUsuarioCreacion' => -1
                ]
            ];
            Departamento::insert($departamentos);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        DB::statement('ALTER TABLE departamentos CHECK CONSTRAINT ALL');
    }
}
