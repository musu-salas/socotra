<?php

namespace App\Http\Controllers\Home;

use Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller {

    /**
     * Redirects to groups listing since home index view is not yet planned.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return redirect()->to(Request::path() . '/classes');
    }
}
