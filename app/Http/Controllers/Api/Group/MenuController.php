<?php namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Group;

class MenuController extends Controller {

    /**
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group) {
        return response()->json($group->menu);
    }
}