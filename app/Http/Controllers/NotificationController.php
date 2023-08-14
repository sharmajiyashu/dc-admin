<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\SentNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
        $categories = SentNotification::orderBy('id','desc')->get();

        
        foreach($categories as $key => $val){
            $dd = [];
            if($val->to_vendors == '1'){
                $dd[] = 'Vendor';
            }
            if($val->to_customers == '1'){
                $dd[] = 'Customer';
            }
            $val['sent_to'] = implode(",",$dd);
        }
        return view('admin.notifications.index',compact('categories'));
    }

    public function edit(){

    }

    public function create (){
        return view('admin.notifications.create');
    }

    public function store(Request $request){

        $validatedData = $request->validate([
            'title' => 'required|string',
            'to_vendors' => 'required_without:to_customers',
            'to_customers' => 'required_without:to_vendors',
        ]);
        $data = [
            'title' => $request->title,
            'body' => $request->body,
        ];
        if(!empty($request->to_customers)){
            $data['to_customers'] = '1';
        }
        if(!empty($request->to_vendors)){
            $data['to_vendors'] = '1';
        }
        SentNotification::create($data);
       return redirect()->route('notifications.index')->with('success','Notification Sent SuccessFully');
    }

    function sentAdminNotification($id){
        Helper::sentAdminNotification($id);
        return redirect()->back()->with('success','Message Sent Successfully');
    }
}
