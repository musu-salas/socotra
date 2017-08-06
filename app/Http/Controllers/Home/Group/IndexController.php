<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\Http\Controllers\Controller;

class IndexController extends Controller {
    
    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group)
    {
        return redirect(url("home/classes/{$group->id}/location"));
    }
}
