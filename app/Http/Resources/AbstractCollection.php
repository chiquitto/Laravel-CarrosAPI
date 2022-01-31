<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AbstractCollection extends ResourceCollection
{
    public static $wrap = 'data';
    public $with = ['error' => false];

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        //return [
        //    'data' => $this->collection,
        //    'links' => ['self' => 'link-value',],
        //];
    }
}
