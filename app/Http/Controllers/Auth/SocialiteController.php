<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialiteUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::error("Error during social authentication with {$provider}: " . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'There was an issue with social authentication. Please try again.']);
        }
        $authUser = $this->findOrCreateUser($user, $provider);
        // Login user
        Auth::login($authUser, true);
        return redirect()->route('filament.pages.dashboard');
    }

    public function findOrCreateUser($socialUser, $provider)
    {
        $socialAccount = SocialiteUser::where('provider_id', $socialUser->id)
            ->where('provider', $provider)
            ->with('user') // Eager load the related user
            ->first();
        if ($socialAccount) {
            return $socialAccount->user;
        }
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            ['name' => $socialUser->getName()]
        );
        $user->socialiteUsers()->firstOrCreate([
            'provider_id' => $socialUser->getId(),
            'provider' => $provider,
        ]);
        return $user;
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->has('redirect_to')) {
            return redirect($request->input('redirect_to'));
        }
        return redirect()->intended($this->redirectPath());
    }
}
