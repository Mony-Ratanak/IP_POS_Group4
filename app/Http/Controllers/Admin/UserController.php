<?php

namespace App\Http\Controllers\Admin;

// ================================>> Core Library
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

// ================================>> Cistom Library
use App\Http\Controllers\MainController;
use App\Models\User\User;
use App\Models\User\Type;
use App\Services\FileUpload;

class UserController extends MainController
{

    public function getType()
    {
        $data = Type::get();
        return $data;
    }

    // get all data user
    public function listing(Request $req)
    {

        // //Retrieve the ID od the currently authenticated user
        // $userId = auth()->user()->id;

        // //Not include recorded itself
        // $users = $users->where('id', '<>', $userId);

        // //Order Users from Latest ID
        // $users = $users->orderBy('id', 'desc');

        // Select data from the 'users' table
        $data = User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at','updated_at', 'is_active')
            ->with(['type']);  // Eager loading the 'type' relationship to avoid N+1 query problem
            
        // Filter the data based on the search key provided in the request
        if ($req->key && $req->key != '') {
            // Add a WHERE clause to filter records where 'name' or 'phone' contains the search key
            $data = $data->where('name', 'LIKE', '%' . $req->key . '%')->orWhere('phone', 'LIKE', '%' . $req->key . '%');
        }
        
        // Order the data by 'id' in descending order
        $data = $data->orderBy('id', 'desc')
            ->paginate($req->limit ? $req->limit : 10);  // Paginate the result, default to 10 items per page if 'limit' is not provided

        // //Pagination format
        // $pagination = [
        //     'total'           => $users->total(),
        //     'count'           => $users->count(),
        //     'per_page'        => $users->perPage(),
        //     'current_page'    => $users->currentPage(),
        //     'total_pages'     => $users->lastPage()
        // ];

        // return new JsonResponse([
        //     'status'      => 'success',
        //     'data'        => $users->items(),
        //     'pagination'  => $pagination
        // ], HttpStatus::HTTP_OK);
        
        // Return the paginated data as JSON response with HTTP status code 200 (OK)
        return response()->json($data, Response::HTTP_OK);
    }

