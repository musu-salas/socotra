<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller {

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

    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return redirect()->to('/home/classes/' . $this->group->id . '/location');
    }
}
