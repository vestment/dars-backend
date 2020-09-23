<?php
namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Socialite;
use App\Events\Frontend\Auth\UserLoggedIn;
use App\Repositories\Frontend\Auth\UserRepository;
use App\Helpers\Frontend\Auth\Socialite as SocialiteHelper;
use App\Models\Auth\User;
class SocialAccountController extends Controller
{
     /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var SocialiteHelper
     */
    protected $socialiteHelper;

    /**
     * SocialLoginController constructor.
     *
     * @param UserRepository  $userRepository
     * @param SocialiteHelper $socialiteHelper
     */
     public function __construct(UserRepository $userRepository, SocialiteHelper $socialiteHelper)
    {
        $this->userRepository = $userRepository;
        $this->socialiteHelper = $socialiteHelper;
    }
   /**
    * Handle Social login request
    *
    * @return response
    */
   public function socialLogin($provider)
   {
       // If the provider is not an acceptable third party than kick back
        if (! in_array($provider, $this->socialiteHelper->getAcceptedProviders())) {
            return redirect()->route(home_route())->withFlashDanger(__('auth.socialite.unacceptable', ['provider' => $provider]));
        }
       return Socialite::driver($provider)->redirect();
   }
   /**
    * Obtain the user information from Social Logged in.
    * @param provider
    * @return Response
    */
   public function handleProviderCallback($provider)
   {
      
       try {
            $user = $this->userRepository->findOrCreateProvider($this->getProviderUser($provider), $provider);
        } catch (GeneralException $e) {
            return redirect()->route(home_route())->withFlashDanger($e->getMessage());
        }

        if (is_null($user)) {
            return redirect()->route(home_route())->withFlashDanger(__('exceptions.frontend.auth.unknown'));
        }

        // Check to see if they are active.
        if (! $user->isActive()) {
            throw new GeneralException(__('exceptions.frontend.auth.deactivated'));
        }

        // Account approval is on
        if ($user->isPending()) {
            throw new GeneralException(__('exceptions.frontend.auth.confirmation.pending'));
        }

        // User has been successfully created or already exists
        auth()->login($user, true);

        // Set session variable so we know which provider user is logged in as, if ever needed
        session([config('access.socialite_session_name') => $provider]);

        event(new UserLoggedIn(auth()->user()));

        $user = User::findorFail(auth()->user()->id);
       $token = $user->createToken('Personal Access Token')->accessToken;
       // Return to the intended url or default to the class property
       return redirect()->intended(route(home_route()))->with(['socialToken'=>$token]);
   }
   /**
     * @param $provider
     *
     * @return mixed
     */
    protected function getProviderUser($provider)
    {
        return Socialite::driver($provider)->user();
    }
   
}