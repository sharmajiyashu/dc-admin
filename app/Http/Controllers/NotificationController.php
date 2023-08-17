<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Notification;
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

    public function edit($id){
        $notification = SentNotification::where('id',$id)->first();
        return view('admin.notifications.edit',compact('notification'));
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

        if($request->hasFile('image')) {
            $image_name = time().rand(1,100).'-'.$request->image->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $request->image->move(public_path('images/notifications'), $image_name);
            $data['image'] = $image_name;
        }
        
        if(!empty($request->to_customers)){
            $data['to_customers'] = '1';
        }
        if(!empty($request->to_vendors)){
            $data['to_vendors'] = '1';
        }
        $sent = SentNotification::create($data);
        $this->sentAdminNotification($sent->id);
       return redirect()->route('notifications.index')->with('success','Notification Sent SuccessFully');
    }

    function sentAdminNotification($id){
        Helper::sentAdminNotification($id);
        return redirect()->back()->with('success','Message Sent Successfully');
    }

    function delete($id){
        SentNotification::where('id',$id)->delete();
        return redirect()->route('notifications.index')->with('success','Notification delete successfully');
    }

    function update($id , Request $request){
        $validatedData = $request->validate([
            'title' => 'required|string',
            'to_vendors' => 'required_without:to_customers',
            'to_customers' => 'required_without:to_vendors',
        ]);
        $data = [
            'title' => $request->title,
            'body' => $request->body,
        ];

        if($request->hasFile('image')) {
            $image_name = time().rand(1,100).'-'.$request->image->getClientOriginalName();
            $image_name = preg_replace('/\s+/', '', $image_name);
            $request->image->move(public_path('images/notifications'), $image_name);
            $data['image'] = $image_name;
        }    

        if(!empty($request->to_customers)){
            if($request->to_customers == 'customer'){
                $data['to_customers'] = '1';
            }else{
                $data['to_customers'] = '0';
            }
        }else{
            $data['to_customers'] = '0';
        }

        if(!empty($request->to_vendors)){
            if($request->to_vendors == 'vendor'){
                $data['to_vendors'] = '1';
            }else{
                $data['to_vendors'] = '0';
            }
        }else{
            $data['to_vendors'] = '0';
        }

        SentNotification::where('id',$id)->update($data);
        return redirect()->route('notifications.index')->with('success','Notification update successfully');
    }

    function delete_notifications($id){
        Notification::where('id',$id)->delete();
        return redirect()->back()->with('success','Notification delete successfully');
    }
}
