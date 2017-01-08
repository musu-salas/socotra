<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\Http\Controllers\Controller;
use Auth;
use Request;
use Validator;

class OverviewController extends Controller {

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

        return view('home.group.overview', [
            'user' => Auth::user(),
            'group' => $group,
            'menu' => $group->menu
        ]);
    }

    public function store($groupId) {
        foreach (Request::all() as $key => $value) {
            $params[$key] = trim(strip_tags($value));
        }

        $validation = Validator::make($params, [
            'creative_field1' => 'required'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        $params['title'] = trim(preg_replace('/\s\s+/', ' ', $params['title']));
        $params['uvp'] = trim(preg_replace('/\s\s+/', ' ', $params['uvp']));
        $group = $this->group;
        $group->update([
            'creative_field1' => $params['creative_field1'],
            'creative_field2' => $params['creative_field2'],
            'title' => $params['title'],
            'uvp' => $params['uvp'],
            'description' => $params['description'],
            'for_who' => $params['for_who']
        ]);

        return redirect('home/classes/' . $group->id . '/overview')->with('success-message', 'Overview was updated!');
    }
}
