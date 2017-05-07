<?php namespace App\Http\Controllers\Api\Group;

use App\Http\Controllers\Controller;
use App\Group;
use App\GroupPhoto;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ServerException;
use Log;
use Request;
use Storage;
use Validator;

class PhotosController extends Controller {
    private const SUPPORTED_FILE_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'svg'];

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Group $group) {
        $photos_count = $group->photos_count;
        $photos_max = config('custom.class.max_photos');
        $files = Request::file('photos');
        $files_count = count($files);
        $files_path = config('custom.aws.class.original_photos_path');
        $file_size_limit = config('custom.class.max_photosize');
        $rules = [
            'photo' => 'required|image|mimes:' . join(',', self::SUPPORTED_FILE_TYPES) . '|max:' . $file_size_limit
        ];

        $uploaded = [];
        $failed = [];

        foreach ($files as $index => $file) {
            $validation = Validator::make(['photo' => $file], $rules);

            if ($validation->fails()) {
                array_push($failed, [
                    'filename' => $file->getClientOriginalName(),
                    'errors' => $validation->errors()->get('photo')
                ]);

                continue;
            }

            $extension = $file->guessExtension();

            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            if (!$extension) {
                array_push($failed, [
                    'filename' => $file->getClientOriginalName(),
                    'errors' => ['Unsupported file extension.']
                ]);

                continue;
            }

            if ($photos_count >= $photos_max) {
                array_push($failed, [
                    'filename' => $file->getClientOriginalName(),
                    'errors' => ["Maximum number of {$photos_max} photos is reached."]
                ]);

                continue;
            }

            $hash = md5($group->id . time()) . sha1_file($file);
            $filename = "{$hash}.{$extension}";

            try {
                Storage::disk('s3')->put($files_path . $filename, fopen($file, 'r+'));

                $photo = GroupPhoto::create([
                    'group_id' => $group->id,
                    'hash' => $hash,
                    'ext' => $extension
                ]);

                $outputs = [];
                foreach(config('custom.aws.class.photos') as $output) {
                    array_push($outputs, [
                        'key' => config('custom.aws.class.photos_path') . "{$hash}{$output['key']}.{$extension}",
                        'bucket' => env('S3_BUCKET'),
                        'size' => [
                            'width' => $output['maxw'],
                            'height' => $output['maxh']
                        ],
                        'isHardCrop' => (bool) ($output['key'] === '_t')
                    ]);
                }

                $cropper = new Client();
                $cropper_request = $cropper->post('https://ay04w4cjqf.execute-api.eu-central-1.amazonaws.com/production/photos/crop', [
                    'headers' => [
                        'Authorization' => 'Bearer 67b0ea24-c4dd-40b2-b420-b335c08c13fa'
                    ],
                    'json' => [
                        'input' => [
                            'bucket' => env('S3_BUCKET'),
                            'key' => config('custom.aws.class.original_photos_path') . $filename
                        ],
                        'outputs' => $outputs
                    ]
                ]);

                $cropper_response = json_decode((string) $cropper_request->getBody());

                if (count($cropper_response->data)) {
                    foreach($outputs as $key => $value) {
                        $cropped = $cropper_response->data[$key];

                        if ($cropped) {
                            $photo->ext = pathinfo($cropped->key, PATHINFO_EXTENSION);

                            if ($cropped->isHardCrop) {
                                $photo->thumbnail_width = $cropped->size->width;
                                $photo->thumbnail_height = $cropped->size->height;

                            } else {
                                $photo->large_width = $cropped->size->width;
                                $photo->large_height = $cropped->size->height;
                            }
                        }
                    }
                }

                $photo->save();
                array_push($uploaded, $photo);
                $photos_count++;

            } catch (ServerException $e) {
                Log::error("Was not possible to crop group #{$group->id} photo {$photo->id}");
                array_push($uploaded, $photo);
                $photos_count++;

            } catch (Exception $e) {
                Log::error("Was not possible to upload group #{$group->id} photo {$photo->id}");
                array_push($failed, [
                    'filename' => $file->getClientOriginalName(),
                    'errors' => ['Was not possible to upload the photo.']
                ]);
            }
        }

        return response()->json([
            'uploaded' => $uploaded,
            'failed' => $failed
        ]);
    }

    /**
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group) {
        return response()->json($group->photos->map(function($photo) {
            return [
                'id' => $photo->id,
                'w' => $photo->large_width,
                'h' => $photo->large_height,
                'src' => $photo->large_src
            ];
        }));
    }

    /**
     * @param  \App\Group  $group
     * @param  \App\GroupPhoto  $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, GroupPhoto $photo) {
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

            $fileTypes = self::SUPPORTED_FILE_TYPES;
            array_push($fileTypes, $photo->ext);

            foreach($fileTypes as $fileType) {
                $original = config('custom.aws.class.original_photos_path') . $photo->hash . '.' . $fileType;

                if (Storage::disk('s3')->exists($original)) {
                    // Deletes $fn from s3 bucket, if it exists
                    Storage::disk('s3')->delete($original);
                }

                unset($original);
            }

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

    /**
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function cover(Group $group) {
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