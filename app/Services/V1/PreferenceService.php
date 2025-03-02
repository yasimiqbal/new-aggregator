<?php

namespace App\Services\V1;

use App\Repositories\V1\PreferenceRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PreferenceTypes;

class PreferenceService
{

    /**
     * @var PreferenceRepo
     */
    private PreferenceRepo $preferenceRepo;

    /**
     * @param PreferenceRepo $preferenceRepo
     */
    public function __construct(PreferenceRepo $preferenceRepo)
    {
        $this->preferenceRepo = $preferenceRepo;
    }

    /**
     * @return mixed
     */
    public function getPreferences(): mixed
    {
        $userId = Auth::id();
        return $this->preferenceRepo->findByClause(['user_id' => $userId]);
    }

    /**
     * @param $id
     * @return object
     */
    public function showPreference($id): object
    {
        return $this->preferenceRepo->find($id);
    }

    /**
     * @param $params
     * @return bool
     * @throws \Exception
     */
    public function storePreferences($params): bool
    {
        try {
            DB::beginTransaction();
            $userId = Auth::id();

            $preferenceTypes = [
                'sources' => PreferenceTypes::SOURCE,
                'categories' => PreferenceTypes::CATEGORY,
                'authors' => PreferenceTypes::AUTHOR,
            ];

            foreach ($preferenceTypes as $key => $type) {
                if (!empty($params[$key])) {
                    foreach ($params[$key] as $value) {
                        $this->preferenceRepo->updateOrCreate(
                            ['name' => $value, 'user_id' => $userId, 'type' => $type],
                            ['name' => $value, 'user_id' => $userId, 'type' => $type]
                        );
                    }
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info('Preferences store error: ' . $exception->getMessage());
            throw new \Exception($exception->getMessage());
        }
    }
}
