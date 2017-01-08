<?php namespace App\Http\Controllers\Api\Group;

use App\Group;
use App\Http\Controllers\Controller;
use Request;

class LocationController extends Controller {

    protected $group;


    public function __construct(Group $group) {
        $this->group = $group;
    }


    private function decodeBase64UrlSafe($str) {
        return base64_decode(str_replace(array('-', '_'), array('+', '/'), $str));
    }


    private function encodeBase64UrlSafe($str) {
        return str_replace(array('+', '/'), array('-', '_'), base64_encode($str));
    }


    private function signMapUrl($width, $height, $location) {
        $latlng = $location->latlng ? $location->latlng : '57.0291309,23.9746625';
        $mapUrl = 'https://maps.googleapis.com/maps/api/staticmap';
        $mapUrl .= '?center=' . $latlng . '&zoom=' . ($location->isFull || $location->latlng ? 16 : 18) . '&size=' . $width . 'x' . $height . '&scale=2&maptype=roadmap' . 1 . '&key=' . env('GOOGLE_MAPS_KEY');

        if ($location->latlng && $location->isFull) {
            $mapUrl .= '&markers=color:red%7C' . $location->latlng;

        } else {
            $mapUrl .= '&style=feature:all|saturation:-100';
        }

        $url = parse_url($mapUrl);

        $urlPartToSign = $url['path'] . '?' . $url['query'];
        $decodedKey = $this->decodeBase64UrlSafe(env('GOOGLE_MAPS_SECRET'));
        $signature = hash_hmac('sha1', $urlPartToSign, $decodedKey, true);
        $encodedSignature = $this->encodeBase64UrlSafe($signature);

        return $mapUrl . '&signature=' . $encodedSignature;
    }


    public function map($groupId, $locationId) {
        $location = $this->group->locations->find($locationId);
        $width = Request::input('width');
        $height = Request::input('height');

        if (!$location) {
            return response()->json([
                'errors' => [
                    'Location does not exist.'
                ]
            ], 404);
        }

        $location->map = $this->signMapUrl(intval($width), intval($height), $location);
        return response()->json($location);
    }


    public function destroy($groupId, $locationId) {
        $group = $this->group;
        $location = $group->locations->find($locationId);

        if (!$location) {
            return response()->json([
                'errors' => [
                    'Location does not exist.'
                ]
            ], 404);
        }

        $location->pricings()->sync([]);

        foreach($group->pricing as $pricing) {

            if (!count($pricing->locations)) {
                $pricing->delete();
            }
        }

        $group->schedule()->where('location_id', $location->id)->delete();
        $location->delete();

        return response()->json($location);
    }
}