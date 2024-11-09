<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\Service;
use App\Http\Controllers\CommonController;

class ServiceController extends Controller
{
    public function __construct(){

    }

    public function add_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.service.add',compact('menu_data'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:150',
            'service_code' => 'required|string|max:4',
            'service_letter' => 'required|string|max:3',
            'service_start_number' => 'required|string|max:4',
            'service_title' => 'required|string|max:150'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $duplicate_status = Common::get_duplicate_value('service_name','services', $request->service_name,0);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Service Name Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status = Common::get_duplicate_value('service_code','services', $request->service_code,0);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Service Code Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $status = Service::add_data($request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Service Added Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Service Does Not Added Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function grid_page(){

        $menu_data = Common::get_page_menu();
        $grid_right = Common::get_page_menu_grid('add');

        return view('admin.service.view',compact('menu_data','grid_right'));
    }

    public function grid(Request $request){
        
        $grid_data = Service::grid_data($request);

        echo json_encode($grid_data);
    }

    public function details_view($id=null){

        $single_data = Service::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $data_status = Common::get_data_status_data();

        $menu_data = Common::get_page_menu();

        return view('admin.service.single_view',compact('single_data','data_status','menu_data'));
    }

    public function history($id){

        $history = Service::history($id);

        if(empty($history)){
            return view('admin.404');
        }

        $data_status = Common::get_data_status_data();

        return view('admin.service.history',compact('data_status','history'));
    }

    public function edit_page($id){

        $single_data = Service::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();

        return view('admin.service.edit',compact('menu_data','single_data'));
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string|max:150',
            'service_code' => 'required|string|max:4',
            'service_letter' => 'required|string|max:3',
            'service_start_number' => 'required|string|max:4',
            'service_title' => 'required|string|max:150'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $duplicate_status = Common::get_duplicate_value('service_name','services', $request->service_name,$request->update_id);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Service Name Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status = Common::get_duplicate_value('service_code','services', $request->service_code,$request->update_id);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Service Code Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $status = Service::update_data($request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Service Updated Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Service Does Not Updated Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function delete_page($id){

        $single_data = Service::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.service.delete',compact('menu_data','single_data','data_status'));
    }

    public function delete($id, Request $request){

        $validator = Validator::make($request->all(), [
            'delete_reason' => 'required|string|max:250'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $status = Service::delete_data($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Service Deleted Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Service Does Not Deleted Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function sent_to_checker_page($id){

        $single_data = Service::get_single_data_by_id($id);
        $history_data = Service::get_single_history_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.service.sent_to_checker',compact('menu_data','single_data','data_status','history_data'));
    }

    public function sent_to_checker(Request $request, $id){

        $status = Service::sent_to_checker($request, $id);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Service Sent To Checker Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Service Does Not Sent To Checker Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function verify_page($id){

        $single_data = Service::get_single_data_by_id($id);
        $history_data = Service::get_single_history_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();
        $actual_status = Common::get_actual_status_data();
        $process_status = Common::get_process_status_data();

        return view('admin.service.verify',compact('menu_data','single_data','data_status','history_data','actual_status','process_status'));
    }

    public function verify($id, Request $request){

        if($request->verify_type==0){
            
            $validator = Validator::make($request->all(), [
                'return_reason' => 'required|string|max:250',
                'verify_type' => 'required'
            ]);

            if ($validator->fails()) {

                $errors = $validator->errors();
                $errorArray = [];

                foreach ($errors->messages() as $field => $messages) {
                    $errorArray[$field] = $messages[0];
                }

                return response()->json([
                    'errors' => $errorArray,
                    'success' => false,
                    'csrf_token' => csrf_token(),
                ]);
            }
        }

        $status = Service::verify($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Service Verify Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Service Does Not Verify Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function deactive_page($id){

        $single_data = Service::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.service.deactive',compact('menu_data','single_data','data_status'));
    }

    public function deactive($id, Request $request){

        $validator = Validator::make($request->all(), [
            'deactive_reason' => 'required|string|max:250'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $status = Service::deactive($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Service Deactive Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Service Does Not Deactive Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function active_page($id){

        $single_data = Service::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.service.active',compact('menu_data','single_data','data_status'));
    }

    public function active($id, Request $request){

        $validator = Validator::make($request->all(), [
            'active_reason' => 'required|string|max:250'
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $errorArray = [];

            foreach ($errors->messages() as $field => $messages) {
                $errorArray[$field] = $messages[0];
            }

            return response()->json([
                'errors' => $errorArray,
                'success' => false,
                'csrf_token' => csrf_token(),
            ]);
        }

        $status = Service::active($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Service Active Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Service Does Not Active Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }
}