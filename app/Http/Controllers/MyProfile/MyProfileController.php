<?php

namespace App\Http\Controllers\MyProfile;

// ================================>> Core Library
use Illuminate\Http\Request; // For Getting Request from Client
use Illuminate\Http\Response; // To Response Back to Client
use Illuminate\Support\Facades\Hash; // For Encripting Password

// ================================>> Third Party Library
use Tymon\JWTAuth\Facades\JWTAuth; // Get Currently Logged in User
use Carbon\Carbon; // Date Time Format & Calculation

// ================================>> Custom Library
use App\Http\Controllers\MainController;

// Model
use App\Models\User\User;

// Service
use App\Services\FileUpload; // Upload to File Micro Serivce


class MyProfileController extends MainController
{
    // view profile 
    public function view()
    {
        // ===>> Get currently logged in user
        $auth = JWTAuth::parseToken()->authenticate();

        // Retrieve user data based on the user ID from the token
        $user = User::select('id', 'type_id','name', 'phone', 'email', 'avatar')
        ->where('id', $auth->id)
        ->with(['type'])
        ->first();

        // Return the user data as a JSON response
        return response()->json($user, Response::HTTP_OK);
    }

    public function update(Request $req)
    {
        // ===>> Check validation
        $this->validate(
            $req,
            [
                'name'  => 'required|max:50',
                'phone' => 'required|min:9|max:10',
            ],
            [
                'name.required'     => 'Please enter a name',
                'name.max'          => 'Name cannot be over 50 characters',
                
                'phone.required'    => 'Please enter a phone number',
                'phone.min'         => 'Phone number must be at least 9 characters',
                'phone.max'         => 'Phone number cannot be over 10 characters',
            ]
        );

        // ===>> Get currently logged in user
        $auth = JWTAuth::parseToken()->authenticate();

        $user = User::findOrFail($auth->id);

        // ===>> Check if user is valid
        if ($user) {

            // ===>> Prepare data
            $user->name         = $req->name;
            $user->phone        = $req->phone;
            $user->email        = $req->email;
            $user->updated_at   = Carbon::now()->format('Y-m-d H:i:s');

            // ===>> Upload image
            if ($req->avatar) {

                // ===>> Create folder by date
                $folder = Carbon::today()->format('d-m-y');

                // ===>> Call file service
                $avatar = FileUpload::uploadFile($req->avatar, 'profile/', $req->fileName);

                // ===>> Only valid url can be used
                if ($avatar['url']) {

                    $user->avatar = $avatar['url'];
                }
            }

            // ===>> Save data
            $user->save();

            return response()->json([
                'status'    => 'Success',
                'message'   => 'Personal info has been updated',
                'data'      => [
                                'name'      => $user->name,
                                'phone'     => $user->phone,
                                'email'     => $user->email,
                                'avatar'    => $user->avatar,
                ],
            ], Response::HTTP_OK);
        }
        else {

            return response()->json([
                'status'    => 'Failure',
                'message'   => 'User does not exist',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function changePassword(Request $req)
    {
        // ===>> Check validation
        $this->validate(
            $req,
            [
                'old_password'              => 'required|min:6|max:20',
                'new_password'              => 'required|min:6|max:20',
                'confirm_password'          => 'required|same:new_password',
            ],
            [
                'old_password.required'     => 'Please enter old password',
                'old_password.min'          => 'Old Password must be at least 6 characters long',
                'old_password.max'          => 'Old Password cannot be over 20 characters',
                
                'new_password.required'     => 'Please enter new password',
                'new_password.min'          => 'New Password must be at least 6 characters long',
                'new_password.max'          => 'New Password cannot be over 20 characters',
                
                'confirm_password.required' => 'Please enter confirm password',
                'confirm_password.same'     => 'Confirm password must be the same as new password',
            ]
        );

        // ===>> Get currently logged in user
        $auth = JWTAuth::parseToken()->authenticate();

        $user = User::findOrFail($auth->id);

        // ===>> Compare old and new password
        if (Hash::check($req->old_password, $user->password)) {

            $user->password = Hash::make($req->new_password);

            $user->save();

            return response()->json([
                'status'    => 'Success',
                'message'   => 'Password has been changed successfully',
            ], Response::HTTP_OK);
            
        } else {

            return response()->json([
                'status'    => 'Failure',
                'message'   => 'Old password is incorrect',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
