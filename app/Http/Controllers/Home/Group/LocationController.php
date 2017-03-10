<?php namespace App\Http\Controllers\Home\Group;

use App\Http\Controllers\Controller;
use App\Group;
use App\Country;
use App\Currency;
use App\GroupLocation;
use Auth;
use Request;
use Validator;

class LocationController extends Controller {

    /**
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group) {
        $user = Auth::user();
        $locations = $group->locations;

        if (!count($locations)) {
            return redirect()->to("/home/classes/{$group->id}/location/new");
        }

        if (count($locations) === 1) {

            // Redirects to the only location edition page, since it is not completed.
            if (!$locations[0]->is_full) {
                return redirect()->to("/home/classes/{$group->id}/location/{$locations[0]->id}");

            } elseif (!$locations[0]->latlng) {
                return redirect()->to("/home/classes/{$group->id}/location/{$locations[0]->id}/map");
            }
        }

        return view('home.group.location.list', [
            'user' => $user,
            'group' => $group,
            'menu' => $group->menu,
            'locations' => $locations
        ]);
    }

    /**
     * @param  \App\Group  $group
     * @param string  $locationId
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, $locationId) {
        $user = Auth::user();
        $location = null;

        if ($locationId !== 'new') {
            $location = $group->locations()->find($locationId);
        }

        $countries = Country::where('active', '=', true)->orderBy('name')->get();
        $currencies = Currency::all();

        return view('home.group.location.add', [
            'user' => $user,
            'group' => $group,
            'menu' => $group->menu,
            'location' => $location,
            'countries' => $countries,
            'currencies' => $currencies
        ]);
    }

    /**
     * @param  \App\Group  $group
     * @param  string  $locationId
     * @return \Illuminate\Http\Response
     */
    public function store(Group $group, $locationId) {
        foreach (Request::all() as $key => $value) {
            $xssCleanReq[$key] = trim(strip_tags($value));
        }

        $validation = Validator::make($xssCleanReq, [
            'country_id' => 'required|exists:countries,id',
            'currency_id' => 'required|exists:currencies,id',
            'address_line_1' => 'required|max:255',
            'address_line_2' => 'max:255',
            'city' => 'required|max:255',
            'district' => 'max:255',
            'state' => 'max:255',
            'zip' => 'required|max:255',
            'how_to_find' => 'max:255'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        $location_attr = [
            'country_id' => $xssCleanReq['country_id'],
            'address_line_1' => $xssCleanReq['address_line_1'],
            'address_line_2' => $xssCleanReq['address_line_2'] ? $xssCleanReq['address_line_2'] : null,
            'city' => $xssCleanReq['city'],
            'district' => $xssCleanReq['district'],
            'state' => $xssCleanReq['state'] ? $xssCleanReq['state'] : null,
            'zip' => $xssCleanReq['zip'],
            'how_to_find' => $xssCleanReq['how_to_find'] ? $xssCleanReq['how_to_find'] : null,
            'currency_id' => $xssCleanReq['currency_id']
        ];

        if ($locationId === 'new') {
            $location_attr['group_id'] = $group->id;
            $location = GroupLocation::create($location_attr);

        } else {
            $location = $group->locations()->findOrFail($locationId);

            if ($location->country_id !== intval($location_attr['country_id']) ||
                    $location->city !== $location_attr['city'] ||
                    $location->zip !== $location_attr['zip'] ||
                    $location->address_line_1 !== $location_attr['address_line_1']) {

                $location->latlng = null;
            }

            $location->update($location_attr);
        }

        return redirect("home/classes/{$group->id}/location/{$location->id}/map");
    }

    /**
     * @param  \App\Group  $group
     * @param string  $locationId
     * @return \Illuminate\Http\Response
     */
    public function map(Group $group, $locationId) {
        $user = Auth::user();
        $location = $group->locations()->find($locationId);

        if (!$location) {
            return redirect()->to("/home/classes/{$group->id}/location");
        }

        if (Request::isMethod('get')) {
            return view('home.group.location.map', [
                'user' => $user,
                'group' => $group,
                'menu' => $group->menu,
                'location' => $location
            ]);
        }

        $location->latlng = trim(strip_tags(Request::input('latlng')));
        $location->save();

        return redirect()->to("home/classes/{$group->id}/location");
    }
}
