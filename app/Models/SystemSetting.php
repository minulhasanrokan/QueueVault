<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class SystemSetting extends Model
{
    protected $guarded = [];
    
    use HasFactory;

    public static function update_system_info($request){

        $data = SystemSetting::where('delete_status',0)
            ->select('id')
            ->where('status',1)
            ->first();

        $session_data = CommonController::get_session_data();

        $activity = '';
        $actual_status = 1;

        Common::db_transaction_on();

        if($data==''){

            $data = new SystemSetting;

            $data->add_by = $session_data['id'];

            $activity = 'Add System Information';
        }
        else{

            $data->edit_by = $session_data['id'];
            $data->edit_status = 1;
            $data->updated_at = now();

            $activity = 'Update System Information';

            $actual_status = 3;
        }

        $data->system_name = $request->system_name;
        $data->system_title = $request->system_title;
        $data->system_slogan = $request->system_slogan;
        $data->system_email = $request->system_email;
        $data->system_mobile = $request->system_mobile;
        $data->system_address = $request->system_address;
        $data->system_details = $request->system_details;
        $data->system_copy_right = $request->system_copy_right;
        $data->system_version = $request->system_version;

        if ($request->hasFile('system_logo')) {

            $file = $request->file('system_logo');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->system_name);

            $fileName = $title_name.'_logo_'.time().'.'.$extension;

            $file->move('uploads/system/',$fileName);

            if($data->system_logo!='')
            {
                $deletePhoto = "uploads/system/".$data->system_logo;
                
                if(file_exists($deletePhoto)){

                    //unlink($deletePhoto);
                }
            }

            $data->system_logo = $fileName;
        }

        if ($request->hasFile('system_icon')) {

            $file = $request->file('system_icon');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->system_name);

            $fileName = $title_name.'_icon_'.time().'.'.$extension;

            $file->move('uploads/system/',$fileName);
            
            if($data->system_icon!='')
            {
                $deletePhoto = "uploads/system/".$data->system_icon;
                
                if(file_exists($deletePhoto)){

                    //unlink($deletePhoto);
                }
            }

            $data->system_icon = $fileName;
        }

        if ($request->hasFile('system_bg_image')) {

            $file = $request->file('system_bg_image');

            $extension = $file->getClientOriginalExtension();

            $title_name = str_replace(' ','_',$request->system_name);

            $fileName = $title_name.'_bg_image_'.time().'.'.$extension;

            $file->move('uploads/system/',$fileName);
            
            if($data->system_bg_image!='')
            {
                $deletePhoto = "uploads/system/".$data->system_bg_image;
                
                if(file_exists($deletePhoto)){

                    //unlink($deletePhoto);
                }
            }

            $data->system_bg_image = $fileName;
        }

        $data->save();

        if($data==true){

            $status = Common::add_user_activity_history('system_settings',$data->id,$activity,'',$actual_status,0);

            if($status==1){

                Common::db_transaction_commit();

                Common::delete_cahce_data('system_settings');

                return 1;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function update_user_rules($request){

        $data = SystemSetting::where('delete_status',0)
            ->select('id')
            ->where('status',1)
            ->first();

        $session_data = CommonController::get_session_data();

        $activity = '';
        $actual_status = 1;

        Common::db_transaction_on();

        if($data==''){

            $data = new SystemSetting;

            $data->add_by = $session_data['id'];

            $activity = 'Add User Id & Password Rules';
        }
        else{

            $data->edit_by = $session_data['id'];
            $data->edit_status = 1;
            $data->updated_at = now();

            $activity = 'Update User Id & Password Rules';

            $actual_status = 3;
        }

        $data->password_validity_days = $request->password_validity_days;
        $data->password_min_length = $request->password_min_length;
        $data->password_max_length = $request->password_max_length;
        $data->default_password_type = $request->default_password_type;
        $data->userid_min_length = $request->userid_min_length;
        $data->userid_max_length = $request->userid_max_length;

        $data->save();

        if($data==true){

            $status = Common::add_user_activity_history('system_settings',$data->id,$activity,'',$actual_status,0);

            if($status==1){

                Common::db_transaction_commit();

                Common::delete_cahce_data('system_settings');

                return 1;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }

    public static function update_session_time($request){

        $data = SystemSetting::where('delete_status',0)
            ->select('id')
            ->where('status',1)
            ->first();

        $session_data = CommonController::get_session_data();

        $activity = '';
        $actual_status = 1;

        Common::db_transaction_on();

        if($data==''){

            $data = new SystemSetting;

            $data->add_by = $session_data['id'];

            $activity = 'Add Session Idle Time';
        }
        else{

            $data->edit_by = $session_data['id'];
            $data->edit_status = 1;
            $data->updated_at = now();

            $activity = 'Update Session Idle Time';

            $actual_status = 3;
        }

        $data->session_time = $request->session_time;
        $data->session_check_time = $request->session_check_time;

        $data->save();

        if($data==true){

            $update_data = array(
                'user_session_time' => $request->session_time*60,
                'session_check_time' => $request->session_check_time*1000,
            );

            $updated = DB::table('users')
                ->update($update_data);

            $status = Common::add_user_activity_history('system_settings',$data->id,$activity,'',$actual_status,0);

            if($status==1){

                Common::db_transaction_commit();

                Common::delete_cahce_data('system_settings');

                return 1;
            }
            else{

                Common::db_transaction_rollBack();

                return 0;
            }
        }
        else{

            Common::db_transaction_rollBack();

            return 0;
        }
    }
}
