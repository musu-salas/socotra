<?php namespace App\Http\Controllers\Api\Group;

use App\Group;
use App\Mail\OwnerVisitorSentMessege;
use App\Mail\OwnerVisitorShownAttendanceInterest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Request;
use Validator;

class ContactController extends Controller {

    /**
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function send(Group $group) {
        foreach (Request::all() as $key => $value) {
            $params[$key] = trim(strip_tags($value));
        }

        $validation = Validator::make($params, [
            'name' => 'required|max:255',
            'phone' => 'required_without:email|max:255',
            'email' => 'required_without:phone|email',
            'message' => 'string'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors()
            ], 500);
        }

        $pageUrl = url("/classes/{$group->id}/{$params['location_id']}");
        $owner = $group->owner;
        $sender = json_decode(json_encode([
            'name' => $params['name'],
            'email' => $params['email'],
            'phone' => $params['phone']
        ]), false);

        if ($params['message']) {
            Mail::to($owner)->queue(new OwnerVisitorSentMessege($owner, $sender, $pageUrl, $params['message']));

        } else {
            Mail::to($owner)->queue(new OwnerVisitorShownAttendanceInterest($owner, $sender, $pageUrl));
        }

        return response()->json([]);
    }
}