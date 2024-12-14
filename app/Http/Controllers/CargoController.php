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
                'forSearchs'=> $request->has('forSearchs') ? $request->forSearchs : null,
            ];
            $cargosQ = Cargo::where(function ($query) use ($q) {
                if ($q['s']) {
                    $query->where('nombre', 'like', '%' . $q['s'] . '%');
                }
            });
            if ($q['count']) {
                $cargos = $cargosQ->count();
            } else {
                $cargos = $cargosQ->get()->toArray();
                $aux=[];
                if($q['forSearchs']==1){
                    $aux=[
                        'id'=>-1,
                        'nombre'=>'Todos'
                    ];
                    array_unshift($cargos, $aux);
                }
            }
            $this->apiResponse->addData('cargos', $cargos);
            $this->apiResponse->setSuccessMessage('Cargos obtenidos');
        } catch (\Exception $e) {
            $this->apiResponse->setErrorMessage($e->getMessage());
        }
        return $this->response();
    }
}
