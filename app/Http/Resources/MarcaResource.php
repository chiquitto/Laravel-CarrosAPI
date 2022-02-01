<?php

namespace App\Http\Resources;

class MarcaResource extends AbstractResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'marca' => $this->marca,
        ];
    }
}
