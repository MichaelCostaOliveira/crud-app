<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpaRequest;
use App\Repositories\Spa\CriticalityRepository;
use App\Repositories\Spa\IncidentRepository;
use App\Repositories\Spa\TypesRepository;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use function response;


class SpaController extends Controller
{

    protected $incidentRepository;
    protected $typesRepository;
    protected $criticalityRepository;

    public function __construct(IncidentRepository $incidentRepository,
                                TypesRepository $typesRepository,
                                CriticalityRepository $criticalityRepository
    ) {
        $this->incidentRepository = $incidentRepository;
        $this->typesRepository = $typesRepository;
        $this->criticalityRepository = $criticalityRepository;
    }

    public function index(){
        $types = $this->typesRepository->all()->toArray();
        $criticality = $this->criticalityRepository->all()->toArray();
        return view('spa.index', [
            'types' => $types,
            'criticality' => $criticality
        ]);
    }

    public function getIncident(Request $request){
        try {
            return json_encode($this->incidentRepository->getIncidents());
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function edit($id){
        $incident  = $this->incidentRepository->findOrFail($id);
        $types = $this->typesRepository->all()->toArray();
        $criticality = $this->criticalityRepository->all()->toArray();
        return view('spa.edit',  [
            'incident' => $incident,
            'types' => $types,
            'criticality' => $criticality
        ]);
    }

    public function store(SpaRequest $request)
    {

        try {
            if($this->incidentRepository->save([
                'titulo' => $request->titulo,
                'criticidade_id' => $request->criticidade,
                'tipo_id' => $request->tipo,
                'status' => $request->status,
                'descricao' => $request->descricao,
            ]))
            {
                return response()->json(['message' => 'Incidente registrado com Sucesso!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Erro ao registrar Incidente!'], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {

            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if($this->incidentRepository->findOrFail($id)->update([
                'titulo' => $request->titulo,
                'criticidade_id' => $request->criticidade,
                'tipo_id' => $request->tipo,
                'status' => $request->status,
                'descricao' => $request->descricao,
            ])){
                return response()->json(['message' => 'Incidente alterado com Sucesso!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Erro ao alterar Incidente!'], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            if($this->incidentRepository->findOrFail($id)->delete()){
                return response()->json(['message' => 'Incidente excluido com Sucesso!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Erro ao excluir Incidente!'], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function restore($id)
    {
        try {
            if($this->incidentRepository->withTrashed()->findOrFail($id)->restore()){
                return response()->json(['message' => 'Incidente restaurado com Sucesso!'], Response::HTTP_OK);
            }
            return response()->json(['message' => 'Erro ao restaurar Incidente!'], Response::HTTP_BAD_REQUEST);
        } catch(\Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }


}
