<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Card",
 *     type="object",
 *     title="Card",
 *     required={"id", "name", "stars", "description", "rarity", "image_url"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Dragon Fruit"),
 *     @OA\Property(property="stars", type="integer", example=5),
 *     @OA\Property(property="description", type="string", example="A rare and exotic fruit card."),
 *     @OA\Property(property="rarity", type="string", example="Legendary"),
 *     @OA\Property(property="image_url", type="string", format="url", example="https://example.com/images/dragon_fruit.png"),
 * )
 */
class CardResource extends JsonResource
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
            'name' => $this->name,
            'stars' => $this->stars,
            'description' => $this->description,
            'rarity' => $this->rarity,
            'image_url' => 'http://127.0.0.1:8000/storage/'.$this->image_url,
        ];
    }
}
