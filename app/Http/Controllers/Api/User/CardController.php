<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Repositories\CardRepository;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Card - User",
 *     description="Quản lý card người dùng"
 * )
 * @OA\PathItem(path="/cards")
 */
class CardController extends Controller
{
    private $cardRepository;
    public function __construct(CardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/user/cards/random",
     *     tags={"Card - User"},
     *     summary="Lấy ngẫu nhiên một card dựa trên tỷ lệ rarity",
     *     @OA\Response(
     *         response=200,
     *         description="Card ngẫu nhiên",
     *         @OA\JsonContent(ref="#/components/schemas/Card")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Không có card nào phù hợp"
     *     )
     * )
     */
    public function randomCard()
    {
        $card = $this->cardRepository->randomCard();
        if ($card) {
            return response()->json([
                'status' => true,
                'message' => 'Lấy card ngẫu nhiên thành công',
                'data' => new CardResource($card)
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Không có card nào phù hợp'
            ], 404);
        }
    }
}
