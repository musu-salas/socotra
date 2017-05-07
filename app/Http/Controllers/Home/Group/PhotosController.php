<?php

namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\Http\Controllers\Controller;
use Auth;

class PhotosController extends Controller {

	public function index(Group $group) {
        return view('home.group.photos', [
            'user' => Auth::user(),
            'group' => $group,
            'menu' => $group->menu
        ]);
    }
}
