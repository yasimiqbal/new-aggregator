<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Article\ArticleCollection;
use App\Services\V1\NewsFeedService;
use Illuminate\Database\RecordNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsFeedController extends Controller
{

    /**
     * @var NewsFeedService
     */
    private NewsFeedService $newsFeedService;

    /**
     * @param NewsFeedService $newsFeedService
     */
    public function __construct(NewsFeedService $newsFeedService)
    {
        $this->newsFeedService = $newsFeedService;
    }

    /**
     * @OA\Get(
     *     path="/api/news/feed",
     *     tags={"News Feed"},
     *     summary="Get user news feed",
     *     description="Retrieves a paginated list of articles based on the user's preferences.",
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="list_size",
     *         in="query",
     *         description="Number of articles per page (default: system-defined)",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User articles retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User articles retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ArticleCollection")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No preferences found for this user",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No preferences found for this user.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Something went wrong"
     *     )
     * )
     */
    public function newsFeed(Request $request): JsonResponse
    {
        try {
            $newsFeed = $this->newsFeedService->getUserNewsFeed();

            $listSize = setDefaultListSize($request->list_size);
            $page = $request->input('page', 1);

            $newsFeed = $newsFeed->paginate($listSize, ['*'], 'page', $page);
            $newsFeed = new ArticleCollection($newsFeed);
            return $this->successResponse('User articles retrieved successfully', $newsFeed);
        } catch (RecordNotFoundException $exception) {
            Log::error($exception->getTraceAsString());
            return $this->notFoundResponse('No preferences found for this user.');
        } catch (\Exception $exception) {
            Log::error($exception->getTraceAsString());
            return $this->errorResponse('Something went wrong', $exception->getTrace());
        }
    }
}
