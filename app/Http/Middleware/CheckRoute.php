<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Common;
use App\Http\Controllers\CommonController;
use DB;

class CheckRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function __construct(){

    }

    public function handle(Request $request, Closure $next): Response
    {

        $session_data = CommonController::get_session_data();

        $status = $this->check_session($session_data);

        if($status==0){

            if ($request->ajax()){

                echo 'Session Expire';

                die;
            }
            else
            {
                return redirect('/login');
            }
        }
        else if($status==2){

            if ($request->ajax()){

                echo 'lock';

                die;
            }
            else
            {
                return redirect('/lock');
            }
        }
        else{

            $system_data = Common::get_system_setting_data();

            if($session_data['password_change_status']==0 || date('Y-m-d')>$session_data['password_exp_date']){

                if ($request->ajax()){

                    echo 'Right Not Found';

                    die;
                }
                else
                {
                    return redirect('/change-password');
                }
            }
            else if($session_data['super_admin_status']!=1){

                $route_name = request()->route()->getName();

                //Common::delete_cahce_data('menu_right:*');

                $right_data = Common::get_cache_value_by_key('menu_right:route_check'.$session_data['id'].$route_name);

                if($right_data['cache_status']==1){

                    if(empty($right_data['cache_data'])){

                        if ($request->ajax()){

                            echo 'Right Not Found';

                            die;
                        }
                        else{

                            return redirect('/logout');
                        }
                    }
                }
                else{

                    $right_data = DB::table('right_details as a')
                        ->join('user_rights as b', 'b.r_id', '=', 'a.id')
                        ->select('a.id  as r_id')
                        ->where('a.status',1)
                        ->where('b.user_id',$session_data['id'])
                        ->where('a.r_route_name',$route_name)
                        ->where('a.delete_status',0)
                        ->get()->toArray();

                    Common::put_cahce_value('menu_right:route_check'.$session_data['id'].$route_name,$right_data);

                    if(empty($right_data)){

                        if ($request->ajax()){

                            echo 'Right Not Found';

                            die;
                        }
                        else{

                            return redirect('/logout');
                        }
                    }
                }
            }
        }

        if($request->isMethod('post')==1){
            
            $request->session()->regenerateToken();
        }

        if ($request->ajax()) {

            return $next($request);
        }
        else{

            $system_data = Common::get_system_setting_data();

            return response()->view('admin.master_2', array_merge(
                compact('system_data'),
                ['content' => $next($request)->content()]
            ));
        }
    }

    protected function check_session($session_data){

        if(!empty($session_data)){

            $last_active_time = $session_data['last_active_time'];
            $user_session_time = $session_data['user_session_time'];
            $lock_status = $session_data['lock_status'];

            if($lock_status==1){

                return 2;
            }
            else{

                if((time() - $last_active_time) > $user_session_time){

                    CommonController::forgot_session_data();

                    return 0;
                }
                else{

                    Common::update_user_log_time(0,0);

                    $last_active_time_Key = CommonController::get_session_variable_name().'.last_active_time';

                    session()->put($last_active_time_Key, time());

                    return 1;
                }
            }
        }
        else{

            CommonController::forgot_session_data();
            
            return 0;
        }
    }
}
