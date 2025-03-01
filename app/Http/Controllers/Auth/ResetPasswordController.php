<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\V1\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * @param ResetPasswordRequest $request
     * @return JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated(); // Validate before transaction

            DB::beginTransaction();
            $this->userService->resetPassword($validated);
            DB::commit();
            return $this->successResponse( 'Password reset successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
