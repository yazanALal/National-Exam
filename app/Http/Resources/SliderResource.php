<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */

     
    public function toArray($request)
    {

        $urls = $this->url;
        $urlsWithBasePath = [];

        if (is_array($urls)) {
            foreach ($urls as $url) {
                $url['url'] = env('BASE_PATH') . $url['url'];
                $urlsWithBasePath[] = $url;
            }
        } else {
            $urlsWithBasePath = env('BASE_PATH') . $urls;
        }

        return [
            'url'=> $urlsWithBasePath,
            'title'=>$this->title,
            'description'=>$this->description,
            'id'=>$this->id,
            'position'=>$this->position
        ];
    }
}
