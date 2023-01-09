<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function register(UserRequest $request)
    {
        $data = $request->validated();
        $user_data = $request->only(['role_id','first_name','last_name','email','password']);
        $user = User::create($user_data);
        if($request->hasFile('profile_picture')){
            $user->profile_picture = CustomHelper::uploadImage($request->file('profile_picture'));
            $user->save();
        }
        $user_detail_data = $request->except(['role_id','first_name','last_name','email','password']);
        $user->userDetail()->create($user_detail_data);

        if($request->has('subject_name') && count($request->input('subject_name')) > 0){
            foreach($request->input('subject_name') as $key =>  $sub){
                $subject['subject_name'] = $sub;
                $user->teacherData()->create($subject);
            }
        }
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);

    }

    public function authenticate(AuthLoginRequest $request)
    {
        $validate = $request->validated();
        $credentials = $request->only(['email','password']);
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
        return response()->json([
            'success' => true,
            'message' => "User Logged In Successfully",
            'token' => $token,
        ]);
    }

    public function logout(AuthenticateRequest $request)
    {
        $validate = $request->validated();
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(AuthenticateRequest $request)
    {
        $token = $request->validated();
        $authenticate = JWTAuth::authenticate($request->token);
        $user = User::with(['userDetail.teacherDetail'])->where('id',$authenticate->id)->get();
        if(!$user){
            return response()->json([
                'success' => false,
                'data' => array(),
                'message' => "Failed To Fetched Data"
            ],Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => "Data Fetched Successfully"
        ],Response::HTTP_OK);
    }

    public function update_user(UserRequest $request){
        $validate = $request->validated();

        $user = JWTAuth::authenticate($request->token);
        $user_data = $request->only(['first_name','last_name','email']);
        $userUpdate = User::find($user->id)->update($user_data);
        if($request->hasFile('profile_picture')){
            $user->profile_picture = CustomHelper::uploadImage($request->file('profile_picture'));
            $user->update();
        }
        $user_detail_data = $request->except(['first_name','last_name','email','token','password','subject_name']);
        $userDetailUpdate = $user->userDetail()->update($user_detail_data);

        if($request->has('subject_name') && count($request->input('subject_name')) > 0){
            foreach($request->input('subject_name') as $key =>  $sub){
                $user->teacherData()->update(['subject_name' => $sub]);
            }
        }
        $userUpdatedData = User::find($user->id);
        return response()->json([
           'success' => true,
           'message' => 'User updated successfully',
           'data' => $userUpdatedData
        ], Response::HTTP_OK);
    }

    public function delete_user(AuthenticateRequest $request){

        $validate = $request->validated();
        $user = JWTAuth::invalidate($request->token);
        $user->userDetail()->delete();
        $user->teacherData()->delete();
        $user->delete();

        JWTAuth::invalidate($request->token);

        return response()->json([
            'success' => true,
            'message' => 'Record Deleted Successfully',
        ]);
    }

    public function student_list(AuthenticateRequest $request)
    {
        $data = $request->validated();
        $user = JWTAuth::authenticate($data['token']);
        $aRows = User::with(['userDetail.teacherDetail'])
                ->whereNotIn('role_id',[CustomHelper::ADMIN,CustomHelper::TEACHER])
                ->get();

        return response()->json([
            'success' => true,
            'message' => 'Record Fetched Successfully',
            'data' => $aRows
        ]);

    }

    public function teacher_list(AuthenticateRequest $request)
    {
        $data = $request->validated();
        $user = JWTAuth::authenticate($data['token']);
        $aRows = User::with(['userDetail.teacherSubject'])
                ->whereNotIn('role_id',[CustomHelper::ADMIN,CustomHelper::STUDENT])
                ->get();
        return response()->json([
            'success' => true,
            'message' => 'Record Fetched Successfully',
            'data' => $data
        ]);

    }

    public function approve_user(AuthenticateRequest $request)
    {
        $data = $request->validated();
        $user = JWTAuth::authenticate($data['token']);
        User::find('id',$request->input('id'))->update(['status' => $request->input('status')]);
        $userData = User::find($request->input('id'));

        return response()->json([
            'success' => true,
            'message' =>  "User Approved Successfully",
            'data' => $userData
        ],Response::HTTP_OK);
    }

    public function assign_teacher(AuthenticateRequest $request)
    {
        $data = $request->validated();
        $authenticate = JWTAuth::authenticate($data['token']);

        $user = User::find($request);
        $user->userDetail()->update(['assigned_status' => CustomHelper::ASSIGNED,'assigned_to' => $request->input('assigned_to')]);
        $teacher_data = User::find($request->input('assigned_to'));
        $student = User::find($request->input('student_id'));
        $user_data['teacher_data'] = $teacher_data;
        $user_data['teacher_data']['student_fname'] = $student->first_name;
        $user_data['teacher_data']['student_lname'] = $student->last_name;

        return response()->json([
            'success' => true,
            'message' =>  "Teacher Assigned Successfully",
            'data' => $user_data,
        ],Response::HTTP_OK);
    }

    public function verify_user(AuthenticateRequest $request)
    {
        $data = $request->validated();
        $user = JWTAuth::authenticate($data['token']);

        if($user){
            return response()->json([
                'success' => true,
                'message' => 'Data Fetched Successfully',
                'data' => $user
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed To Fetch Data',
                'data' => array()
            ]);
        }
    }
}
