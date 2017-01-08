<?php namespace App\Http\Controllers\Api\Group;

use App\Group;
use App\Http\Controllers\Controller;

class PricingController extends Controller {

    protected $group;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Group $group) {
        $this->group = $group;
    }


    public function destroy($groupId, $pricingId) {
        $price = $this->group->pricing->find($pricingId);

        if (!$price) {
            return response()->json([
                'errors' => [
                    'Pricing does not exist.'
                ]
            ], 404);
        }

        $price->locations()->sync([]);
        $price->delete();

        return response()->json($price);
    }
}