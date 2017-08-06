<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\Http\Controllers\Controller;
use Auth;
use Request;
use Validator;

class ContactController extends Controller {

    /**
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group) {
        return view('home.group.contact.index', [
            'user' => Auth::user(),
            'group' => $group,
            'menu' => $group->menu
        ]);
    }

    /**
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function store(Group $group) {
        $group->phone = strip_tags(Request::input('phone'));
        $group->save();

        return redirect(url("home/classes/{$group->id}/contact"))
            ->with('success-message', 'Contact information was updated!');
    }
}
