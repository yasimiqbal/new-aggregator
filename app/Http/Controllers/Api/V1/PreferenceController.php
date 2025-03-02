<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PreferenceRequest;
use App\Http\Resources\V1\Preference\PreferenceCollection;
use App\Http\Resources\V1\Preference\PreferenceResource;
use App\Http\Resources\V1\User\UserResource;
use App\Services\V1\PreferenceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PreferenceController extends Controller
{

    /**
     * @var PreferenceService
     */
    private PreferenceService $preferenceService;

    /**
     * @param PreferenceService $preferenceService
     */
    public function __construct(PreferenceService $preferenceService)
    {
        $this->preferenceService = $preferenceService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $preferences = $this->preferenceService->getPreferences();

            $listSize = setDefaultListSize($request->list_size);
            $page = $request->input('page', 1);

            $preferences = $preferences->paginate($listSize, ['*'], 'page', $page);
            $preferences = new PreferenceCollection($preferences);

            $result = ['user' => new UserResource($user), 'preferences' => $preferences];
            return $this->successResponse('User preferences retrieved successfully', $result);
        } catch (\Exception $exception) {
            Log::error($exception->getTraceAsString());
            return $this->errorResponse('Something went wrong', $exception->getTrace());
        }
    }


    /**
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $preference = $this->preferenceService->showPreference($id);
            $preference = new PreferenceResource($preference);
            return $this->successResponse( 'Preference retrieved successfully.', $preference);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param PreferenceRequest $request
     * @return JsonResponse
     */
    public function store(PreferenceRequest $request): JsonResponse
    {
        try {
            $preferences = $this->preferenceService->storePreferences($request);
            return $this->successResponse($preferences, 'Preferences saved successfully.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
