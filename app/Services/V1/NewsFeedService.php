<?php

namespace App\Services\V1;

use App\Repositories\V1\ArticleRepo;
use App\Repositories\V1\PreferenceRepo;
use Illuminate\Support\Facades\Auth;
use PreferenceTypes;

class NewsFeedService
{
    /**
     * @var ArticleRepo
     */
    private ArticleRepo $articleRepo;

    /**
     * @var PreferenceRepo
     */
    private PreferenceRepo $preferenceRepo;


    public function __construct(ArticleRepo $articleRepo, PreferenceRepo $preferenceRepo)
    {
        $this->articleRepo = $articleRepo;
        $this->preferenceRepo = $preferenceRepo;
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function getUserNewsFeed(): mixed
    {
        $userId = Auth::id();
        $preferences = $this->preferenceRepo->findByClause(['user_id' => $userId])->get();

        if (empty($preferences)) {
            throw new \Exception('No preferences found for this user.');
        }

        $typeMappings = [
            PreferenceTypes::SOURCE => 'sources',
            PreferenceTypes::CATEGORY => 'categories',
            PreferenceTypes::AUTHOR => 'authors',
        ];


        $userPreferences = [];
        foreach ($preferences as $preference) {
            $typeKey = $typeMappings[$preference->type] ?? null;
            if ($typeKey) {
                $userPreferences[$typeKey][] = $preference->name;
            }
        }

        return $this->articleRepo->fetchUserNewsFeed($userPreferences);
    }
}
