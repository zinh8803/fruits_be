<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\UserCardResource;
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
        try {
            $card = $this->cardRepository->randomCard();
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 429);
        }

        return response()->json([
            'status' => true,
            'message' => 'Lấy card ngẫu nhiên thành công',
            'data' => new CardResource($card),
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/user/cards",
     *     tags={"Card - User"},
     *     summary="Lấy danh sách card của người dùng",
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách card của người dùng",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Card")
     *         )
     *     )
     * )
     */
    public function userCards(Request $request)
    {
        $user_id = $request->user()->id;
        $userCards = $this->cardRepository->userCards($user_id);
        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách card của người dùng thành công',
            'data' => UserCardResource::collection($userCards)
        ], 200);
    }
}
