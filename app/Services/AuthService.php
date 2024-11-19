<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthService implements AuthServiceInterface
{
    /**
     * Register a new user.
     *
     * @param array $data The user data.
     * @return array The registered user data.
     */
    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['token' => $token, 'user' => $user];
    }

    /**
     * Logs in a user.
     *
     * @param array $data The user login data.
     * @return array The user data after successful login.
     */
    public function login(array $data): array
    {
        if (!Auth::attempt($data)) {
            throw ValidationException::withMessages(['email' => ['The provided credentials are incorrect.']]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ['token' => $token, 'user' => $user];
    }

    /**
     * Logs out the specified user.
     *
     * @param User $user The user to log out.
     * @return void
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Sends a password reset link to the specified email address.
     *
     * @param string $email The email address to send the password reset link to.
     * @return string The result of the password reset link sending operation.
     */
    public function sendPasswordResetLink(string $email): string
    {
        $status = Password::sendResetLink(['email' => $email]);

        return $status === Password::RESET_LINK_SENT
            ? __('passwords.sent')
            : __('passwords.user');
    }

    /**
     * Reset the password for a user.
     *
     * @param array $data The data containing the necessary information for resetting the password.
     * @return string The message indicating the success or failure of the password reset.
     */
    public function resetPassword(array $data): string
    {
        $status = Password::reset(
            $data,
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? __('passwords.reset')
            : __('passwords.token');
    }
}
