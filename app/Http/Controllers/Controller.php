<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Product;
use App\Models\Category;
use App\Models\DemoProduct;
use App\Models\Role;
use App\Models\User;
use App\Models\Order;
use App\Models\Slab;
use App\Models\SlabLink;
use App\Models\StoreLink;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard(){
        $total_category = Category::where('status','Active')->where('is_admin','1')->count();
        $total_product = Product::where('is_admin','1')->count();
        $total_vendors = User::where('role_id',Role::$vendor)->where('is_register','1')->count();
        $total_customers = User::where('role_id',Role::$customer)->where('is_register','1')->count();
        $total_orders = Order::count();
        return view('admin.dashboard',compact('total_category','total_product','total_vendors','total_customers','total_orders'))->with('success','ALLSSVHJBHJSBJVJ');
    }


    public function changes_slab_status(Request $request){
        $payment_status = Slab::where('id',$request->id)->first();
        if($payment_status->status == Slab::$active){
            $payment_status->update(['status' => Slab::$inactive]);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => Slab::$active]);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_category_status(Request $request){
        $payment_status = Category::where('id',$request->id)->first();
        if($payment_status->status == Category::$active){
            $payment_status->update(['status' => Category::$inactive]);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => Category::$active]);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_product_status(Request $request){
        $payment_status = Product::where('id',$request->id)->first();
        if($payment_status->status == Product::$active){
            $payment_status->update(['status' => Product::$inactive]);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => Product::$active]);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_demo_product_status(Request $request){
        $payment_status = DemoProduct::where('id',$request->id)->first();
        if($payment_status->status == DemoProduct::$active){
            $payment_status->update(['status' => DemoProduct::$inactive]);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => DemoProduct::$active]);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_store_link_status(Request $request){
        $payment_status = StoreLink::where('id',$request->id)->first();
        if($payment_status->status == StoreLink::$active){
            $payment_status->update(['status' => StoreLink::$inactive]);
            Helper::sentNotificationForActiveInactiveUser($payment_status->user_id,$payment_status->vendor_id,StoreLink::$inactive);
            return json_encode(['0' ,'Status Inactive Successfully']);
        }else{
            $payment_status->update(['status' => StoreLink::$active]);
            Helper::sentNotificationForActiveInactiveUser($payment_status->user_id,$payment_status->vendor_id,StoreLink::$active);
            return json_encode(['1' ,'Status Active Successfully']);
        }
    }

    public function changes_notification_status(Request $request){
        $payment_status = User::where('id',$request->id)->first();
        if($payment_status->is_notify == '1'){
            $payment_status->update(['is_notify' => '0']);
            return json_encode(['0' ,'Is notify Inactive Successfully']);
        }else{
            $payment_status->update(['is_notify' => '1']);
            return json_encode(['1' ,'Is notify Active Successfully']);
        }
    }

    public function createthumbnil(Request $request){
            ini_set('max_execution_time', 50000);
        $sourceFolder = public_path('images/products/');
        $targetFolder = public_path('images/products/thumb1/');
        $targetFolder2 = public_path('images/products/thumb2/');

        // Ensure the target folder exists, create if necessary
        if (!File::exists($targetFolder)) {
            File::makeDirectory($targetFolder, 0777, true);
        }

        if (!File::exists($targetFolder2)) {
            File::makeDirectory($targetFolder2, 0777, true);
        }

        // Get all image files from the source folder
        $imageFiles = File::glob($sourceFolder . '*.{jpg,jpeg,png,gif,bmp,webp,JPEG,PNG,JPG,GIF}', GLOB_BRACE);

        $imgessss = [];
        foreach ($imageFiles as $imageFile) {
            // Check if the file is a valid image
            $imageInfo = getimagesize($imageFile);

            if ($imageInfo === false) {
                // Skip this file if it's not a valid image

                $imgessss[] = $imageInfo;
                continue;
            }

            // Get the original file name
            $originalFileName = pathinfo($imageFile, PATHINFO_BASENAME);

            // Generate a unique name for the thumbnail
            $thumbnailName = $originalFileName;

            // Open the image using Intervention Image
            $img = Image::make($imageFile);

            // Resize and save the thumbnail to targetFolder
            $img->fit(300, 300)->save($targetFolder . $thumbnailName);

            // Resize and save another thumbnail to targetFolder2
            $img->fit(400, 400)->save($targetFolder2 . $thumbnailName);
        }

        return 'Thumbnails created and moved to the target folder.';
    }

    function test_pagination(Request $request){
        $page = $request->input('page',1);
        $categories = Category::paginate(10, ['*'], 'page',$page);

        // echo "<pre>";
        // echo json_encode($categories);die;
        return view('admin.pagination.index',compact('categories'));
    }

    // public function createthumbnil(Request $request){
    //     ini_set('max_execution_time', 300);
    //     $sourceFolder = public_path('images/products/');
    //     // $targetFolder = public_path('images/products/thumb1/');
    //     // $targetFolder2 = public_path('images/products/thumb2/');

    //     // Ensure the target folder exists, create if necessary
    //     // if (!File::exists($targetFolder)) {
    //         // File::makeDirectory($targetFolder, 0777, true);
    //     // }

    //     // if (!File::exists($targetFolder2)) {
    //         // File::makeDirectory($targetFolder2, 0777, true);
    //     // }

    //     // Get all image files from the source folder
    //     $imageFiles = File::glob($sourceFolder . '*.{jpg,jpeg,png,gif,bmp,webp,JPEG,PNG,JPG,GIF}', GLOB_BRACE);

    //     $imgessss = [];
    //     foreach ($imageFiles as $imageFile) {
    //         // Check if the file is a valid image
    //         $imageInfo = getimagesize($imageFile);

    //         if ($imageInfo === false) {
    //             // Skip this file if it's not a valid image

    //             $imgessss[] = $imageInfo;
    //             continue;
    //         }

    //         // Get the original file name
    //         $originalFileName = pathinfo($imageFile, PATHINFO_BASENAME);
    //         $pathInfo = pathinfo($originalFileName);
    //         // Remove the extension
    //         $filenameWithoutExtension = $pathInfo['filename'];
    //         $get_product = Product::where('name', 'LIKE', '%' . $filenameWithoutExtension . '%')->first();
    //         if(!empty($get_product)){
    //             $image = [];
    //             $image[] = $originalFileName;
    //             $get_product->update(['images' => json_encode($image)]);
    //         }
    //     }

    //     return 'Thumbnails created and moved to the target folder.';
    // }


    function upload_image(Request $request){
        $data = array();

        $validator = Validator::make($request->all(), [
             'file' => 'required|mimes:png,jpg,jpeg,pdf|max:2048'
        ]);

        if ($validator->fails()) {

            $data['success'] = 0;
            $data['error'] = $validator->errors()->first('file');// Error response

        }else{
             if($request->file('file')) {

                 $file = $request->file('file');
                 $filename = time().'_'.$file->getClientOriginalName();

                  // File upload location
                  $location = 'files';

                  // Upload file
                  $file->move($location,$filename);

                  // Response
                  $data['success'] = 1;
                  $data['message'] = 'Uploaded Successfully!';
                  $data['file_name'] = $filename;

             }else{
                   // Response
                   $data['success'] = 0;
                   $data['message'] = 'File not uploaded.'; 
             }
        }

         return response()->json($data);
    }

}
