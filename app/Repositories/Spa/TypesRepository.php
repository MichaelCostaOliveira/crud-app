<?php

namespace App\Repositories\Spa;

use App\Models\Tipos;
use App\Repositories\BaseRepository;

class TypesRepository extends BaseRepository
{

    protected $types;
    public function __construct(Tipos $types)
    {
        parent::__construct($types);
    }
}
