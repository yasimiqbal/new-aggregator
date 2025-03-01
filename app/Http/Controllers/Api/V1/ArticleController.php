<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Article\ArticleCollection;
use App\Http\Resources\V1\Article\ArticleResource;
use App\Services\V1\ArticleService;
use App\Services\V1\NewsService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{

    public ArticleService $articleService;

    public function __construct(ArticleService $articleService, private NewsService $newsService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @param Request $request
     * @return ArticleCollection|JsonResponse
     */
    public function index(Request $request): JsonResponse|ArticleCollection
    {
        try {
            $articles = $this->articleService->getArticles($request);

            $listSize = setDefaultListSize($request->list_size);
            $page = $request->input('page', 1);

            $articles = $articles->paginate($listSize, ['*'], 'page', $page);
            $articles = new ArticleCollection($articles);
            return $this->successResponse('Articles retrieved successfully', $articles);
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
            $article = $this->articleService->getArticleById($id);
            $article = new ArticleResource($article);
            return $this->successResponse($article, 'Article retrieved successfully');
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Article not found');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve the article', $e->getMessage());
        }
    }

//    public function fetchNews()
//    {
//        $this->newsService->fetchAndStoreArticles();
//        return $this->successResponse('News Articles Fetched Successfully');
//    }

}
