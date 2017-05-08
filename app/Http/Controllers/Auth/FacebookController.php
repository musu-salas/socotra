<?php namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Country;
use App\Website;
use Socialite;
use Input;
use Auth;
use Hash;
use App\User;

class FacebookController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {}


    private function extractFirstName($name) {
        return implode(' ', array_slice(explode(' ', $name), 0, -1));
    }


    private function extractLastName($name) {
        return array_slice(explode(' ', $name), -1)[0];
    }


    private function hometownToLocation($fb_hometown) {
        $hometown = explode(', ', $fb_hometown['name']);
        $hometown_count = count($hometown);
        $country_name = $hometown_count == 2 ? $hometown[1] : $hometown[--$hometown_count];
        $country = array_first(Country::all(), function($i, $db_country) use ($country_name) {
            return strtolower($db_country->name) == strtolower($country_name);
        });

        return [
            'city' => $hometown[0],
            'state' => $hometown_count == 2 ? '' : $hometown[1],
            'country' => $country ? $country->alpha_2 . '|' . $country->name : '',
            'fb_hometown' => $fb_hometown['id']
        ];
    }


    private function extendUser($user, $fb_user, $hard_save = false) {
        $requires_save = $hard_save;

        if(!$user->facebook_id) {
            $user->facebook_id = $fb_user['id'];
            $requires_save = true;
        }

        if (!$user->avatar) {
            $user->avatar = 'http://graph.facebook.com/' . $fb_user['id'] . '/picture?width=160&height=160';
            $requires_save = true;
        }

        if(!$user->first_name){
            $user->first_name = $this->extractFirstName($fb_user['name']);
            $requires_save = true;
        }

        if(!$user->last_name){
            $user->last_name = $this->extractLastName($fb_user['name']);
            $requires_save = true;
        }

        if(!$user->location && isset($fb_user['hometown'])){
            $user->location = json_encode($this->hometownToLocation($fb_user['hometown']));
            $requires_save = true;
        }

        if (isset($fb_user['website'])) {
            Website::firstOrCreate([
                'user_id' => $user->id,
                'website' => $fb_user['website']
            ]);
        }

        if($requires_save) {
            $user->save();
        }

        return $user;
    }


    public function socialize() {
        // Get the provider instance
        $provider = Socialite::driver('facebook')
            ->scopes(config('services.facebook.scopes'))
            ->asPopup();

        if (Input::has('error')) {
            return view('home.socialized');
        }

        // If code exists, get the User instance with all data,
        // else redirect to the provider auth screen.
        if (Input::has('code')) {
            $FB = $provider->user();

            if(!$FB->user) {
                Log::error('Facebook user object is missing.', $FB);
                return redirect('socialize/facebook?' . http_build_query([
                    'error' => 'missing_user_data'
                ]));
            }

            if (Auth::check()) {
                $user = Auth::user();
                $userWithThisFacebook = User::where('facebook_id', '=', $FB->user['id'])->where('id', '!=', $user->id)->first();

                if ($userWithThisFacebook) {
                    return redirect('socialize/facebook?' . http_build_query([
                        'error' => 'already_user'
                    ]));
                }

                $this->extendUser($user, $FB->user);
                return view('home.socialized');
            }

            if (isset($FB->user['email'])) {
                $user = User::where('facebook_id', '=', $FB->user['id'])
                    ->orWhere('email', '=', $FB->user['email'])
                    ->first();
            } else {
                $user = User::where('facebook_id', '=', $FB->user['id'])->first();
            }

            if ($user) {
                $user = $this->extendUser($user, $FB->user);
                Auth::login($user);
                return view('home.socialized');
            }

            if (isset($FB->user['hometown'])) {
                $hometown = $FB->user['hometown'];
                $location = $hometown ? json_encode($this->hometownToLocation($hometown)) : null;

            } else {
                $location = null;
            }

            $user = User::create([
                'first_name' => $this->extractFirstName($FB->user['name']),
                'last_name' => $this->extractLastName($FB->user['name']),
                'email' => $FB->user['email'] ?? config('services.facebook.empty_email'),
                'newsletter' => 1,
                'facebook_id' => $FB->user['id'],
                'location' => $location
            ]);

            $user = $this->extendUser($user, $FB->user, true);

            Auth::login($user);
            return view('home.socialized');
        }

        return $provider->redirect();
    }
}
