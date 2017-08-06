<?php namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;

use App\Group;
use App\GroupLocation;
use App\Country;
use App\Currency;
use App\GroupPricing;
use Auth;
use Request;
use Validator;

class GroupController extends Controller {

    /**
     * Displays a listing with groups.
     *
     * @return \Illuminate\Http\Response
     */
	public function index() {
        $user = Auth::user();
        $groups = $user->myGroups;

        if (!count($groups)) {
            return redirect(url('home/classes/new'));
        }

        return view('home.groups', [
            'user' => $user,
            'myGroups' => $groups
        ]);
    }


    /**
     * Shows the form for creating a new group.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $user = Auth::user();

        // TODO: Maybe take some information from already existing groups (e.g. currency, location).

        return view('home.group.new', [
            'user' => $user,
            'currencies' => Currency::all()
        ]);
    }


    /**
     * Stores a newly created group in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store() {
        foreach (Request::all() as $key => $value) {
            $params[$key] = trim(strip_tags($value));
        }

        $validation = Validator::make($params, [
            'creative_field1' => 'required|max:50',
            'creative_field2' => 'max:50',
            'location' => 'required',
            'single_fee' => 'numeric',
            'currency_id' => 'exists:currencies,id'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        $user = Auth::user();

        $group_attr = [
            'user_id'  => $user->id,
            'creative_field1' => $params['creative_field1'],
        ];

        if ($params['creative_field2']) {
            $group_attr['creative_field2'] = $params['creative_field2'];
        }

        $group = Group::create($group_attr);
        $location_json = json_decode($params['location']);

        if (isset($location_json->country)) {
            $country_alpha_2 = explode('|', $location_json->country)[0];
            $country = Country::where('alpha_2', '=', $country_alpha_2)->where('active', '=', true)->first();

            if ($country) {
                $location_attr = [
                    'group_id' => $group->id,
                    'country_id' => $country->id,
                    'currency_id' => $params['currency_id']
                ];

                if (isset($location_json->city)) {
                    $location_attr['city'] = $location_json->city;
                }

                if (isset($location_json->state)) {
                    $location_attr['state'] = $location_json->state;
                }

                if (isset($location_json->latlng)) {
                    $location_attr['latlng'] = $location_json->latlng;
                }

                $location = GroupLocation::create($location_attr);
            }
        }

        if ($params['single_fee'] && isset($location)) {
            $price = GroupPricing::create([
                'group_id' => $group->id,
                'title' => 'Single attendance'
            ]);

            $price->locations()->attach($location->id, [
                'price' => floatval($params['single_fee'])
            ]);
        }

        return redirect(url('home/classes/' . $group->id));
    }
}
