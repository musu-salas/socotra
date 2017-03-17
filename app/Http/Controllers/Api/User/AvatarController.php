<?php namespace App\Http\Controllers\Api\User;

use App\User;
use App\Http\Controllers\Controller;
use Request;
use Validator;
use Image;
use Storage;

class AvatarController extends Controller {

    /**
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user) {
        $avatar = config('custom.aws.user.avatar.path') . $user->avatar;

        if ($user->avatar && Storage::disk('local')->has($avatar)) {
            Storage::disk('local')->delete($avatar);
        }

        return response('');
    }

    /**
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function crop(User $user) {
        $validation = Validator::make(Request::all(), [
            'string' => 'required|string',
            'type' => 'in:jpeg'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors()
            ], 500);
        }

        define('AVATAR_EXTENSION', config('custom.aws.user.avatar.extension'));

        $base64 = preg_replace('#^data:image/[^;]+;base64,#', '', Request::input('string'));
        $img = Image::make($base64);

        $fileName = md5($user->id . time()) . sha1($base64) . '.' . AVATAR_EXTENSION;

        $img->encode(AVATAR_EXTENSION);
        Storage::disk('s3')->put(config('custom.aws.user.avatar.path') . $fileName, (string) $img);
        $img->destroy();

        if ($user->avatar) {
            if (!$user->isExternalAvatar) {
                Storage::disk('s3')->delete(config('custom.aws.user.avatar.path') . $user->avatar);
            }

            if (Storage::disk('local')->has(config('custom.aws.user.avatar.path') . $user->avatar)) {
                Storage::disk('local')->delete(config('custom.aws.user.avatar.path') . $user->avatar);
            }
        }

        $user->avatar = $fileName;
        $user->save();

        return response()->json($user->avatar_src);
    }

    /**
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function store(User $user) {
        $validation = Validator::make(Request::all(), [
            'avatar' => 'required|image|mimes:jpeg'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors()
            ], 500);
        }

        $file = Request::file('avatar');

        if ($file->getSize() > config('custom.class.max_photosize')) {
            $megabytes = $this->formatBytes(config('custom.class.max_photosize'));
            return response()->json([
                'errors' => [sprintf('Avatar must be under %s.', $megabytes)]
            ]);
        }

        $fit = function($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        };

        define('MAX_AVATAR_WIDTH', config('custom.aws.user.avatar.max_width'));
        define('MAX_AVATAR_HEIGHT', config('custom.aws.user.avatar.max_height'));
        define('AVATAR_EXTENSION', config('custom.aws.user.avatar.extension'));

        $fileName = md5($user->id . time()) . sha1_file($file) . '.' . AVATAR_EXTENSION;
        $img = Image::make($file);
        $width = $img->width();
        $height = $img->height();

        if ($width > $height) {
            $img->resize(MAX_AVATAR_WIDTH, null, $fit);

            if ($img->height() > MAX_AVATAR_HEIGHT) {
                $img->resize(null, MAX_AVATAR_HEIGHT, $fit);
            }

        } elseif ($height > $width) {
            $img->resize(null, MAX_AVATAR_HEIGHT, $fit);

            if ($img->width() > MAX_AVATAR_WIDTH) {
                $img->resize(MAX_AVATAR_WIDTH, null, $fit);
            }

        } else {
            $img->resize(min(MAX_AVATAR_WIDTH, MAX_AVATAR_HEIGHT), null, $fit);
        }

        $img->encode(AVATAR_EXTENSION);
        Storage::disk('s3')->put(config('custom.aws.user.avatar.path') . $fileName, (string) $img);
        Storage::disk('local')->put(config('custom.aws.user.avatar.path') . $fileName, (string) $img);

        if ($user->avatar) {
            if (!$user->isExternalAvatar) {
                Storage::disk('s3')->delete(config('custom.aws.user.avatar.path') . $user->avatar);
            }

            if (Storage::disk('local')->has(config('custom.aws.user.avatar.path') . $user->avatar)) {
                Storage::disk('local')->delete(config('custom.aws.user.avatar.path') . $user->avatar);
            }
        }

        $user->avatar = $fileName;
        $user->save();
        $img->destroy();

        return response()->json('/' . basename(config('filesystems.disks.local.root')) . '/' . config('custom.aws.user.avatar.path') . $fileName);
    }
}