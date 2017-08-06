<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\Http\Controllers\Controller;
use Auth;
use Request;
use Validator;

class OverviewController extends Controller {

    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group) {
        return view('home.group.overview', [
            'user' => Auth::user(),
            'group' => $group,
            'menu' => $group->menu
        ]);
    }

    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function store(Group $group) {
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
        $group->update([
            'creative_field1' => $params['creative_field1'],
            'creative_field2' => $params['creative_field2'],
            'title' => $params['title'],
            'uvp' => $params['uvp'],
            'description' => $params['description'],
            'for_who' => $params['for_who']
        ]);

        return redirect(url("home/classes/{$group->id}/overview"))
            ->with('success-message', 'Overview was updated!');
    }
}
