<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AbstractResource extends JsonResource
{

    public static $wrap = 'data';
    public $with = ['error' => false];

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);

        //return [
        //    'id' => $this->id,
        //    'marca' => $this->marca
        //];
    }

}
