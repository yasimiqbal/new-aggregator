<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

/**
 * @OA\Tag(
 *     name="Password Reset",
 *     description="Endpoints related to password recovery"
 * )
 */
class ForgetPasswordController extends Controller
{
    /**
     * @var UserService
     */
    private UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Forgot Password
     *
     * @OA\Post(
     *     path="/api/password/forgot",
     *     tags={"Password Reset"},
     *     summary="Send password reset link",
     *     description="Sends a password reset link to the provided email address.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", example="testuser@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset link sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset link sent successfully to your email address.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Failed to send password reset link"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function forgotPassword(ForgetPasswordRequest $request): JsonResponse
    {
        try {
            $response = $this->userService->sendPasswordResetEmail(['email' => $request->email]);
            if ($response === Password::RESET_LINK_SENT) {
                return $this->successResponse('Password reset link sent successfully to your email address.');
            }

            return $this->errorResponse('Failed to send password reset link.', ['error' => $response]);
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred while sending the password reset email.', $e->getMessage());
        }
    }
}
