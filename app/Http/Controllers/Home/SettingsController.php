<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Auth;
use Request;
use Validator;
use Hash;

class SettingsController extends Controller {

	public function index() {
        return view('home.settings', [
            'user' => Auth::user()
        ]);
    }


    public function store() {
        $user = Auth::user();

        foreach (Request::all() as $key => $value) {
            switch($key) {
                case 'password':
                case 'new_password':
                    $params[$key] = $value;
                    break;

                default:
                    $params[$key] = trim(strip_tags($value));
                    break;
            }
        }

        $rules = [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id
        ];

        if (isset($params['password']) && $params['password']) {
            $rules['password'] = 'hash:' . $user->password;
            $rules['new_password'] = 'required|different:password|confirmed|min:6';
        }

        $validation = Validator::make($params, $rules);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        if (isset($params['new_password']) && $params['new_password']) {
            $user->password = Hash::make($params['new_password']);
        }

        $user->first_name = $params['first_name'];
        $user->last_name = $params['last_name'];
        $user->email = $params['email'];
        $user->location = $params['location'];
        $user->newsletter = filter_var($params['newsletter'] ?? 0, FILTER_VALIDATE_BOOLEAN);
        $isNewUser = ($user->created_at === $user->updated_at) ? true : false;
        $user->save();

        if ($isNewUser && !count($user->myGroups)) {
            return redirect()->to('home/classes/new');
        }

        if (Request::get('followTo')) {
            return redirect()->to(Request::get('followTo'));
        }

        return redirect()->back()->with('success-message', 'Your account data was updated!');
    }
}
