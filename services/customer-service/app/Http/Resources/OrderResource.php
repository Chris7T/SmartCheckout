<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'value' => $this->value,
            'liquid_value' => $this->liquid_value,
            'status_id' => $this->status_id->value,
            'status' => strtolower($this->status_id->label()),
            'payment_status_id' => $this->payment_status_id->value,
            'payment_status' => strtolower($this->payment_status_id->label()),
            'carts' => CartResource::collection($this->whenLoaded('carts')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
