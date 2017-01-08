<?php namespace App\Http\Controllers\Api\Group;

use App\Group;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller {

    protected $group;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Group $group) {
        $this->group = $group;
    }


    public function destroy($groupId, $scheduleId) {
        $schedule = $this->group->schedule->find($scheduleId);

        if (!$schedule) {
            return response()->json([
                'errors' => [
                    'Schedule session does not exist.'
                ]
            ], 404);
        }

        $schedule->delete();
        return response()->json($schedule);
    }
}