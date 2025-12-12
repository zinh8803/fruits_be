<?php

namespace App\Http\Requests\Card;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *   schema="CardUpdateRequest",
 *   type="object",
 *   format="multipart",
 *  @OA\Property(property="name", type="string", example="Dragon Fruit"),
 *  @OA\Property(property="stars", type="integer", example=5),
 *  @OA\Property(property="description", type="string", example="A rare and exotic fruit card."),
 *  @OA\Property(property="rarity", type="string", example="Legendary"),
 *  @OA\Property(property="image_url", type="string", format="binary", description="Upload image file"),
 * )
 */
class CardUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'stars' => 'sometimes|required|integer|min:1|max:5',
            'description' => 'nullable|string',
            'rarity' => 'sometimes|required|string|max:100',
            'image_url' => 'nullable|file|image|max:2048', // đổi từ url sang file image
        ];
    }
}
