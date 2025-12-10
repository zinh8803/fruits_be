<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     required={"id", "name", "email", "role"},
 *     @OA\Property(property="id", type="integer", format="int64", example=1, description="ID của người dùng"),
 *     @OA\Property(property="name", type="string", example="Nguyen Van A", description="Tên của người dùng"),
 *     @OA\Property(property="email", type="string", format="email", example="nguyenvana@example.com", description="Email của người dùng"),
 *     @OA\Property(property="image_url", type="string", format="url", example="https://example.com/images/nguyenvana.jpg", description="URL ảnh đại diện của người dùng"),
 *     @OA\Property(property="role", type="string", example="user", description="Vai trò của người dùng")
 * )
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'image_url' => $this->image_url ? url('storage/' . $this->image_url) : null,
            'role' => $this->role,
        ];
    }
}
