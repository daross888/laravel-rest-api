<?php

namespace App\Services;

use App\Exceptions\NotFoundException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function authenticate(string $email, string $password): ?User
    {
        $user = $this->userRepository->findByEmail($email);
        if (!$user) {
            throw new NotFoundException('Invalid Credentials');
        }

        if (!Hash::check($password, $user->password)) {
            throw new NotFoundException('Invalid Credentials');
        }

        return $user;
    }

    public function register(array $data): User
    {
        if (!$user = $this->userRepository->create($data)) {
            throw new \Error('Could not register user.');
        }

        return $user;
    }

    public function logout(): void
    {
        $user = $this->userRepository->findById(Auth::id());
        if ($user) {
            $user->tokens()->delete();
        }
    }
}
