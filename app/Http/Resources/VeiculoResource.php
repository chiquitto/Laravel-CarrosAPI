<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Storage;

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
            // 'figura' => $this->when($this->figura, Storage::disk('public')->url($this->figura), null),
            'figura' => $this->figura,
            'ownerid' => $this->ownerid,
        ];
    }
}
