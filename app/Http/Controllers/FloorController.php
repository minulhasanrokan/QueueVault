<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\Floor;
use App\Http\Controllers\CommonController;

class FloorController extends Controller
{
    public function __construct(){

    }

    public function add_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.floor.add',compact('menu_data'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
            'assign_service' => 'required',
            'floor_name' => 'required|string|max:150',
            'floor_code' => 'required|string|max:4',
            'floor_title' => 'required|string|max:150'
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

        $duplicate_status = Common::get_duplicate_value('floor_name','floors', $request->floor_name,0);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Floor Name Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status = Common::get_duplicate_value('floor_code','floors', $request->floor_code,0);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Floor Code Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $status = Floor::add_data($request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Floor Added Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Floor Does Not Added Successfully",
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

        return view('admin.floor.view',compact('menu_data','grid_right'));
    }

    public function grid(Request $request){
        
        $grid_data = Floor::grid_data($request);

        echo json_encode($grid_data);
    }

    public function details_view($id=null){

        $single_data = Floor::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $data_status = Common::get_data_status_data();

        $menu_data = Common::get_page_menu();

        return view('admin.floor.single_view',compact('single_data','data_status','menu_data'));
    }

    public function history($id){

        $history = Floor::history($id);

        if(empty($history)){
            return view('admin.404');
        }

        $data_status = Common::get_data_status_data();

        return view('admin.floor.history',compact('data_status','history'));
    }

    public function edit_page($id){

        $single_data = Floor::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();

        return view('admin.floor.edit',compact('menu_data','single_data'));
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
            'assign_service' => 'required',
            'floor_name' => 'required|string|max:150',
            'floor_code' => 'required|string|max:4',
            'floor_title' => 'required|string|max:150'
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

        $duplicate_status = Common::get_duplicate_value('floor_name','floors', $request->floor_name,$request->update_id);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Floor Name Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status = Common::get_duplicate_value('floor_code','floors', $request->floor_code,$request->update_id);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Floor Code Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $status = Floor::update_data($request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Floor Updated Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Floor Does Not Updated Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function delete_page($id){

        $single_data = Floor::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.floor.delete',compact('menu_data','single_data','data_status'));
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

        $status = Floor::delete_data($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Floor Deleted Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Floor Does Not Deleted Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function sent_to_checker_page($id){

        $single_data = Floor::get_single_data_by_id($id);
        $history_data = Floor::get_single_history_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.floor.sent_to_checker',compact('menu_data','single_data','data_status','history_data'));
    }

    public function sent_to_checker(Request $request, $id){

        $status = Floor::sent_to_checker($request, $id);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Floor Sent To Checker Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Floor Does Not Sent To Checker Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function verify_page($id){

        $single_data = Floor::get_single_data_by_id($id);
        $history_data = Floor::get_single_history_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();
        $actual_status = Common::get_actual_status_data();
        $process_status = Common::get_process_status_data();

        return view('admin.floor.verify',compact('menu_data','single_data','data_status','history_data','actual_status','process_status'));
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

        $status = Floor::verify($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Floor Verify Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Floor Does Not Verify Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function deactive_page($id){

        $single_data = Floor::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.floor.deactive',compact('menu_data','single_data','data_status'));
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

        $status = Floor::deactive($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Floor Deactive Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Floor Does Not Deactive Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function active_page($id){

        $single_data = Floor::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.floor.active',compact('menu_data','single_data','data_status'));
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

        $status = Floor::active($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "Floor Active Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "Floor Does Not Active Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }
}