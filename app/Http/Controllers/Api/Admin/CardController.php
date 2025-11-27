<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Card\CardStoreRequest;
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
        return $this->cardRepository->getAllCards();
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
        return $this->cardRepository->createCard($request->validated());
    }
}
