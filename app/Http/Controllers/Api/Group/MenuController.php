<?php namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Group;

class MenuController extends Controller {

    protected $group;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Group $group) {
        $this->group = $group;
    }

    public function index(){
        return response()->json($this->group->menu);
    }
}