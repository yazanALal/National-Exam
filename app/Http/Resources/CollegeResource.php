<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollegeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'college_uuid'=>$this->uuid,
            'type'=>$this->type,
            'image'=> env('BASE_PATH') . $this->image,
            'name'=>$this->name,
        ];
    }   
}
