<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpaRequest;
use App\Repositories\Spa\SpaRepository;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use function response;


class SpaController extends Controller
{

    protected $spaRepository;

    public function __construct(SpaRepository $spaRepository) {
        $this->spaRepository = $spaRepository;
    }

    public function index(Request $request){
        try {
            return json_encode($this->spaRepository->getIncidents());
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(ApplicationRequest $request)
    {
        try {
            if($this->applicationRepository->save(['application_name' => $request->name]))
            {
                return response()->json(['message' => 'Aplicação registrada com Sucesso!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Erro ao registrar Aplicação!'], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(ApplicationRequest $request, $id)
    {
        try {
            if($this->applicationRepository->findOrFail($id)->update(['application_name' => $request->name])){
                return response()->json(['message' => 'Aplicação alterada com Sucesso!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Erro ao alterar Aplicação!'], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            if($this->applicationRepository->findOrFail($id)->delete()){
                return response()->json(['message' => 'Aplicação excluida com Sucesso!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Erro ao excluir Aplicação!'], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function restore($id)
    {
        try {
            if($this->applicationRepository->withTrashed()->findOrFail($id)->restore()){
                return response()->json(['message' => 'Aplicação restaurada com Sucesso!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Erro ao restaurar Aplicação!'], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


}
