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
     * @param Request $request
     * @return JsonResponse
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
