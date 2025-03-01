<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(): JsonResponse
    {
        return $this->notVerified('Unauthorized');
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $response = $this->userService->login($request->all());
            return $this->successResponse('User successfully logged in', $response);
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->getMessage());
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $this->userService->logout();
            return $this->successResponse('User successfully logged out');
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during logout', $e->getMessage());
        }
    }
}
