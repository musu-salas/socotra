<?php namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Group;
use App\GroupPhoto;
use Request;
use Storage;
use Validator;

class PhotosController extends Controller {

    protected $group;

    public function __construct(Group $group) {
        $this->group = $group;
    }

    public function index($groupId) {
        return response()->json($this->group->photos->map(function($photo) {
            return [
                'id' => $photo->id,
                'w' => $photo->large_width,
                'h' => $photo->large_height,
                'src' => $photo->large_src
            ];
        }));
    }

    public function destroy($groupId, $photoId) {
        $photo = GroupPhoto::find($photoId);
        $ppath = config('custom.aws.class.photos_path');

        if (isset($photo->hash)) {
            foreach (config('custom.aws.class.photos') as $name => $size) {

                # Define the filename
                $fn = $ppath . $photo->hash . $size['key'] . '.' . $photo->ext;

                if (Storage::disk('s3')->exists($fn)) {
                    // Deletes $fn from s3 bucket, if it exists
                    Storage::disk('s3')->delete($fn);
                }
            }

            $group = $this->group;

            if ($group->cover_photo_id == $photo->id) {
                $group->cover_photo_id = null;
                $group->save();
            }

            // Now delete the db record
            $photo->delete();

            return response()->json($photo);
        }

        return response()->json(['errors' => [
            'Photo does not exist.'

        ]], 404);
    }


    public function cover($groupId) {
        $group = $this->group;

        $validation = Validator::make(Request::all(), [
            'photo_id' => 'integer|exists:group_photos,id',
            'offset' => 'integer|max:0'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors()
            ], 500);
        }

        if (Request::has('photo_id')) {
            $photo = GroupPhoto::findOrFail(intval(Request::input('photo_id')));

            if ($group->cover_photo_id !== $photo->id) {
                $group->cover_photo_id = $photo->id;
                $group->cover_photo_offset = 0;
            }
        }

        if (Request::has('offset')) {
            $offset = intval(Request::input('offset'));
            $group->cover_photo_offset = $offset;
        }

        $group->save();

        return response()->json($group);
    }
}