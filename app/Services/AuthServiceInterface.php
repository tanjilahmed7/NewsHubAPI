<?php

namespace App\Services;

use App\Models\User;

interface AuthServiceInterface
{
    public function register(array $data): array;
    public function login(array $data): array;
    public function logout(User $user): void;
    public function sendPasswordResetLink(string $email): string;
    public function resetPassword(array $data): string;
}
