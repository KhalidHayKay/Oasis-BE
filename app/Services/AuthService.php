<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\AccountCreated;
use App\Models\SocialAccount;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\DTOs\AuthResponse;
use App\Exceptions\EmailNotVerifiedException;
use App\Exceptions\InvalidLoginCredentialException;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Sanctum\PersonalAccessToken;

class AuthService
{
    // public function __construct(readonly protected FirebaseAuthService $firebase) {}

    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw new InvalidLoginCredentialException();
        }

        if (! $user->email_verified_at) {
            $this->sendEmailVerificationCode($user);
            throw new EmailNotVerifiedException(
                'Email not verified. A new verification code has been sent to your email.'
            );
        }

        return $this->respondWithToken($user);
    }

    public function socialLogin(SocialUser $socialUser, string $provider)
    {
        $account = SocialAccount::where('provider_name', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($account) {
            return $this->respondWithToken($account->user);
        }

        // Find or create user (since social account does not exist)
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name'              => $socialUser->getName() ?? $socialUser->getEmail(),
                'password'          => bcrypt(Str::random(24)),
                'avatar'            => $socialUser->getAvatar() ?? null,
                'email_verified_at' => now(),
            ]
        );

        // Linking of social account to newly created user
        $user->socialAccounts()->create([
            'provider_name' => $provider,
            'provider_id'   => $socialUser->getId(),
        ]);

        if ($user->wasRecentlyCreated) {
            Mail::to($user->email)->send(new AccountCreated($user->name));
            return $this->respondWithToken($user);
        }

        // Verifying email if email was not verified prior to usage of social login
        if (! $user->email_verified_at) {
            $user->email_verified_at = now();
        }

        if (! $user->avatar && $avatar = $socialUser->getAvatar()) {
            $user->avatar = $avatar;
        }

        $user->save();

        return $this->respondWithToken($user);
    }

    public function register(array $data)
    {
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $this->sendEmailVerificationCode($user);

        return $this->respondWithToken($user);
    }

    public function logout(User $user)
    {
        /** @var PersonalAccessToken|null $token */
        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
        }
    }

    private function sendEmailVerificationCode(User $user)
    {
        $code = rand(111111, 999999);

        DB::table('email_verification_tokens')
            ->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'token'      => Hash::make($code),
                    'expires_at' => now()->addMinutes(20),
                    'updated_at' => now(),
                ]
            );

        if ($user->wasRecentlyCreated) {
            Mail::to($user->email)->send(new AccountCreated(
                $user->name,
                $code
            ));
        } else {
            Mail::to($user->email)->send(new EmailVerification(
                $user->name,
                $code
            ));
        }

        return $code;
    }

    protected function respondWithToken(User $user)
    {
        $user->tokens()->delete();

        return new AuthResponse(
            $user,
            $user->createToken('auth_token', expiresAt: now()->addHour())->plainTextToken
        );
    }
}
