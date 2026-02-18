<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @group User Profile
 *
 * APIs for managing user profile
 */
class ProfileController extends Controller
{
    /**
     * Update profile information
     *
     * Update the authenticated user's profile information.
     *
     * @authenticated
     * @bodyParam name string required The new name. Example: John Doe
     * @bodyParam email string required The new email. Example: john@example.com
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json([
            'status' => 'profile-updated',
            'user' => $user,
        ]);
    }

    /**
     * Update password
     *
     * Update the authenticated user's password.
     *
     * @authenticated
     * @bodyParam current_password string required The current password. Example: password
     * @bodyParam password string required The new password. Example: newpassword
     * @bodyParam password_confirmation string required The new password confirmation. Example: newpassword
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'status' => 'password-updated',
        ]);
    }
}
