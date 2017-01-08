<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\Http\Controllers\Controller;
use Auth;
use Request;
use Validator;

class ContactController extends Controller {

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

    public function index($groupId) {
        $group = $this->group;

        return view('home.group.contact.index', [
            'user' => Auth::user(),
            'group' => $group,
            'menu' => $group->menu
        ]);
    }

    public function store($groupId) {
        $group = $this->group;

        $group->phone = strip_tags(Request::input('phone'));
        $group->save();

        return redirect('home/classes/' . $group->id . '/contact')->with('success-message', 'Contact information was updated!');
    }
}
