<?php

namespace App\Services;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Register a new user.
     *
     * @param array $data The user data.
     * @return array The registered user data.
     */
    public function register(array $data): array;
    /**
     * Logs in a user.
     *
     * @param array $data The user login data.
     * @return array The user data after successful login.
     */
    public function login(array $data): array;
    /**
     * Logout the user.
     *
     * @param User $user The user to logout.
     * @return void
     */
    public function logout(User $user): void;
    /**
     * Sends a password reset link to the specified email address.
     *
     * @param string $email The email address to send the password reset link to.
     * @return string The result of the password reset link sending operation.
     */
    public function sendPasswordResetLink(string $email): string;
    /**
     * Resets the password for a user.
     *
     * @param array $data The data containing the necessary information for resetting the password.
     * @return string The message indicating the success or failure of the password reset.
     */
    public function resetPassword(array $data): string;
}
