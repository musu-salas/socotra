<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Group;
use App\GroupLocation;
use Auth;
use \Illuminate\Http\Request;

class GroupController extends Controller {

    /**
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function oldToNewForwarder(Group $group, GroupLocation $location) {
        if (!$group) {
            return abort(404);
        }

        $location = $location->exists ? $location : array_first($group->locations);
        $title = str_slug($group->title);
        $city = str_slug($location->city);
        $discipline = str_slug($group->creative_field2 ? $group->creative_field2 : $group->creative_field1);
        $parameters = [
            'location' => $location->id,
            'title' => $title,
            'discipline' => $discipline,
            'city' => $city
        ];

        if ($location->district) {
            $district = str_slug($location->district);
            $parameters = array_add($parameters, 'district', $district);

            return redirect(route('classpage.long', $parameters));
        }

        return redirect(route('classpage.short', $parameters));
    }


    /**
     * @param  Request  $request
     * @param  GroupLocation  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request) {
        $locationId = $request->route('location');
        $location = GroupLocation::find($locationId);
        $group = $location->group;

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
            'locations' => $group->locations,
            'location' => $location,
            'pricing' => $location->pricings,
            'weekly_schedule' => $weeklySchedule,
            'amenities' => $group->amenities
        ]);
    }

}