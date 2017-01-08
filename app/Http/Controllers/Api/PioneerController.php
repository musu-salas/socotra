<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Log;
use App\Pioneer;
use Psy\Exception\ErrorException;
use Request;
use Validator;

class PioneerController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    public function store() {
        foreach (Request::all() as $key => $value) {
            $xssCleanReq[$key] = trim(strip_tags($value));
        }

        $validation = Validator::make($xssCleanReq, [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'location' => 'required',
            'creative_field1' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors()
            ], 500);
        }

        $pioneer = Pioneer::where('email', $xssCleanReq['email'])->first();
        if (!$pioneer) {
            $new_pioneer = [
                'first_name' => $xssCleanReq['first_name'],
                'last_name' => $xssCleanReq['last_name'],
                'email' => $xssCleanReq['email'],
                'location' => $xssCleanReq['location'],
                'creative_field1' => $xssCleanReq['creative_field1']
            ];

            if ($xssCleanReq['creative_field2']) {
                $new_pioneer['creative_field2'] = $xssCleanReq['creative_field2'];
            }

            if ($xssCleanReq['website']) {
                $new_pioneer['website'] = $xssCleanReq['website'];
            }

            return response()->json(Pioneer::create($new_pioneer));
        }

        return response()->json($pioneer, 208);
    }
}