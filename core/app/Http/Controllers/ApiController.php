<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Tymon\JWTAuth\Claims\Custom;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function signup(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'first_name' => 'required', 'string', 'max:255',
            'last_name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $url = "http://127.0.0.1:8002/api/register";
        // echo $url;
        $body = json_encode($data);
        // echo "<pre>";
        // print_r($url);
        // die;
        $response = CustomHelper::Call_Api($method = 'POST',$url,$body);
        $data = json_decode($response,false);
        $aResponse = [
            'success' => $data->success,
            'message' => $data->message,
            'data' => (isset($data->data)) ? $data->data : ''
        ];

        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');

        $validator = Validator::make($credentials,[
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator],Response::HTTP_OK);
        }
        $url = "http://127.0.0.1:8002/api/login";
        $body = json_encode($credentials);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);
        $aResponse = [
            'success' => $data->success,
            'message' => $data->message,
            'token' => (isset($data->token)) ? $data->token : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);

    }

    public function userDetails(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $token = $request->all();
        $url = "http://127.0.0.1:8002/api/get_user";
        $body = json_encode($token);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);
        // echo "<pre>";
        // print_r($data);
        // die;
        $aResponse = [
            'success' => $data->success,
            'message' => $data->message,
            'data' => (isset($data->data)) ? $data->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);

    }

    public function updateUser(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'first_name' => 'required', 'string', 'max:255',
            'last_name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $url = "http://127.0.0.1:8002/api/update_user";
        // echo $url;
        $body = json_encode($data);
        // echo "<pre>";
        // print_r($url);
        // die;
        $response = CustomHelper::Call_Api($method = 'POST',$url,$body);
        $data = json_decode($response,false);
        $aResponse = [
            'success' => (isset($data->success)) ? $data->success : '',
            'message' => (isset($data->message)) ? $data->message : '',
            'data' => (isset($data->data)) ? $data->data : ''
        ];

        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $token = $request->all();
        $url = "http://127.0.0.1:8002/api/delete_user";
        $body = json_encode($token);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);
        // echo "<pre>";
        // print_r($data);
        // die;
        $aResponse = [
            'success' => $data->success,
            'message' => $data->message,
            'data' => (isset($data->data)) ? $data->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function studentList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'role_id' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $data = $request->all();

        if($data['role_id'] == 100){
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access the data'
            ],Response::HTTP_BAD_REQUEST);
        }

        $url = "http://127.0.0.1:8002/api/student_list";
        $body = json_encode($data);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);
        // echo "<pre>";
        // print_r($data);
        // die;
        $aResponse = [
            'success' => (isset($data->success)) ? $data->success : '',
            'message' => $data->message,
            'data' => (isset($data->data)) ? $data->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);

    }

    public function teacherList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'role_id' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $data = $request->all();

        if($data['role_id'] == 100 || $data['role_id'] == 101){
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access the data'
            ],Response::HTTP_BAD_REQUEST);
        }

        $url = "http://127.0.0.1:8002/api/teacher_list";
        $body = json_encode($data);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);
        // echo "<pre>";
        // print_r($data);
        // die;
        $aResponse = [
            'success' => $data->success,
            'message' => $data->message,
            'data' => (isset($data->data)) ? $data->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);

    }

    public function approveUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'role_id' => 'required',
            'status' => 'required',
            'user_id' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $data = $request->all();

        if($data['role_id'] == 100 || $data['role_id'] == 101){
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access the data'
            ],Response::HTTP_BAD_REQUEST);
        }

        $url = "http://127.0.0.1:8002/api/approve_user";
        $body = json_encode($data);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);

        if(isset($data->success) && $data->success){
            $notify_url = "http://127.0.0.1:8003/api/mail_notification";
            $notify_body = json_encode($data->data);
            $notify_api = CustomHelper::Call_Api($method = "POST",$notify_url,$notify_body);
        }
        $aResponse = [
            'success' => $data->success,
            'message' => $data->message,
            'data' => (isset($data->data)) ? $data->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function assignTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'role_id' => 'required',
            'assigned_status' => 'required',
            'assigned_to' => 'required',
            'student_id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $data = $request->all();

        if($data['role_id'] == 100 || $data['role_id'] == 101){
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access the data'
            ],Response::HTTP_BAD_REQUEST);
        }

        $url = "http://127.0.0.1:8002/api/assign_teacher";
        $body = json_encode($data);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);
        // echo "<pre>";
        // print_r($data);
        // die;
        if(isset($data->success) && $data->success){
            $notify_url = "http://127.0.0.1:8003/api/db_notification";
            $notify_body = json_encode($data->data->teacher_data);
            $notify_api = CustomHelper::Call_Api($method = "POST",$notify_url,$notify_body);
        }
        $aResponse = [
            'success' => (isset($data->success)) ? $data->success : '',
            'message' => (isset($data->message)) ? $data->message : '',
        ];
        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function notificationCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $data = $request->all();
        $url = "http://127.0.0.1:8002/api/verify_user";
        $body = json_encode($data);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);

        if(isset($data->success) && $data->success){
            $notify_url = "http://127.0.0.1:8003/api/notification_count";
            $notify_body = json_encode($data->data);
            $notify_api = CustomHelper::Call_Api($method = "POST",$notify_url,$notify_body);

            $notify_data = json_decode($notify_api,false);
            return response()->json([
                'success' => isset($notify_data->success) ? $notify_data->success : '',
                'message' => isset($notify_data->message) ? $notify_data->message : '',
                'data' => isset($notify_data->data) ? $notify_data->data : ''
            ]);
        }else{
            return response()->json([
                'success' => (isset($data->success)) ? $data->success : '',
                'message' => (isset($data->message)) ? $data->message : ''
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    public function notifyList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator], 200);
        }

        $data = $request->all();

        $url = "http://127.0.0.1:8002/api/verify_user";
        $body = json_encode($data);

        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        $data = json_decode($response,false);

        if(isset($data->success) && $data->success){
            $notify_url = "http://127.0.0.1:8003/api/notification_list";
            $notify_body = json_encode($data->data);
            $notify_api = CustomHelper::Call_Api($method = "POST",$notify_url,$notify_body);

            $notify_data = json_decode($notify_api,false);
            return response()->json([
                'success' => isset($notify_data->success) ? $notify_data->success : '',
                'message' => isset($notify_data->message) ? $notify_data->message : '',
                'data' => isset($notify_data->data) ? $notify_data->data : ''
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "failed to get the data"
            ],Response::HTTP_BAD_REQUEST);
        }
    }
}
