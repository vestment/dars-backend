<?php
namespace App\Services;
use App\Models\Auth\SocialAccount;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as ProviderUser;
class SocialService
{
    public function createOrGetUser(ProviderUser $providerUser,$provider)
    {
        $account = SocialAccount::whereProvider($provider)
            ->whereProviderUserId($providerUser->getId())
            ->first();
        if ($account) {
            return $account->user;
        } else {
            $account = new SocialAccount([
                'user_id' => $providerUser->getId(),
                'provider' => $provider
            ]);
            // $user = User::whereEmail($providerUser->getEmail())->first();
            // if (!$user) {
            //     $user = User::create([
            //         'email' => $providerUser->getEmail(),
            //         'first_name' => explode(' ',$providerUser->getName())[0],
            //         'last_name' => explode(' ',$providerUser->getName())[1],
            //         'password' => Hash::make(md5(rand(1,10000))),
            //     ]);
            // }
            // $account->user()->associate($user);
            $account->save();
            return $user;
        }
    }
}