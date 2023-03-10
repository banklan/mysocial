<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request){
        $user = auth('api')->user();

        $validator = $this->validate($request, [
            'user.first_name' => 'required|min:3|max:30',
            'user.last_name' => 'required|min:3|max:30',
            'user.phone' => 'required|max:14',
        ]);

        $user->update([
            $user->first_name = $request->user['first_name'],
            $user->last_name = $request->user['last_name'],
            $user->phone = $request->user['phone'],
        ]);

        return response()->json($user, 201);
    }
    

    public function confirmCurrentPswd(Request $request){
        $user = auth('api')->user();
        $current = $user->password;
        $check = Hash::check($request->password, $current);

        return response()->json($check, 200);
    }

    public function updatePassword(Request $request){
        $this->validate($request, [
            'password' => 'required|min:5|max:20|confirmed',
            'password_confirmation' => 'required'
        ]);

        $user = auth('api')->user();
        $new = $request->password;

        $user->update([
            $user->password = Hash::make($new)
        ]);

        return response()->json(['message' => 'Password changed successfully'], 201);
    }

    public function updateProfilePicture(Request $request){
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,bmp,png,gif'
        ]);

        $file = $request->image;

        if($file){
            $pool = '0123456789abcdefghijklmnopqrstuvwxyz';
            $ext = $file->getClientOriginalExtension();
            $filename = substr(str_shuffle($pool), 0, 12).".".$ext;

            $user = auth('api')->user();
            // unlink old pic if exists
            $old_file = $user->public_id;
            if($old_file){
                \Cloudinary::destroy($old_file);
            }
            $result = $file->storeOnCloudinary('mysocial/profile');
            $publicId = $result->getPublicId();
            $path = $result->getPath();
            $user->update([
                $user->public_id = $publicId,
                $user->picture = $path
            ]);

            return response()->json($user, 200);
        }
    }

}
