<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\CardStoreRequest;
use App\Http\Resources\CardResource;
use App\Repositories\CardRepository;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Card",
 *     description="Quản lý card"
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
     *     path="/api/admin/cards",
     *     tags={"Card"},
     *     summary="Lấy danh sách card",
     *     @OA\Response(
     *         response=200,
     *         description="Danh sách card",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Card"))
     *     )
     * )
     */
    public function index()
    {
        return CardResource::collection($this->cardRepository->getAllCards());
    }

    /**
     * @OA\Get(
     *     path="/api/admin/cards/{id}",
     *     tags={"Card"},
     *     summary="Lấy thông tin chi tiết của một card",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID của card",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Thông tin chi tiết của card",
     *         @OA\JsonContent(ref="#/components/schemas/Card")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Card không tìm thấy"
     *     )
     * )
     */
    public function show($id)
    {
        $card = $this->cardRepository->getCardById($id);
        if (!$card) {
            return response()->json(['message' => 'Card not found'], 404);
        }
        return new CardResource($card);
    }
    /**
     * @OA\Post(
     *     path="/api/admin/cards",
     *     tags={"Card"},
     *     summary="Tạo mới card",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/CardStoreRequest")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Card được tạo thành công",
     *         @OA\JsonContent(ref="#/components/schemas/Card")
     *     )
     * )
     */
    public function store(CardStoreRequest $request)
    {
        $card = $this->cardRepository->createCard($request->validated());
        return (new CardResource($card))->response()->setStatusCode(201);
    }
}
