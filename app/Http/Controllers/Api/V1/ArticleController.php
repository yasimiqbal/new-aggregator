<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Article\ArticleCollection;
use App\Http\Resources\V1\Article\ArticleResource;
use App\Services\V1\ArticleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="News Aggregator API",
 *      description="API documentation for News Aggregator application"
 * )
 *
 * @OA\Tag(
 *     name="Articles",
 *     description="API endpoints for managing articles"
 * )
 *
 * @OAS\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 *
 * @OA\SecurityRequirement(
 *     security={{"bearerAuth":{}}}
 * )
 */
class ArticleController extends Controller
{
    public ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * @OA\Get(
     *     path="/api/articles",
     *     tags={"Articles"},
     *     summary="Get all articles",
     *     security={{"bearerAuth":{}}},
     *     description="Retrieve a paginated list of articles.",
     *     @OA\Parameter(
     *         name="list_size",
     *         in="query",
     *         description="Number of articles per page",
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
     *         description="List of articles",
     *         @OA\Property(property="data", ref="#/components/schemas/ArticleCollection")
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
     * @OA\Get(
     *     path="/api/articles/{id}",
     *     tags={"Articles"},
     *     summary="Get an article by ID",
     *     security={{"bearerAuth":{}}},
     *     description="Retrieve a specific article using its ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the article",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Article retrieved successfully",
     *        @OA\Property(property="data", type="array",
     *                  @OA\Items(ref="#/components/schemas/ArticleResource")
     *        )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Article not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Article not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Failed to retrieve the article",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to retrieve the article")
     *         )
     *     )
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $article = $this->articleService->getArticleById($id);
            if(!$article) {
                return $this->notFoundResponse('Article not found');
            }

            $article = new ArticleResource($article);
            return $this->successResponse('Article retrieved successfully', $article);
        } catch (ModelNotFoundException $e) {
            return $this->notFoundResponse('Article not found');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve the article', $e->getMessage());
        }
    }
}
