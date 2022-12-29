<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\TeacherSubject;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->all();

          //Request is valid, create new user
          $userData['role_id'] = isset($data['role_id']) ? $data['role_id'] : '';
          $userData['first_name'] = isset($data['first_name']) ? $data['first_name'] : '';
          $userData['last_name'] = isset($data['last_name']) ? $data['last_name'] : '';
          $userData['email'] = isset($data['email']) ? $data['email'] : '';
          $userData['password'] = isset($data['password']) ? Hash::make($data['password']) : '';
          if($request->hasFile('profile_picture')){
              $file= $request->file('profile_picture');
              $filename= date('YmdHi').$file->getClientOriginalName();
              $file->move(public_path('uploads'), $filename);
              $userData['profile_picture'] = $filename;
          }
          $userData['status'] = 2;
          $user = User::create($userData);

          $uDetails['user_id'] = $user->id;
          $uDetails['address'] = isset($data['address']) ? $data['address'] : '';
          $uDetails['current_school'] = isset($data['current_school']) ? $data['current_school'] : '';
          $uDetails['previous_school'] = isset($data['previous_school']) ? $data['previous_school'] : '';
          $uDetails['exp'] = isset($data['exp']) ? $data['exp'] : null;
          $uDetails['father_name'] = isset($data['father_name']) ? $data['father_name'] : '';
          $uDetails['mother_name'] = isset($data['mother_name']) ? $data['mother_name'] : '';
          $uDetails['assigned_status'] = 2;
          $userDetail = UserDetail::create($uDetails);
          if(isset($data['subject_name']) && count($data['subject_name']) > 0){
              $total_subs = count($data['subject_name']);
              foreach($data['subject_name'] as $key =>  $sub){
                  $tSubject['user_id'] = $user->id;
                  $tSubject['subject_name'] = $sub;
                  TeacherSubject::create($tSubject);
              }
          }
          //User created, return success response
          return response()->json([
              'success' => true,
              'message' => 'User created successfully',
              'data' => $user
          ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email','password');
        //Request is validated
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

 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'message' => "User Logged In Successfully",
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

		//Request is validated, do logout
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

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);
        $user['role_id'] = CustomHelper::$userType[$user->role_id];
        $userDetails = UserDetail::where('user_id',$user->id)->first();
        $user['user_details'] = $userDetails;
        $user['user_details']['assigned_status'] = CustomHelper::$studentStatus[$userDetails->assigned_status];
        $user['user_details']['assigned_to'] = ($userDetails->assigned_to != "") ? User::where('id',$userDetails->assigned_to)->first() : '';
        // echo "<pre>";
        // print_r($user);
        // die;
        if(!$user && !$userDetails){
            return response()->json([
                'success' => false,
                'data' => array(),
                'message' => "Failed To Fetched Data"
            ],200);
        }
        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => "Data Fetched Successfully"
        ],200);
    }

    public function update_user(Request $request){
        $data = $request->all();

        $user = JWTAuth::authenticate($request->token);
        $validator2 = Validator::make($request->all(), [
            'email' => 'required', 'string', 'email', 'max:255','unique:users,email,'.$user->id.',id',
        ]);

        if ($validator2->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator2
            ], 200);
        }
        $userData['first_name'] = isset($data['first_name']) ? $data['first_name'] : '';
        $userData['last_name'] = isset($data['last_name']) ? $data['last_name'] : '';
        $userData['email'] = isset($data['email']) ? $data['email'] : '';
        $userData['password'] = isset($data['password']) ? Hash::make($data['password']) : '';
        if($request->hasFile('profile_picture')){
            $file= $request->file('profile_picture');
            $filename= date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $userData['profile_picture'] = $filename;
        }
        $userData['status'] = CustomHelper::NOTAPPROVE;
        $userUpdate = User::where('id',$user->id)->update($userData);

        $uDetails['address'] = isset($data['address']) ? $data['address'] : '';
        $uDetails['current_school'] = isset($data['current_school']) ? $data['current_school'] : '';
        $uDetails['previous_school'] = isset($data['previous_school']) ? $data['previous_school'] : '';
        $uDetails['exp'] = isset($data['exp']) ? $data['exp'] : null;
        $uDetails['father_name'] = isset($data['father_name']) ? $data['father_name'] : '';
        $uDetails['mother_name'] = isset($data['mother_name']) ? $data['mother_name'] : '';

        $userDetail = UserDetail::where('user_id',$user->id)->update($uDetails);

        if(isset($data['subject_name']) && count($data['subject_name']) > 0){
            $total_subs = count($data['subject_name']);
            foreach($data['subject_name'] as $key =>  $sub){
                $tSubject['subject_name'] = $sub;
                TeacherSubject::where('user_id',$user->id)->update($tSubject);
            }
        }
        $userData = User::where('id',$user->id)->first();
         //User created, return success response
         return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data' => $userData
        ], Response::HTTP_OK);
    }

    public function delete_user(Request $request){

        $user = JWTAuth::authenticate($request->token);

        UserDetail::where('user_id',$user->id)->delete();
        TeacherSubject::where('user_id',$user->id)->delete();
        User::where('id',$user->id)->delete();
        JWTAuth::invalidate($request->token);

        return response()->json([
            'success' => true,
            'message' => 'Record Deleted Successfully',
        ]);
    }

    public function student_list(Request $request)
    {
        $data = $request->all();

        $user = JWTAuth::authenticate($data['token']);

        $aRows = User::join('user_details','user_details.user_id','users.id')
                        ->where('users.role_id','!=',CustomHelper::ADMIN)
                        ->where('users.id','!=',$user->id)
                        ->where('users.role_id','!=',CustomHelper::TEACHER);
        if($user->role_id == CustomHelper::TEACHER){
            $aRows = $aRows->where('users.role_id',CustomHelper::STUDENT)
                            ->where('user_details.assigned_to',$user->id);
        }

        $aRows = $aRows->select('users.*','user_details.assigned_status','assigned_to','user_details.address')->get();

        return response()->json([
            'success' => true,
            'message' => 'Record Fetched Successfully',
            'data' => $aRows
        ]);

    }

    public function teacher_list(Request $request)
    {
        $data = $request->all();

        $user = JWTAuth::authenticate($data['token']);
        $aRows = User::join('user_details','user_details.user_id','=','users.id')
                ->where('users.id','!=',$user->id)
                ->where('users.role_id',101)
                ->select('users.*','user_details.address','user_details.current_school','user_details.previous_school','user_details.exp')
                ->get();

        $data = array();
        if($aRows){
            $data = $aRows;
        }

        return response()->json([
            'success' => true,
            'message' => 'Record Fetched Successfully',
            'data' => $data
        ]);

    }

    public function approve_user(Request $request)
    {
        $data = $request->all();
        $user = JWTAuth::authenticate($data['token']);

        $id = $data['user_id'];
        $status = $data['status'];
        User::where('id',$id)->update(['status' => $status]);

        $userData = User::where('id',$id)->first();

        return response()->json([
            'success' => true,
            'message' =>  "User Approved Successfully",
            'data' => $userData
        ],Response::HTTP_OK);
    }

    public function assign_teacher(Request $request)
    {
        $data = $request->all();
        $user = JWTAuth::authenticate($data['token']);

        $uDetails['assigned_status'] = CustomHelper::ASSIGNED;
        $uDetails['assigned_to'] = $data['assigned_to'];
        UserDetail::where('user_id',$data['student_id'])->update($uDetails);
        $teacher_data = User::where('id',$data['assigned_to'])->first();
        $student = User::where('id',$data['student_id'])->first();
        $user_data['teacher_data'] = $teacher_data;
        $user_data['teacher_data']['student_fname'] = $student->first_name;
        $user_data['teacher_data']['student_lname'] = $student->last_name;

        return response()->json([
            'success' => true,
            'message' =>  "Teacher Assigned Successfully",
            'data' => $user_data,
        ],Response::HTTP_OK);
    }

    public function verify_user(Request $request)
    {
        $data = $request->all();
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
