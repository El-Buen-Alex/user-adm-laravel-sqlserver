<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\Request;

class DepartamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $q = [
                'count' => $request->has('count') ? $request->count : null,
                's' => $request->has('s') ? $request->s : null,
            ];
            $departamentosQ = Departamento::where(function ($query) use ($q) {
                if ($q['s']) {
                    $query->where('nombre', 'like', '%' . $q['s'] . '%');
                }
            });
            if ($q['count']) {
                $departamentos = $departamentosQ->count();
            } else {
                $departamentos = $departamentosQ->get();
            }
            return response()->json([
                'data' => [
                    'cargos' => $departamentos
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los cargos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
