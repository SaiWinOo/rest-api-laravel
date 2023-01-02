<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResourceDashboard extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'order_counts' => $this->order_counts_count,
            'featured_image' => asset(Storage::url($this->featured_image)),
            'category' => $this->category,
            'price' => $this->price,
            'description' => $this->description,
            'title_excerpt' => mb_strimwidth($this->title,0,40,'...'),
        ];
    }
}
