<?php

namespace App\Repositories\V1;

use App\Models\Preference;
use App\Repositories\BaseRepo;

class PreferenceRepo extends BaseRepo
{
    /**
     * @var Preference
     */
    private Preference $preference;

    /**
     * @param Preference $preference
     */
    public function __construct(Preference $preference)
    {
        parent::__construct($preference);
        $this->preference = $preference;
    }

}
