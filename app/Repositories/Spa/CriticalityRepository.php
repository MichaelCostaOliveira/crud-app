<?php

namespace App\Repositories\Spa;

use App\Models\Criticidades;
use App\Models\Insidentes;
use App\Repositories\BaseRepository;

class CriticalityRepository extends BaseRepository
{

    protected $critical;
    public function __construct(Criticidades $critical)
    {
        parent::__construct($critical);
    }
}
