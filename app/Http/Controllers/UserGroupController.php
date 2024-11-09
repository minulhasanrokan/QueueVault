<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\UserGroup;
use App\Http\Controllers\CommonController;


class UserGroupController extends Controller
{

    public function __construct(){ 

    }

    public function add_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.user_group.add',compact('menu_data'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:150',
            'group_code' => 'required|string|max:4',
            'group_title' => 'required|string|max:150',
            'group_logo' => $request->input('hidden_group_logo') === '' ? 'required' : '',
            'group_icon' => $request->input('hidden_group_icon') === '' ? 'required' : ''
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

        $duplicate_status = Common::get_duplicate_value('group_name','user_groups', $request->group_name,0);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Group Name Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status = Common::get_duplicate_value('group_code','user_groups', $request->group_code,0);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Group Code Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $status = UserGroup::add_data($request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "User Group Added Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Group Does Not Added Successfully",
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

        return view('admin.user_group.view',compact('menu_data','grid_right'));
    }

    public function grid(Request $request){
        
        $grid_data = UserGroup::grid_data($request);

        echo json_encode($grid_data);
    }

    public function details_view($id=null){

        $single_data = UserGroup::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $data_status = Common::get_data_status_data();

        $menu_data = Common::get_page_menu();

        return view('admin.user_group.single_view',compact('single_data','data_status','menu_data'));
    }

    public function edit_page($id){

        $single_data = UserGroup::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();

        return view('admin.user_group.edit',compact('menu_data','single_data'));
    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'group_name' => 'required|string|max:150',
            'group_code' => 'required|string|max:4',
            'group_title' => 'required|string|max:150',
            'group_logo' => $request->input('hidden_group_logo') === '' ? 'required' : '',
            'group_icon' => $request->input('hidden_group_icon') === '' ? 'required' : ''
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

        $duplicate_status = Common::get_duplicate_value('group_name','user_groups', $request->group_name,$request->update_id);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Group Name Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $duplicate_status = Common::get_duplicate_value('group_code','user_groups', $request->group_code,$request->update_id);

        if($duplicate_status>0){

            $notification = array(
                'message'=> "Duplicate Group Code Found",
                'alert_type'=>'warning',
                'id'=>$duplicate_status,
                'csrf_token' => csrf_token()
            );

            return response()->json($notification);
        }

        $status = UserGroup::update_data($request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "User Group Updated Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Group Does Not Updated Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function sent_to_checker_page($id){

        $single_data = UserGroup::get_single_data_by_id($id);
        $history_data = UserGroup::get_single_history_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.user_group.sent_to_checker',compact('menu_data','single_data','data_status','history_data'));
    }

    public function sent_to_checker(Request $request, $id){

        $status = UserGroup::sent_to_checker($request, $id);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "User Group Sent To Checker Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Group Does Not Sent To Checker Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function verify_page($id){

        $single_data = UserGroup::get_single_data_by_id($id);
        $history_data = UserGroup::get_single_history_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();
        $actual_status = Common::get_actual_status_data();
        $process_status = Common::get_process_status_data();

        return view('admin.user_group.verify',compact('menu_data','single_data','data_status','history_data','actual_status','process_status'));
    }

    public function delete_page($id){

        $single_data = UserGroup::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.user_group.delete',compact('menu_data','single_data','data_status'));
    }

    public function deactive_page($id){

        $single_data = UserGroup::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.user_group.deactive',compact('menu_data','single_data','data_status'));
    }

    public function active_page($id){

        $single_data = UserGroup::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $data_status = Common::get_data_status_data();

        return view('admin.user_group.active',compact('menu_data','single_data','data_status'));
    }

    public function right_page($id){

        $single_data = UserGroup::get_single_data_by_id($id);

        if(empty($single_data)){

            return view('admin.404');
        }

        $menu_data = Common::get_page_menu();
        $all_right = Common::get_all_right();

        $group_right = Common::get_group_right($single_data->id);

        return view('admin.user_group.right',compact('menu_data','single_data','all_right','group_right'));
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

        $status = UserGroup::verify($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "User Group Verify Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Group Does Not Verify Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
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

        $status = UserGroup::delete_user_group($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "User Group Deleted Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Group Does Not Deleted Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
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

        $status = UserGroup::deactive_user_group($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "User Group Deactive Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Group Does Not Deactive Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
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

        $status = UserGroup::active_user_group($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "User Group Active Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Group Does Not Active Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function right($id, Request $request){

        $status = UserGroup::set_right_user_group($id,$request);

        $notification = array();

        if($status!=0){

            $notification = array(
                'message'=> "User Group Right Updated Successfully",
                'alert_type'=>'success',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }
        else{

            $notification = array(
                'message'=> "User Group Right Does Not Updated Successfully",
                'alert_type'=>'warning',
                'id'=>$status,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function user_group_right_details($id){

        $all_right = Common::get_all_right();
        $group_right = Common::get_group_right($id);

        return view('admin.user_group.right_details',compact('all_right','group_right'));
    }

    public function user_group_chenged_right_details($id){

        $all_right = Common::get_all_right();
        $group_right = UserGroup::get_single_right_chnaged_history_data_by_id($id);

        return view('admin.user_group.right_details',compact('all_right','group_right'));
    }

    public function user_group_history($id){

        $history = UserGroup::user_group_history($id);

        if(empty($history)){
            return view('admin.404');
        }

        $data_status = Common::get_data_status_data();

        return view('admin.user_group.history',compact('data_status','history'));
    }
}