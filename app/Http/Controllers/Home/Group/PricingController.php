<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\Http\Controllers\Controller;
use App\GroupPricing;
use Auth;
use Request;
use Validator;

class PricingController extends Controller {

    protected $group;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Group $group) {
        // This middleware is no longer needed here because it is added directly
        // on the Route definitions inside routes.php
		// $this->middleware('auth');

        $this->group = $group;
	}

    public function index() {
        $user = Auth::user();
        $group = $this->group;
        $locations = $group->locations;

        if (!count($locations) || !$locations->first()->is_full) {
            return view('home.group.pricing.disabled', [
                'user' => $user,
                'group' => $group,
                'menu' => $group->menu
            ]);
        }

        return view('home.group.pricing.list', [
            'user' => $user,
            'group' => $group,
            'menu' => $group->menu,
            'locations' => $locations
        ]);
    }

    public function show($groupId, $priceId) {
        $user = Auth::user();
        $group = $this->group;
        $locations = $group->locations;
        $price = null;

        if ($priceId !== 'new') {
            $price = $group->pricing->find($priceId);

            if (!$price) {
                return redirect()->to('/home/classes/' . $group->id . '/pricing');
            }
        }

        $pricesByLocation = [];

        foreach($locations as $location) {
            $pricing = $price ? $location->pricings()->where('group_pricing_id', $price->id)->first() : null;

            if ($pricing) {
                $pricesByLocation[$location->id] = floatval($pricing->pivot->price);
            }
        }

        return view('home.group.pricing.add', [
            'user' => $user,
            'group' => $group,
            'menu' => $group->menu,
            'locations' => $locations,
            'price' => $price,
            'pricesByLocation' => $pricesByLocation
        ]);
    }

    public function store($groupId, $priceId) {
        foreach (Request::all() as $key => $value) {
            $xssCleanReq[$key] = !is_array($value) ? trim(strip_tags($value)) : $value;
        }

        $validation = Validator::make($xssCleanReq, [
            'title' => 'required|max:255',
            'description' => 'max:255',
            'prices' => 'array'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withInput()->withErrors($validation->errors());
        }

        $prices = [];
        foreach($xssCleanReq['prices'] as $price) {
            $price['location'] = isset($price['location']) ? intval($price['location']) : null;
            $price['checked'] = isset($price['checked']) ? $price['checked'] : false;
            $price['price'] = isset($price['price']) ? floatval(str_replace(',', '.', $price['price'])) : null;

            if ($price['location'] && $price['checked'] && $price['price']) {
                $prices[$price['location']] = ['price' => $price['price']];
            }

            unset($price);
        }

        if (!count($prices)) {
            return redirect()->back()->withInput()->withErrors(['msg' => 'At least one location has to have price assigned.']);
        }

        $group = $this->group;

        if ($priceId === 'new') {
            $price = GroupPricing::create([
                'group_id' => $group->id,
                'title' => $xssCleanReq['title'],
                'description' => $xssCleanReq['description']
            ]);

        } else {
            $price = $group->pricing()->findOrFail($priceId);
            $price->update([
                'title' => $xssCleanReq['title'],
                'description' => $xssCleanReq['description']
            ]);
        }

        $price->locations()->sync($prices);

        return redirect()->to('home/classes/' . $group->id . '/pricing');
    }
}
