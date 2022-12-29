<?php

namespace App\Http\Controllers;

use App\Models\Notification as ModelsNotification;
use App\Notifications\MailNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ApiController extends Controller
{
    public function mail_notification(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];

        $details = [
            'greeting' => 'Hi '.$data['first_name']. ' ' .$data['last_name'],
            'body' => 'Your Profile has been approved',
            'thanks' => 'Thank you for using Sprint from Hestabit !',
            'actionText' => 'View My Site',
            'actionURL' => url('/'),
        ];

        Notification::route('mail',$email)->notify(new MailNotification($details));

        return response()->json([
            'success' => true,
            'message' => "Mail Send Successfully",
        ]);
    }

    public function db_notification(Request $request)
    {
        $data = $request->all();

        $details = [
            'data' => $data['student_fname'] . ' ' . $data['student_lname'] . ' has been assigned to You'
        ];
        $notify['notifiable_id'] = $data['id'];
        $notify['data'] = json_encode($details);
        $notify['notifiable_type'] = "App\Models\User";
        $notify['type'] = "App\Notifications\AssignTeacherNotification";

        $notify_create = ModelsNotification::create($notify);

        return response()->json([
            'success' => true,
            'message' => 'Notification Sent',
        ]);
    }

    public function notification_count(Request $request)
    {
        $data = $request->all();

        $notify_data = ModelsNotification::where('notifiable_id',$data['id'])->whereNull('read_at')->count();

        if($notify_data <= 0){
            return response()->json([
                'success' => false,
                'message' => 'No Data Found',
                'data' => array()
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Fetched Successfully',
            'data' => $notify_data
        ]);
    }

    public function notification_list(Request $request)
    {
        $data = $request->all();
        $notify_data = ModelsNotification::where('notifiable_id',$data['id'])->whereNull('read_at')->select('id','notifiable_id','data')->get();
        if(count($notify_data) <= 0){
            return response()->json([
                'success' => false,
                'message' => 'No Data Found',
                'data' => array()
            ]);
        }
        return response()->json([
            'success' => true,
            'message' => 'Data Fetched Successfully',
            'data' => $notify_data
        ]);

    }
}
