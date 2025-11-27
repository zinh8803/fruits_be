<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'offering_user' => new UserResource($this->whenLoaded('offeringUser')),
            'receiving_user' => new UserResource($this->whenLoaded('receivingUser')),
            'offered_card' => new UserCardResource($this->whenLoaded('offeredCard')),
            'requested_card' => new UserCardResource($this->whenLoaded('requestedCard')),
            //'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
