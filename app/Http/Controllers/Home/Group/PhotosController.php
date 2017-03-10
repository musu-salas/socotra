<?php namespace App\Http\Controllers\Home\Group;

use App\Group;
use App\GroupPhoto;
use App\Http\Controllers\Controller;
use Auth;
use Log;
use Request;
use Validator;
use Image;
use Storage;

class PhotosController extends Controller {

    private function formatBytes($bytes, $precision = 2) {
        $base = log($bytes, 1024);
        $suffixes = ['bytes', 'kilobytes', 'megabytes', 'gigabytes', 'terabytes'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function index(Group $group) {
        //dd([ini_get('post_max_size'), ini_get('upload_max_filesize')]);

        return view('home.group.photos', [
            'user' => Auth::user(),
            'group' => $group,
            'menu' => $group->menu
        ]);
    }

    /**
     * Redirects to my group location since group index view is not yet planned.
     *
     * @param  \App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function store(Group $group) {
        $validation = Validator::make(Request::all(), [
            'photo' => 'required|image|mimes:jpeg'
        ]);

        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation->errors());
        }

        if ($group->photos_count >= config('custom.class.max_photos')) {
            return redirect()->back()->withErrors([
                'msg' => sprintf('Maximum number of photos is %d.', config('custom.class.max_photos'))
            ]);
        }

        $file = Request::file('photo');

        if ($file->getSize() > config('custom.class.max_photosize')) {
            $megabytes = $this->formatBytes(config('custom.class.max_photosize'));
            return redirect()->back()->withErrors([
                'msg' => sprintf('The photo must be under %s.', $megabytes)
            ]);
        }

        $ext = $file->guessExtension();
        $photos_path = config('custom.aws.class.photos_path');
        $photo = [
            'group_id' => $group->id,
            'hash' => md5($group->id . time()) . sha1_file($file),
            'ext' => ($ext === 'jpeg' || $ext === '') ? 'jpg' : $ext
        ];

        $img = Image::make($file);
        $fit = function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        };

        foreach (config('custom.aws.class.photos') as $name => $size) {
            $width = $img->width();
            $height = $img->height();

            if ($name === 'thumbnail') {
                $img->fit($size['maxw'], $size['maxh'], $fit);

            } elseif ($width > $height) {
                $img->resize($size['maxw'], null, $fit);

                if ($img->height() > $size['maxh']) {
                    $img->resize(null, $size['maxh'], $fit);
                }

            } elseif ($height > $width) {
                $img->resize(null, $size['maxh'], $fit);

                if ($img->width() > $size['maxw']) {
                    $img->resize($size['maxw'], null, $fit);
                }

            } else {
                $img->resize(min($size['maxw'], $size['maxh']), null, $fit);
            }

            $photo[$name . '_width'] = $img->width();
            $photo[$name . '_height'] = $img->height();

            $img->encode($photo['ext']);

            Storage::disk('s3')->put($photos_path . $photo['hash'] . $size['key'] . '.' . $photo['ext'], (string) $img);
            unset($width, $height);
        }

        $img->destroy();
        $photo = GroupPhoto::create($photo);

        if (!$group->cover_photo_id || $group->photos_count == 1) {
            $group->cover_photo_id = $photo->id;
            $group->cover_photo_offset = 0;
            $group->save();
        }

        return redirect()->back()->with('success-message', 'Photo is uploaded.');
    }
}
