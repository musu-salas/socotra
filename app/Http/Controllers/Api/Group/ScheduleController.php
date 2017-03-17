<?php namespace App\Http\Controllers\Api\Group;

use App\Group;
use App\GroupSchedule;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller {

    /**
     * @param  \App\Group  $group
     * @param  \App\GroupSchedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, GroupSchedule $schedule) {
        $schedule->delete();

        return response()->json($schedule);
    }
}