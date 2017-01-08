<?php namespace App\Http\Controllers\Api\Group;

use App\Group;
use App\Http\Controllers\Controller;
use Mail;
use Request;
use Validator;

class ContactController extends Controller {

    protected $group;


    public function __construct(Group $group) {
        $this->group = $group;
    }


    public function send($groupId) {
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

        $group = $this->group;
        $owner = $group->owner;
        $sender = json_decode(json_encode([
            'name' => $params['name'],
            'email' => $params['email'],
            'phone' => $params['phone']
        ]), false);

        $subject = 'Wish to begin attending your class';
        $template = 'emails.notification';
        $data = [
            'receiver' => $owner,
            'sender' => $sender,
            'page_url' => url('/classes/' . $group->id . '/' . $params['location_id'])
        ];

        if ($params['message']) {
            $data['text'] = $params['message'];
            $template = 'emails.message';
            $subject = 'Message from your ' . config('custom.code') . ' page visitor';
        }

        Mail::send($template, $data, function ($m) use ($owner, $sender, $subject) {
            $m->to($owner->email, $owner->first_name . ' ' . $owner->last_name);
            $m->subject($subject);

            if ($sender->email) {
                $m->replyTo($sender->email, $sender->name);
            }
        });

        return response()->json([]);
    }
}