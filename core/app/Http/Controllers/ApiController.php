<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    public function signup(Request $request)
    {
        $data = $request->all();
        $url = CustomHelper::USERAPI."register";
        $body = json_encode($data);
        $response = CustomHelper::Call_Api($method = 'POST',$url,$body);
        $aResponse = [
            'success' => $response->success,
            'message' => $response->message,
            'data' => (isset($response->data)) ? $response->data : ''
        ];

        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email','password');
        $url = CustomHelper::USERAPI."login";
        $body = json_encode($credentials);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);
        $aResponse = [
            'success' => $response->success,
            'message' => $response->message,
            'token' => (isset($response->token)) ? $response->token : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);

    }

    public function userDetails(Request $request)
    {
        $token = $request->all();
        $url = CustomHelper::USERAPI."get_user";
        $body = json_encode($token);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);
        $aResponse = [
            'success' => $response->success,
            'message' => $response->message,
            'data' => (isset($response->data)) ? $response->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);

    }

    public function updateUser(Request $request)
    {
        $data = $request->all();
        $url = CustomHelper::USERAPI."update_user";
        $body = json_encode($data);
        $response = CustomHelper::Call_Api($method = 'PUT',$url,$body);
        // echo "<pre>";
        // print_r($url);
        // die;
        $aResponse = [
            'success' => (isset($response->success)) ? $response->success : '',
            'message' => (isset($response->message)) ? $response->message : '',
            'data' => (isset($response->data)) ? $response->data : ''
        ];

        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function deleteUser(Request $request)
    {
        $token = $request->all();
        $url = CustomHelper::USERAPI."delete_user";
        $body = json_encode($token);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);
        $aResponse = [
            'success' => $response->success,
            'message' => $response->message,
            'data' => (isset($response->data)) ? $response->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function studentList(Request $request)
    {
        $data = $request->all();
        $url = CustomHelper::USERAPI."student_list";
        $body = json_encode($data);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);
        $aResponse = [
            'success' => (isset($response->success)) ? $response->success : '',
            'message' => $response->message,
            'data' => (isset($response->data)) ? $response->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);

    }

    public function teacherList(Request $request)
    {
        $data = $request->all();
        $url = CustomHelper::USERAPI."teacher_list";
        $body = json_encode($data);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);
        $aResponse = [
            'success' => $response->success,
            'message' => $response->message,
            'data' => (isset($response->data)) ? $response->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);

    }

    public function approveUser(Request $request)
    {
        $data = $request->all();
        $url = CustomHelper::USERAPI."approve_user";
        $body = json_encode($data);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);
        $data = json_decode($response,false);
        if(isset($response->success) && $response->success){
            $notify_url = CustomHelper::NOTIFYAPI."mail_notification";
            $notify_body = json_encode($response->data);
            $notify_api = CustomHelper::Call_Api($method = "POST",$notify_url,$notify_body);
        }
        $aResponse = [
            'success' => $response->success,
            'message' => $response->message,
            'data' => (isset($response->data)) ? $response->data : ''
        ];
        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function assignTeacher(Request $request)
    {
        $data = $request->all();

        $url = CustomHelper::USERAPI."assign_teacher";
        $body = json_encode($data);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);
        if(isset($response->success) && $response->success){
            $notify_url = CustomHelper::NOTIFYAPI."db_notification";
            $notify_body = json_encode($response->data->teacher_data);
            $notify_api = CustomHelper::Call_Api($method = "POST",$notify_url,$notify_body);
        }
        $aResponse = [
            'success' => (isset($response->success)) ? $response->success : '',
            'message' => (isset($response->message)) ? $response->message : '',
        ];
        return response()->json($aResponse,Response::HTTP_OK);
    }

    public function notificationCount(Request $request)
    {
        $data = $request->all();
        $url = CustomHelper::USERAPI."verify_user";
        $body = json_encode($data);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);

        if(isset($response->success) && $response->success){
            $notify_url = CustomHelper::NOTIFYAPI."notification_count";
            $notify_body = json_encode($response->data);
            $notify_api = CustomHelper::Call_Api($method = "POST",$notify_url,$notify_body);
            return response()->json([
                'success' => isset($notify_api->success) ? $notify_api->success : '',
                'message' => isset($notify_api->message) ? $notify_api->message : '',
                'data' => isset($notify_api->data) ? $notify_api->data : ''
            ]);
        }else{
            return response()->json([
                'success' => (isset($response->success)) ? $response->success : '',
                'message' => (isset($response->message)) ? $response->message : ''
            ],Response::HTTP_BAD_REQUEST);
        }
    }

    public function notifyList(Request $request)
    {
        $data = $request->all();

        $url = CustomHelper::USERAPI."verify_user";
        $body = json_encode($data);
        $response = CustomHelper::Call_Api($method="POST",$url,$body);
        if(isset($response->success) && $response->success){
            $notify_url = CustomHelper::NOTIFYAPI."notification_list";
            $notify_body = json_encode($response->data);
            $notify_api = CustomHelper::Call_Api($method = "POST",$notify_url,$notify_body);
            return response()->json([
                'success' => isset($notify_api->success) ? $notify_api->success : '',
                'message' => isset($notify_api->message) ? $notify_api->message : '',
                'data' => isset($notify_api->data) ? $notify_api->data : ''
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => "failed to get the data"
            ],Response::HTTP_BAD_REQUEST);
        }
    }
}
