<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Group;
use App\GroupLocation;
use Auth;
use Request;

class GroupController extends Controller {

    public function __construct() {
        // We do not need middleware at this point.
        //$this->middleware('auth');
    }


    public function index($groupId) {
        $location = GroupLocation::where('group_id', $groupId)->first();

        if (!$location) {
            return abort(404);
        }

        return redirect()->to(Request::path() . '/' . $location->id);
    }


    public function show($groupId, $locationId) {
        $group = Group::find($groupId);

        if (!$group) {
            return abort(404);
        }

        $locations = $group->locations;
        $location = $locations->find($locationId);

        if (!$location) {
            $first = $locations->first();

            // TODO: Perhaps, notify visitor, that requested location doesn't exist
            return redirect()->to('classes/' . $group->id . '/' . ($first ? $first->id : ''));
        }

        // TODO: Move all this amateur art below into pro Eloquent model.
        // What it does:
        // Builds an array with items representing week days. All days are
        // always included except Sunday only when it has any session.
        // Then it equates number of rows between two sibling items what assures
        // week days tables rendered one to each other has equal amount of rows
        // even being empty (see profile page rendered in your browser).
        $schedule = $group->schedule()->where('location_id', $location->id)->orderBy('starts')->get()->toArray();
        $weeklySchedule = [];

        if (count($schedule)) {
            foreach ([0, 1, 2, 3, 4, 5, 6] as $number) {
                $sessions = array_filter($schedule, function($row) use ($number) {
                    return $row['week_day'] == $number;
                });

                if ($number != 6 || count($sessions)) {
                    $siblingLength = count(array_filter($schedule, function($row) use ($number) {
                        return $row['week_day'] == $number + ($number % 2 ? -1 : 1);
                    }));

                    if (count($sessions) < $siblingLength) {
                        foreach (array_fill(0, $siblingLength - count($sessions), '') as $empty) array_push($sessions, $empty);
                    }

                    array_push($weeklySchedule, count($sessions) ? $sessions : ['']);
                }
            }
        }

        return view('groups.page.index', [
            'user' => Auth::user(),
            'group' => $group,
            'photos_count' => $group->photos_count,
            'locations' => $locations,
            'location' => $location,
            'pricing' => $location->pricings,
            'weekly_schedule' => $weeklySchedule,
            'amenities' => $group->amenities
        ]);
    }

}