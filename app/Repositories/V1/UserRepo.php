<?php

namespace App\Repositories\V1;

use App\Models\PasswordResetToken;
use App\Models\User;
use App\Repositories\BaseRepo;
use Illuminate\Support\Facades\DB;

class UserRepo extends BaseRepo
{
    /**
     * @var User
     */
    private User $user;

    /**
     * @var PasswordResetToken
     */
    private PasswordResetToken $token;

    /**
     * @param User $user
     * @param PasswordResetToken $token
     */
    public function __construct(User $user, PasswordResetToken $token)
    {
        parent::__construct($user);
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * @param string $email
     * @param string $token
     * @return object|null
     */
    public function getPasswordResetToken(string $email, string $token): ?object
    {
        return $this->token->where('email', $email)->where('token', $token)->first();
    }

    /**
     * @param string $email
     * @return void
     */
    public function deletePasswordResetToken(string $email): void
    {
        $this->token->where('email', $email)->delete();
    }
}
