<?php

namespace App\Http\Resources;

class VeiculoResource extends AbstractResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'marca_id' => $this->marca_id,
            'placa' => $this->placa,
            'modelo' => $this->modelo,
            'ano' => $this->ano,
            'ownerid' => $this->ownerid,
        ];
    }
}
