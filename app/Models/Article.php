<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Article",
 *     type="object",
 *     title="Article",
 *     description="Article model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Breaking News"),
 *     @OA\Property(property="description", type="string", example="This is a breaking news article."),
 *     @OA\Property(property="url", type="string", format="url", example="https://example.com/article"),
 *     @OA\Property(property="category", type="string", example="Technology"),
 *     @OA\Property(property="source", type="string", example="Tech News"),
 *     @OA\Property(property="author", type="string", example="John Doe"),
 *     @OA\Property(property="published_at", type="string", format="date-time", example="2025-03-06 12:00:00")
 * )
 */
class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';
    protected $fillable = ['title', 'description', 'url', 'category', 'source', 'author', 'published_at'];
}
