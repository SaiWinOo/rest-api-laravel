<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResourceDashboard extends JsonResource
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
            'total_cost' => $this->total_cost,
            'status' => $this->status,
            'voucher' => $this->voucher,
            'time' => $this->created_at->format('d/M/Y') .  ' ' . $this->created_at->format('h:i A'),
            'user' => new UserResource($this->user),
        ];
    }
}
