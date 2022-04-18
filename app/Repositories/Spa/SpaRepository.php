<?php

namespace App\Repositories\Spa;

use Exception;
use App\Models\Insidentes;
use App\Repositories\BaseRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SpaRepository extends BaseRepository
{

    protected $incident;
    public function __construct(Insidentes $incident)
    {
        $this->incident = $incident;
        parent::__construct($incident);
    }

    public function getIncidents()
    {
        $this->model = $this->relationships(['insidetesCriticidade', 'insidetesTipos']);
        $data = $this->model->get()->toArray();
        return $data;
    }


    public function verifyColumns($value)
    {
        return Schema::hasColumn('application_origin', $value);
    }

}
