<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'featured_image' => asset(Storage::url($this->featured_image)),
            'price' => $this->price,
            'photos' => ProductPhotoResource::collection($this->photos),
            'category' => $this->category,
            'title_excerpt' => mb_strimwidth($this->title,0,80,'...'),
            'order_counts' => $this->order_counts_count,
        ];
    }
}
