<?php namespace App\Http\Controllers\Api\Group;

use App\Group;
use App\GroupPricing;
use App\Http\Controllers\Controller;

class PricingController extends Controller {

    /**
     * @param  \App\Group  $group
     * @param  \App\GroupPricing  $pricing
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, GroupPricing $pricing) {
        $pricing->locations()->sync([]);
        $pricing->delete();

        return response()->json($pricing);
    }
}