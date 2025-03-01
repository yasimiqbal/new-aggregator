<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;

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
     * @param ForgetPasswordRequest $request
     * @return JsonResponse
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
