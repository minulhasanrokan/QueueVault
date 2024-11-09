<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Common;
use App\Http\Controllers\CommonController;

class LockCheck
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

        if($status!=1){

            return redirect('/logout');
        }

        if($request->isMethod('post')==1){
            
            $request->session()->regenerateToken();
        }

        return $next($request);
    }

    protected function check_session($session_data){

        if(!empty($session_data)){

            $last_active_time = $session_data['last_active_time'];
            $user_session_time = $session_data['user_session_time'];
            $lock_status = $session_data['lock_status'];

            if($lock_status==1){

                $total_log_data= Common::get_user_log_histories_data();

                if ($total_log_data>0) {

                $last_active_time_Key = CommonController::get_session_variable_name().'.last_active_time';
                session()->put($last_active_time_Key, time());

                Common::update_user_log_time(0,0);

                    return 1;
                }
                else{

                    return 0;
                }
            }
            else{

                if((time() - $last_active_time) > $user_session_time){

                    CommonController::forgot_session_data();

                    return 0;
                }
                else{

                    $total_log_data= Common::get_user_log_histories_data();

                    if ($total_log_data>0) {

                        Common::update_user_log_time(0,0);

                        $last_active_time_Key = CommonController::get_session_variable_name().'.last_active_time';
                        session()->put($last_active_time_Key, time());

                        $lock_status = CommonController::get_session_variable_name().'.lock_status';
                        session()->put($lock_status, 1);

                        return 1;
                    }
                    else{

                        return 0;
                    }
                }
            }
        }
        else{

            CommonController::forgot_session_data();

            return 0;
        }
    }
}