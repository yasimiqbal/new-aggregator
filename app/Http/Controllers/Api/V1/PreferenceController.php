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
use OpenApi\Annotations as OA;


/**
 * @OA\Tag(
 *     name="Preferences",
 *     description="API endpoints for user preferences"
 * )
 *
 * @OAS\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 *  )
 */
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
     * @OA\Get(
     *     path="/api/preferences",
     *     tags={"Preferences"},
     *     summary="Get user preferences",
     *     security={{"bearerAuth":{}}},
     *     description="Retrieve the authenticated user along with their preferences.",
     *     @OA\Parameter(
     *         name="list_size",
     *         in="query",
     *         description="Number of preferences per page",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User and preferences list",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="preferences", ref="#/components/schemas/PreferenceCollection")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Something went wrong")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/preferences/{id}",
     *     tags={"Preferences"},
     *     summary="Get a preference by ID",
     *     security={{"bearerAuth":{}}},
     *     description="Retrieve a specific preference using its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the preference",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Preference retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PreferenceResource")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Preference not found",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Preference not found"))
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $preference = $this->preferenceService->showPreference($id);
            if (!$preference) {
                return $this->notFoundResponse('Preference not found');
            }
            $preference = new PreferenceResource($preference);
            return $this->successResponse('Preference retrieved successfully.', $preference);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/preferences",
     *     tags={"Preferences"},
     *     summary="Create user preferences",
     *     security={{"bearerAuth":{}}},
     *     description="Store user preferences for sources, categories, and authors.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"sources", "categories", "authors"},
     *             @OA\Property(property="sources", type="array", @OA\Items(type="string"), example={"BBC", "CNN"}),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string"), example={"Technology", "Health"}),
     *             @OA\Property(property="authors", type="array", @OA\Items(type="string"), example={"John Doe", "Jane Smith"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Preferences saved successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="user", ref="#/components/schemas/UserResource"),
     *              @OA\Property(property="preferences", ref="#/components/schemas/PreferenceCollection")
     *          )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The sources field is required."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Something went wrong"))
     *     )
     * )
     */
    public function store(PreferenceRequest $request): JsonResponse
    {
        try {
            $this->preferenceService->storePreferences($request->all());
            $user = Auth::user();
            $preferences = $this->preferenceService->getPreferences();
            $preferences = new PreferenceCollection($preferences->get());

            $result = [
                'user' => new UserResource($user),
                'preferences' => $preferences,
            ];
            return $this->successResponse('Preferences saved successfully.', $result, 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
