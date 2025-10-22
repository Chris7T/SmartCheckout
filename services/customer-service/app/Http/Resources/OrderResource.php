<?php

namespace App\Http\Resources;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'value' => $this->value,
            'liquid_value' => $this->liquid_value,
            'status_id' => $this->status_id,
            'status' => OrderStatusEnum::tryFrom($this->status_id)?->label(),
            'payment_status_id' => $this->payment_status_id,
            'payment_status' => PaymentStatusEnum::tryFrom($this->payment_status_id)?->label(),
            'carts' => $this->carts ? CartResource::collection($this->carts) : [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
