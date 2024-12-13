<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
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
            $cargosQ = Cargo::where(function ($query) use ($q) {
                if ($q['s']) {
                    $query->where('nombre', 'like', '%' . $q['s'] . '%');
                }
            });
            if ($q['count']) {
                $cargos = $cargosQ->count();
            } else {
                $cargos = $cargosQ->get();
            }
            return response()->json([
                'data' => [
                    'cargos' => $cargos
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
