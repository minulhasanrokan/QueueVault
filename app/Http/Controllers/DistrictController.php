<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\District;
use App\Http\Controllers\CommonController;

class DistrictController extends Controller
{

    public function __construct(){

    }

    public function add_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.district.add',compact('menu_data'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'division_id' => 'required',
            'district_name' => 'required|string|max:150',
            'district_code' => 'required|string|max:4',
            'district_title' => 'required|string|max:150'
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

        $duplicate_status = Common::get_duplicate_value('district_name','districts', $request->district_name,0);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate District Name Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status = Common::get_duplicate_value('district_code','districts', $request->district_code,0);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate District Code Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $status = District::add_data($request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "District Added Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "District Does Not Added Successfully",
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

        return view('admin.district.view',compact('menu_data','grid_right'));
    }

    public function grid(Request $request){
        
        $grid_data = District::grid_data($request);

        echo json_encode($grid_data);
    }

    public function details_view($id=null){

        $single_data = District::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $data_status = Common::get_data_status_data();

        $menu_data = Common::get_page_menu();

        return view('admin.district.single_view',compact('single_data','data_status','menu_data'));
    }

    public function history($id){

        $history = District::history($id);

        if(empty($history)){
            return view('admin.404');
        }

        $data_status = Common::get_data_status_data();

        return view('admin.district.history',compact('data_status','history'));
    }

    public function edit_page($id){

        $single_data = District::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();

        return view('admin.district.edit',compact('menu_data','single_data'));
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'division_id' => 'required',
            'district_name' => 'required|string|max:150',
            'district_code' => 'required|string|max:4',
            'district_title' => 'required|string|max:150'
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

        $duplicate_status = Common::get_duplicate_value('district_name','districts', $request->district_name,$request->update_id);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate District Name Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status = Common::get_duplicate_value('district_code','districts', $request->district_code,$request->update_id);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate District Code Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $status = District::update_data($request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "District Updated Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "District Does Not Updated Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function delete_page($id){

        $single_data = District::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.district.delete',compact('menu_data','single_data','data_status'));
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

        $status = District::delete_data($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "District Deleted Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "District Does Not Deleted Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function sent_to_checker_page($id){

        $single_data = District::get_single_data_by_id($id);
        $history_data = District::get_single_history_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.district.sent_to_checker',compact('menu_data','single_data','data_status','history_data'));
    }

    public function sent_to_checker(Request $request, $id){

        $status = District::sent_to_checker($request, $id);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "District Sent To Checker Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "District Does Not Sent To Checker Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function verify_page($id){

        $single_data = District::get_single_data_by_id($id);
        $history_data = District::get_single_history_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();
        $actual_status = Common::get_actual_status_data();
        $process_status = Common::get_process_status_data();

        return view('admin.district.verify',compact('menu_data','single_data','data_status','history_data','actual_status','process_status'));
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

        $status = District::verify($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "District Verify Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "District Does Not Verify Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function deactive_page($id){

        $single_data = District::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.district.deactive',compact('menu_data','single_data','data_status'));
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

        $status = District::deactive($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "District Deactive Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "District Does Not Deactive Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function active_page($id){

        $single_data = District::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.district.active',compact('menu_data','single_data','data_status'));
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
        
        $status = District::active($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "District Active Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "District Does Not Active Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }
}