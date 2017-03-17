<?php namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class IndexController extends Controller {

    public function index() {
        return response()->json([
            'name' => 'API v1',
            'status' => 'running',
            'version' => 1,
            'a-random-number' => rand(10, 99)
        ]);
    }

}