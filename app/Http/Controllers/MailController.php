<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DynamicMail;
use App\Models\Common;

class MailController extends Controller
{
    public function __construct(){

    }

    public static function sent_password_reset_email($email,$encrypt_data,$user_name,$token,$table_id){

        $verificationLink = config('app.url')."/reset-password/".$encrypt_data;

        $body = '<p>Hello '.$user_name.',</p>
            <p>Please click the following link to Reset your Password:</p>
            <a href="'.$verificationLink.'">Reset Password</a>
            <br>
            <p>Verify Token: '.$token.'</p>
            <p>If you didn\'t request this, you can ignore this email.</p>';

        try {

            $sent_status = Mail::to($email)->send(new DynamicMail($email,$body,'Reset Password',''));

            Common::insert_mail_history($email,'','users',$table_id,'Reset Password',$body,$sent_status=1,$try_status=1);

            return true;

        } catch (\Exception $e){

            Common::insert_mail_history($email,'','users',$table_id,'Reset Password',$body,$sent_status=0,$try_status=1);

            Log::error('Failed to send email: ' . $e);

            return false;
        }
    }

    public static function sent_verify_email($email,$encrypt_data,$user_name,$table_id){

        $verificationLink = config('app.url')."/verify-email/".$encrypt_data;

        $body = '<p>Hello '.$user_name.',</p>
            <p>Please click the following link to verify your email:</p>
            <a href="'.$verificationLink.'">Verify Email</a>
            <br>
            <p>If you didn\'t request this verification, you can ignore this email.</p>';

        try {

            $sent_status = Mail::to($email)->send(new DynamicMail($email,$body,'Verify Email',''));

            Common::insert_mail_history($email,'','users',$table_id,'Verify Email',$body,$sent_status=1,$try_status=1);

            return true;

        } catch (\Exception $e){

            Common::insert_mail_history($email,'','users',$table_id,'Verify Email',$body,$sent_status=0,$try_status=1);

            Log::error('Failed to send email: ' . $e);

            return false;
        }
    }

    public static function sent_verify_email_with_password($email,$password,$encrypt_data,$user_name,$table_id){

        $verificationLink = config('app.url')."/verify-email/".$encrypt_data;

        $body = '<p>Hello '.$user_name.',</p>
            <p>Please click the following link to verify your email:</p>
            <a href="'.$verificationLink.'">New User Verify Email</a>
            <br>
            <p>After Verify Your Account to Login In Your Account Use This Password: '.$password.'</p><p>After Login Please Change Your Password</p>
            <br>
            <p>If you didn\'t request this verification, you can ignore this email.</p>';

        try {

            $sent_status = Mail::to($email)->send(new DynamicMail($email,$body,'New User Verify Email',''));

            Common::insert_mail_history($email,'','users',$table_id,'New User Verify Email',$body,$sent_status=1,$try_status=1);

            return true;

        } catch (\Exception $e){

            Common::insert_mail_history($email,'','users',$table_id,'New User Verify Email',$body,$sent_status=0,$try_status=1);

            Log::error('Failed to send email: ' . $e);

            return false;
        }
    }

    public static function reset_password_mail($email,$password,$user_name,$table_id){

        $body = '<p>Hello '.$user_name.',</p>
            <p>Your Password Was Reseted.</p>
            <p>To Login In Your Account Use This Password: '.$password.'</p><p>After Login Please Change Your Password</p>
            <br>
            <p> Application Link :<a href="'.config('app.url').'">Login</a></p>
            <p>If you didn\'t request this Reset Password, you can ignore this email.</p>';

        try {

            $sent_status = Mail::to($email)->send(new DynamicMail($email,$body,'Reset Password',''));

            Common::insert_mail_history($email,'','users',$table_id,'Reset Password',$body,$sent_status=1,$try_status=1);

            return true;

        } catch (\Exception $e){

            Common::insert_mail_history($email,'','users',$table_id,'Reset Password',$body,$sent_status=0,$try_status=1);

            Log::error('Failed to send email: ' . $e);

            return false;
        }
    }
}