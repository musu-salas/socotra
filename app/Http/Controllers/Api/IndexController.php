<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class IndexController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

    }

    public function index() {
        return response()->json([
            'name' => 'API v1',
            'status' => 'running',
            'version' => 1,
            'a-random-number' => rand(10, 99)
        ]);
    }

}