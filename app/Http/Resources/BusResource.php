<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Bus */
class BusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'friday' => $this->friday,
            'saturday' => $this->saturday,
            'sunday' => $this->sunday,
            'amount' => (float)$this->amount,
            'formatted_amount' => $this->formatted_amount,
            'obs' => $this->obs,
            'user_deleted_id' => $this->user_deleted_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'saldo' => $this->saldo,
            'total' => $this->total,
            'passenger_id' => $this->passenger_id,
            'passenger' => $this->passenger,
            'user_created_id' => $this->user_created_id,
            'user_updated_id' => $this->user_updated_id,
        ];
    }
}
