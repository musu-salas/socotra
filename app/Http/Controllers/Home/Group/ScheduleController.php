<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\Http\Controllers\Controller;
use App\GroupSchedule;
use Auth;
use Request;
use Validator;

class ScheduleController extends Controller {

    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @param  \App\Group  $group
     * @param  string  $locationId
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group, $locationId = null) {
        $user = Auth::user();
        $locations = $group->locations;

        if (!$locationId) {
            $location = $locations->first();

            if (!$location || !$location->is_full) {
                return view('home.group.schedule.disabled', [
                    'user' => $user,
                    'group' => $group,
                    'menu' => $group->menu
                ]);
            }

            return redirect(url("home/classes/{$group->id}/schedule/{$location->id}"));
        }

        $location = $locations->find($locationId);

        if (!$location || !$location->is_full) {
            return redirect(url("home/classes/{$group->id}/schedule"));
        }

        return view('home.group.schedule.list', [
            'user' => $user,
            'group' => $group,
            'menu' => $group->menu,
            'locations' => $locations,
            'location' => $location
        ]);
    }

    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @param  \App\Group  $group
     * @param  string  $locationId
     * @param  string  $scheduleId
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group, $locationId, $scheduleId) {
        $schedule = null;
        $location = $group->locations->find($locationId);

        if (!$location) {
            return redirect(url("home/classes/{$group->id}/schedule"));
        }

        if ($scheduleId !== 'new') {
            $schedule = $group->schedule()->where('id', $scheduleId)->where('location_id', $location->id)->first();

            if (!$schedule) {
                return redirect(url("home/classes/{$group->id}/schedule/{$location->id}"));
            }
        }

        $weekDay = $schedule ? $schedule->week_day : Request::input('week_day');

        if (!isset($weekDay) || !in_array(intval($weekDay), [0, 1, 2, 3, 4, 5, 6])) {
            return redirect(url(Request::path(), [ 'week_day' => 0 ]));
        }

        return view('home.group.schedule.add', [
            'user' => Auth::user(),
            'group' => $group,
            'menu' => $group->menu,
            'location' => $location,
            'schedule' => $schedule,
            'week_day' => intval($weekDay)
        ]);
    }

    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @param  \App\Group  $group
     * @param  string  $locationId
     * @param  string  $scheduleId
     * @return \Illuminate\Http\Response
     */
    public function store(Group $group, $locationId, $scheduleId) {
        foreach (Request::all() as $key => $value) {
            $xssCleanReq[$key] = trim(strip_tags($value));
        }

        $validation = Validator::make($xssCleanReq, [
            'title' => 'required|max:255',
            'description' => 'max:255',
            'starts' => 'max:5',
            'ends' => 'max:5',
            'week_day' => 'required|digits_between:0,6'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        $location = $group->locations->find($locationId);

        if (!$location) {
            return redirect()->back()->withErrors([
                'msg' => 'There was a problem attaching schedule session to the location.'
            ]);
        }

        $schedule_attr = [
            'title' => $xssCleanReq['title'],
            'description' => $xssCleanReq['description'] ? $xssCleanReq['description'] : null,
            'starts' => $xssCleanReq['starts'],
            'ends' => $xssCleanReq['ends']
        ];

        if ($scheduleId === 'new') {
            $schedule_attr['group_id'] = $group->id;
            $schedule_attr['location_id'] = $location->id;
            $schedule_attr['week_day'] = $xssCleanReq['week_day'];

            GroupSchedule::create($schedule_attr);

        } else {
            $group->schedule()->findOrFail($scheduleId)->update($schedule_attr);
        }

        return redirect(url("home/classes/{$group->id}/schedule/{$location->id}"));
    }
}
