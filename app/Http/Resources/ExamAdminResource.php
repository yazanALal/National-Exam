<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamAdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'exam_id'=>$this->id,
            'name'=>$this->name,
            'type'=>$this->type,
            'degree'=>$this->degree,
            'specialty'=>$this->specialty->name
        ];
    }
}
