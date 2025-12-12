<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreAuthRequest;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Tag(
 *     name="Auth",
 *     description="Quản lý xác thực người dùng"
 * )
 * @OA\PathItem(path="/auth")
 */
class AuthController extends Controller
{
    private $authRepository;
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Auth"},
     *     summary="Đăng ký người dùng mới",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Nguyen Van A"),
     *             @OA\Property(property="email", type="string", format="email", example="nguyenvana@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Đăng ký thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Yêu cầu không hợp lệ"
     *     )
     * )
     */
    public function register(StoreAuthRequest $request)
    {
        $data = $request->only(['name', 'email', 'password']);
        $user = $this->authRepository->registerUser($data);
        $token = JWTAuth::fromUser($user);
        // $refreshToken = Str::random(60);

        // RefreshToken::create([
        //     'token' => $refreshToken,
        //     'user_id' => $user->id,
        //     'expires_at' => now()->addDays(30),
        //     'ip_address' => request()->ip(),
        //     'user_agent' => request()->header('User-Agent'),
        // ]);
        $accessCookie = cookie('access_token', $token, 60);
        return response()->json(['user' => $user, 'token' => $token], 201)->cookie($accessCookie);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Đăng nhập người dùng",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="nguyenvana@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Đăng nhập thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Đăng nhập thành công")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Email hoặc mật khẩu không đúng",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Email hoặc mật khẩu không đúng")
     *         )
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        Log::info('email: ' . $credentials['email'] . ', password: ' . $credentials['password']);
        Log::info('Login attempt with credentials: ' . json_encode($credentials));
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Email hoặc mật khẩu không đúng',
            ], 401);
        }
        //  $user = Auth::guard('api')->user();
        $accessCookie = cookie('access_token', $token, 60);
        Log::info('Generated token: ' . $token);
        return response()->json([
            'status' => true,
            'message' => 'Đăng nhập thành công'
        ], 200)->cookie($accessCookie);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Auth"},
     *     summary="Đăng xuất người dùng",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Đăng xuất thành công",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Đăng xuất thành công")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        $this->authRepository->logout();
        // $accessCookie = cookie('access_token', '', -1, '/', 'localhost', false, true, true, 'Lax');
        $accessCookie = cookie('access_token', '', -1);
        return response()->json([
            'status' => true,
            'message' => 'Đăng xuất thành công'
        ], 200)->cookie($accessCookie);
    }
}
