<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Common;
use App\Models\Report;
use App\Http\Controllers\CommonController;

class ReportController extends Controller
{
    public function __construct(){

    }

    public function user_activity_report_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.report.user_report.user_activity_report',compact('menu_data'));
    }

    public function user_activity_report(Request $request){

        $validator = Validator::make($request->all(), [
            'from_date' => 'required|string|max:10',
            'to_date' => 'required|string|max:10'
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

        $report_data = Report::user_activity_report($request);
        
        if(empty($report_data)){

            $notification = array(
                'message'=> "User Activity Report Data Not Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }
        else {
            
            $notification = array(
                'message'=> "User Activity Report Generated Successfully",
                'alert_type'=>'success',
                'report_data'=>$report_data,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function user_log_report_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.report.user_report.user_log_report',compact('menu_data'));
    }

    public function user_log_report(Request $request){

        $validator = Validator::make($request->all(), [
            'from_date' => 'required|string|max:10',
            'to_date' => 'required|string|max:10'
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

        $report_data = Report::user_log_report($request);
        
        if(empty($report_data)){

            $notification = array(
                'message'=> "User Log Report Date Not Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }
        else {
            
            $notification = array(
                'message'=> "User Log Report Generated Successfully",
                'alert_type'=>'success',
                'report_data'=>$report_data,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function user_list_report_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.report.user_report.user_list_report',compact('menu_data'));
    }

    public function user_list_report(Request $request){

        $report_data = Report::user_list_report($request);
        
        if(empty($report_data)){

            $notification = array(
                'message'=> "User List Report Data Not Found",
                'alert_type'=>'warning',
                'csrf_token' => csrf_token()
            );
        }
        else {
            
            $notification = array(
                'message'=> "User List Report Generated Successfully",
                'alert_type'=>'success',
                'report_data'=>$report_data,
                'csrf_token' => csrf_token()
            );
        }

        CommonController::upsate_last_active_time();

        return response()->json($notification);
    }

    public function user_token_report_page(){

        $menu_data = Common::get_page_menu();

        return view('admin.report.token_report.user_token_report',compact('menu_data'));
    }

    public function user_token_report(Request $request){

        $validator = Validator::make($request->all(), [
            //'user_id' => 'required',
            'from_date' => 'required|string|max:10',
            'to_date' => 'required|string|max:10'
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

        $report_data = Report::user_token_report($request);
        
        if(empty($report_data)){

            $report_data['message'] = 'User Wise Token Report Data Not Found';
            $report_data['alert_type'] = 'warning';
        }
        else {
            
            $report_data['message'] = 'User Wise Token Report Generated Successfully';
            $report_data['alert_type'] = 'success';
        }

        CommonController::upsate_last_active_time();

        return response()->json($report_data);
    }
}