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
                'cargoId'=>$request->has('cargoId') &&  $request->cargoId !=-1 ? $request->cargoId : null,
                'departamentoId'=>$request->has('departamentoId') && $request->departamentoId!=-1 ? $request->departamentoId : null,
            ];
            $usuaiosQ = User::where(function ($query) use ($q) {
                if ($q['s']) {
                    $query->where('usuario', 'like', '%' . $q['s'] . '%');
                }
            });
            if($q['cargoId']){
                $usuaiosQ->whereHas('cargo', function($query) use ($q){
                    $query->where('id', $q['cargoId']);
                });
            }
            if($q['departamentoId']){
                $usuaiosQ->whereHas('departamento', function($query) use ($q){
                    $query->where('id', $q['departamentoId']);
                });
            }
            $usuaiosQ->with(['cargo', 'departamento']);
            if ($q['count']) {
                $usuarios = $usuaiosQ->count();
            } else {
                $usuarios = $usuaiosQ->get();
            }
            $this->apiResponse->addData('usuarios', $usuarios);
            $this->apiResponse->setSuccessMessage('Usuarios obtenidos');
        } catch (\Exception $e) {
            $this->apiResponse->setErrorMessage($e->getMessage());
        }
        return $this->response();
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
                'segundoNombre' => 'required',
                'segundoApellido' => 'required',
                'email' => 'required',
                'idDepartamento' => 'required|exists:departamentos,id',
                'idCargo' => 'required|exists:cargos,id',
            ],
                [
                'usuario.required' => 'El campo usuario es requerido',
                'primerNombre.required' => 'El campo primer nombre es requerido',
                'segundoNombre.required' => 'El campo segundo nombre es requerido',
                'primerApellido.required' => 'El campo primer apellido es requerido',
                'segundoApellido.required' => 'El campo segundo apellido es requerido',
                'email.required' => 'El campo email es requerido',
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
            $usuario->segundoNombre = $request->segundoNombre;
            $usuario->primerApellido = $request->primerApellido;
            $usuario->segundoApellido = $request->segundoApellido;
            $usuario->email = $request->email;
            $usuario->idDepartamento = $request->idDepartamento;
            $usuario->idCargo = $request->idCargo;
            $usuario->save();
            $usuario->load('cargo');
            $usuario->load('departamento');
            DB::commit();
            $this->apiResponse->addData('usuario', $usuario);
            $this->apiResponse->setSuccessMessage('Usuario creado correctamente', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->apiResponse->setErrorMessage($e->getMessage());
        }
        return $this->response();
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
            $this->apiResponse->addData('usuario', $user);
            $this->apiResponse->setSuccessMessage('Usuario obtenido correctamente');
        } catch (\Exception $e) {
            $this->apiResponse->setErrorMessage($e->getMessage());
        }
        return $this->response();
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
                'segundoNombre' => 'required',
                'segundoApellido' => 'required',
                'email' => 'required',
                'idDepartamento' => 'required|exists:departamentos,id',
                'idCargo' => 'required|exists:cargos,id',
            ],
                [
                'usuario.required' => 'El campo usuario es requerido',
                'primerNombre.required' => 'El campo primer nombre es requerido',
                'segundoNombre.required' => 'El campo segundo nombre es requerido',
                'primerApellido.required' => 'El campo primer apellido es requerido',
                'segundoApellido.required' => 'El campo segundo apellido es requerido',
                'email.required' => 'El campo email es requerido',
                'idDepartamento.required' => 'El campo departamento es requerido',
                'idDepartamento.exists' => 'El departamento no existe',
                'idCargo.required' => 'El campo cargo es requerido',
                'idCargo.exists' => 'El cargo no existe',
            ]
            );
            if ($validation->fails()) {
                throw new \Exception($validation->errors()->first());
            }
            $usuario = User::with(['departamento', 'cargo'])->find($id);
            if (!$usuario) {
                throw new \Exception("El usuario no existe");
            }

            $usuario->usuario = $request->usuario;
            $usuario->primerNombre = $request->primerNombre;
            $usuario->segundoNombre = $request->segundoNombre;
            $usuario->primerApellido = $request->primerApellido;
            $usuario->segundoApellido = $request->segundoApellido;
            $usuario->email = $request->email;
            $usuario->idDepartamento = $request->idDepartamento;
            $usuario->idCargo = $request->idCargo;
            $usuario->save();
            $usuario->load('cargo');
            $usuario->load('departamento');
            DB::commit();
            $this->apiResponse->setSuccessMessage('Usuario actualizado correctamente');
            $this->apiResponse->addData('usuario', $usuario);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->apiResponse->setErrorMessage($e->getMessage());
        }
        return $this->response();
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
            $this->apiResponse->setSuccessMessage('Usuario eliminado correctamente');
            $this->apiResponse->addData('usuario', $user);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->apiResponse->setErrorMessage($e->getMessage());
        }
        return $this->response();
    }
}
