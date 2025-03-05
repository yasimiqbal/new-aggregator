<?php

namespace App\Models;

use OpenApi\Annotations as OA;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *     schema="Preference",
 *     type="object",
 *     title="Preference",
 *     description="User preference settings",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="sources", type="array", @OA\Items(type="string"), example={"BBC", "CNN"}),
 *     @OA\Property(property="categories", type="array", @OA\Items(type="string"), example={"Technology", "Health"}),
 *     @OA\Property(property="authors", type="array", @OA\Items(type="string"), example={"John Doe", "Jane Smith"})
 * )
 */
class Preference extends Model
{
    use HasFactory;

    protected $table = 'preferences';
    protected $fillable = ['user_id', 'name', 'type'];
}
