<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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
            $cargosQ = User::where(function ($query) use ($q) {
                if ($q['s']) {
                    $query->where('usuario', 'like', '%' . $q['s'] . '%');
                }
            });
            if ($q['count']) {
                $cargos = $cargosQ->count();
            } else {
                $cargos = $cargosQ->get();
            }
            return response()->json([
                'data' => [
                    'usuarios' => $cargos
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los cargos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validation = Validator::make(
                $request->all(),
                [
                'usuario' => 'required',
                'primerNombre' => 'required',
                'primerApellido' => 'required',
                'segundoApellido' => 'required',
                'idDepartamento' => 'required|exists:departamentos,id',
                'idCargo' => 'required|exists:cargos,id',
            ],
                [
                'usuario.required' => 'El campo usuario es requerido',
                'primerNombre.required' => 'El campo primer nombre es requerido',
                'primerApellido.required' => 'El campo primer apellido es requerido',
                'segundoApellido.required' => 'El campo segundo apellido es requerido',
                'idDepartamento.required' => 'El campo departamento es requerido',
                'idDepartamento.exists' => 'El departamento no existe',
                'idCargo.required' => 'El campo cargo es requerido',
                'idCargo.exists' => 'El cargo no existe',
            ]
            );
            if ($validation->fails()) {
                throw new \Exception($validation->errors()->first());
            }
            $usuario = new User();
            $usuario->usuario = $request->usuario;
            $usuario->primerNombre = $request->primerNombre;
            $usuario->primerApellido = $request->primerApellido;
            $usuario->segundoApellido = $request->segundoApellido;
            $usuario->idDepartamento = $request->idDepartamento;
            $usuario->idCargo = $request->idCargo;
            $usuario->save();
            DB::commit();
            return response()->json([
                'message' => 'Usuario creado correctamente',
                'data' => $usuario
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::with(['departamento', 'cargo'])->find($id);
            if (!$user) {
                throw new \Exception("El usuario no existe");
            }
            return response()->json([
                'data' => [
                    'usuario' => $user
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $validation = Validator::make(
                $request->all(),
                [
                'usuario' => 'required',
                'primerNombre' => 'required',
                'primerApellido' => 'required',
                'segundoApellido' => 'required',
                'idDepartamento' => 'required|exists:departamentos,id',
                'idCargo' => 'required|exists:cargos,id',
            ],
                [
                'usuario.required' => 'El campo usuario es requerido',
                'primerNombre.required' => 'El campo primer nombre es requerido',
                'primerApellido.required' => 'El campo primer apellido es requerido',
                'segundoApellido.required' => 'El campo segundo apellido es requerido',
                'idDepartamento.required' => 'El campo departamento es requerido',
                'idDepartamento.exists' => 'El departamento no existe',
                'idCargo.required' => 'El campo cargo es requerido',
                'idCargo.exists' => 'El cargo no existe',
            ]
            );
            if ($validation->fails()) {
                throw new \Exception($validation->errors()->first());
            }
            $user = User::with(['departamento', 'cargo'])->find($id);
            if (!$user) {
                throw new \Exception("El usuario no existe");
            }

            $user->usuario = $request->usuario;
            $user->primerNombre = $request->primerNombre;
            $user->primerApellido = $request->primerApellido;
            $user->segundoApellido = $request->segundoApellido;
            $user->idDepartamento = $request->idDepartamento;
            $user->idCargo = $request->idCargo;
            $user->save();
            DB::commit();
            return response()->json([
                'message' => 'Usuario actualizado correctamente',
                'data' => [
                    'usuario' => $user
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::with(['departamento', 'cargo'])->find($id);
            if (!$user) {
                throw new \Exception("El usuario no existe");
            }
            $departamentos = Departamento::where('idUsuarioCreacion', $id)->count();
            if ($departamentos > 0) {
                throw new \Exception("El usuario tiene departamentos creados");
            }
            $cargos = Cargo::where('idUsuarioCreacion', $id)->count();
            if ($cargos > 0) {
                throw new \Exception("El usuario tiene cargos creados");
            }
            $user->delete();
            DB::commit();
            return response()->json([
                'message' => 'Usuario eliminado correctamente',
                'data' => [
                    'usuario' => $user
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