     // view 1 user
    public function view($id = 0)
    {
        // Select specific columns from the 'users' table and eager load the 'type' relationship
        $data = User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at', 'is_active')
            ->with(['type' => function ($data) {
                $data->select('id', 'name');
            }])
            ->find($id);  // Retrieve a user by their ID
         
        if ($data) {
            // If user data is found, return it as a JSON response with HTTP status code 200 (OK)
            return response()->json($data, 200);
 
        } else {
 
            // If user data is not found, return a JSON response with an error message and HTTP status code 400 (Bad Request)
            return response()->json([
                'status'  => 'fail',
                'message' => 'រកទិន្នន័យមិនឃើញក្នុងប្រព័ន្ធ'   
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // create new user
    public function create(Request $req)
    {
        // Validation rules for the incoming request data
        $this->validate(
            $req,
            [
                'type_id'     => 'required|min:1|max:20',
                'name'     => 'required|min:1|max:20',
                'phone'    => 'required|unique:user,phone',
                'password' => 'required|min:6|max:20',
                'email'    => 'unique:user,email'
            ],
            [
                'name.required'     => 'សូមវាយបញ្ចូលឈ្មោះរបស់អ្នក',
                'phone.required'    => 'សូមវាយបញ្ចូលលេខទូរស័ព្ទរបស់អ្នក',
                'phone.unique'      => 'លេខទូរស័ព្ទនេះត្រូវបានប្រើប្រាស់រួចហើយនៅក្នុងប្រព័ន្ធ',
                'email.unique'      => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ',
                'password.required' => 'សូមវាយបញ្ចូលពាក្យសម្ងាត់របស់អ្នក',
                'password.min'      => 'សូមបញ្ចូលលេខសម្ងាត់ធំជាងឬស្មើ៦',
                'password.max'      => 'សូមបញ្ចូលលេខសម្ងាត់តូចឬស្មើ២០'
            ]
        );

        // Creating a new User instance and populating its attributes
        $user            =   new User;
        $user->name      =   $req->name;
        $user->type_id   =   $req->type_id;
        $user->phone     =   $req->phone;
        $user->email     =   $req->email;
        $user->password  =   Hash::make($req->password);    // Hashing the password for security
        $user->is_active =   1;                             // Assuming a new user is active by default
        $user->avatar    =   'static/icon/user.png';        // Default avatar path

        // Uploading and setting the user's avatar if an image is provided in the request
        if ($req->image) {

            $image     = FileUpload::uploadFile($req->image, 'users', $req->fileName);
            if ($image['url']) {
                $user->avatar = $image['url'];
            }
        }

        // Saving the new user to the database
        $user->save();

        // Returning a JSON response with the newly created user's data and a success message
        return response()->json([

            'user'  => User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at','updated_at', 'is_active')->with(['type'])->find($user->id),
            'message' => 'User: ' . $user->name . ' has been successfully created.'
        ], Response::HTTP_OK);
    }

    // update info user
    public function update(Request $req, $id = 0)
    {
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'name'     => 'required',
                'phone'    => 'required',
            ],
            // Custom error messages for validation rules
            [
                'name.required'     => 'សូមវាយបញ្ចូលឈ្មោះរបស់អ្នក',
                'phone.required'    => 'សូមវាយបញ្ចូលលេខទូរស័ព្ទរបស់អ្នក',
            ]
        );

        // Check if the provided phone number is already in use by another user
        $check_phone  = User::where('id','!=',$id)->where('phone',$req->phone)->first();
        if($check_phone){

            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'លេខទូរស័ព្ទនេះត្រូវបានប្រើប្រាស់រួចហើយនៅក្នុងប្រព័ន្ធ',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Check if the provided email is already in use by another user
        $check_email  = User::where('id','!=',$id)->where('email',$req->email)->first();
        if($check_email){

            return response()->json([

                'status'    => 'បរាជ័យ',
                'message'   => 'អ៊ីមែលនេះមានក្នុងប្រព័ន្ធរួចហើយ',
            ], Response::HTTP_BAD_REQUEST);
        }

        // Start updating data for the user with the given ID
        $user = User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at', 'is_active')->with(['type'])->find($id);
        if ($user) {

            // $user->name     =  $req->name ?? $user->name;
            $user->name      =   $req->name;
            $user->type_id   =   $req->type_id;
            $user->phone     =   $req->phone;
            $user->email     =   $req->email;
            $user->is_active =   $req->is_active;

            // Need to create folder before storing images
            if ($req->image) {
                $image     = FileUpload::uploadFile($req->image, 'users', $req->fileName);
                if ($image['url']) {
                    $user->avatar = $image['url'];
                }
            }

            // save data
            $user->save();

            return response()->json([

                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានកែប្រែ',
                'user'      => $user,
            ], Response::HTTP_OK);

        } else {
            return response()->json([

                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវទេ',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // delete user
    public function delete($id = 0)
    {
        // Find the user data by ID
        $data = User::find($id);

        // Check if the user data exists
        if ($data) {

            // If the user data exists, delete it
            $data->delete();

            // Return a JSON response indicating success
            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានលុយចេញពីប្រព័ន្ធ',
            ], Response::HTTP_OK);
        }
        else {

            // If the user data does not exist, return a JSON response indicating failure
            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យដែលផ្តល់ឲ្យមិនត្រូវ',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // change password for user
    public function changePassword(Request $req, $id = 0)
    {
        //==============================>> Check validation
        $this->validate($req, [

            'password'          => 'required|min:6|max:20',
            'confirm_password'  => 'required|same:password',
        ], 
        // Custom error messages for validation rules
        [
            'password.required'         => 'សូមបញ្ចូលលេខសម្ងាត់',
            'password.min'              => 'សូមបញ្ចូលលេខសម្ងាត់ធំជាងឬស្មើ៦',
            'password.max'              => 'សូមបញ្ចូលលេខសម្ងាត់តូចឬស្មើ២០',
            'confirm_password.required' => 'សូមបញ្ចូលបញ្ជាក់ពាក្យសម្ងាត់',
            'confirm_password.same'     => 'សូមបញ្ចូលបញ្ជាក់ពាក្យសម្ងាត់ឲ្យដូចលេខសម្ងាត់',

        ]);

        //==============================>> Start Updating Password
        // Find the user by ID
        $user = User::find($id);

        // Check if the user exists
        if ($user) {

        // Update the user's password and set the password_last_updated_at field
            $user->password                 = Hash::make($req->password);
            $user->password_last_updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $user->save();

            // Return a JSON response indicating success
            return response()->json(['message' => 'លេខសម្ងាត់របស់ត្រូវបានកែប្រែ', 'user' => $user], Response::HTTP_OK);

        } 
        else {

            return response()->json(['message' => 'មិនមានទិន្នន័យក្នុងប្រព័ន្ធ'], Response::HTTP_BAD_REQUEST);
        }
    }

    // block user
    public function block(Request $req, $id = 0)
    {
        $validate = $req->validate([
            'status' => 'required|boolean',
        ]);
        //==============================>> Start Updating data
        // Find the user by ID and eager load the 'type' relationship
        $user = User::select('id', 'name', 'phone', 'email', 'type_id', 'avatar', 'created_at', 'is_active')
            ->with(['type'])
            ->find($id);

        // Check if the user exists
        if ($user) {

            // Toggle the 'is_active' field (block or unblock the user)
            // $user->is_active  =  !$user->is_active;

            $user->is_active = $req -> input( 'status');
            $user->save();

            // Return a JSON response indicating success
            return response()->json([
                'status'    => 'Success',
                'message'   => 'User successfully modified',
                'user'      => $user,
            ], Response::HTTP_OK);

        } 
        else {
            return response()->json([
                'status'    => 'Fail',
                'message'   => 'Invalid data',
            ], Response::HTTP_BAD_REQUEST);
        }
    } 
}
