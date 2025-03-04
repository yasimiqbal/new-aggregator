<?php

namespace App\Services\V1;

use App\Mappers\UserMapper;
use App\Repositories\V1\UserRepo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Password;

class UserService
{
    /**
     * @var UserRepo
     */
    private UserRepo $userRepo;

    /**
     * @var UserMapper
     */
    private UserMapper $userMapper;

    /**
     * @param UserRepo $userRepo
     * @param UserMapper $userMapper
     */
    public function __construct(UserRepo $userRepo, UserMapper $userMapper)
    {
        $this->userRepo = $userRepo;
        $this->userMapper = $userMapper;
    }

    /**
     * @param array $params
     * @return array
     */
    public function register(array $params): array
    {
        $data = $this->userMapper->mapRegisterUser($params);
        $user = $this->userRepo->create($data);

        $token = $user->createToken('API Token')->plainTextToken;

        return ['user' => $user, 'token' => $token, 'token_type' => 'Bearer'];
    }

    /**
     * @param mixed $params
     * @return mixed
     * @throws \Exception
     */
    public function login(mixed $params): mixed
    {
        $user = $this->userRepo->findByClause(['email' => $params['email']])->first();
        if (!$user || !Hash::check($params['password'], $user->password)) {
            throw new \Exception('Invalid credentials.', 422);
        }

        $token = $user->createToken('API Token')->plainTextToken;
        return ['user' => $user, 'token' => $token];
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function logout(): void
    {
        if(!Auth::check()) {
            throw new \Exception('Unauthorized', 401);
        }
        Auth::user()->tokens()->delete();
    }


    /**
     * @param array $data
     * @return string
     */
    public function sendPasswordResetEmail(array $data): string
    {
        return Password::sendResetLink($data);
    }

    /**
     * @param array $params
     * @return void
     * @throws \Exception
     */
    public function resetPassword(array $params): void
    {
        $resetToken = $this->userRepo->getPasswordResetToken($params['email'], $params['token']);
        if (!$resetToken) {
            throw new \Exception('Invalid or expired token.');
        }

        if (Carbon::parse($resetToken->created_at)->addMinutes(60)->isPast()) {
            throw new \Exception('Token expired.');
        }

        $user = $this->userRepo->findByClause(['email' => $params['email']])->first();
        if (!$user) {
            throw new \Exception('User not found.');
        }
        $user->update(['password' => Hash::make($params['password'])]);

        $this->userRepo->deletePasswordResetToken($params['email']);
    }

}
