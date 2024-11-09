<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SystemInfoController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\UapzilaController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\TokenController;

Route::controller(CommonController::class)->group(function(){

    Route::get('/check-session','check_session')->name('check.session'); 
    Route::get('/update-session','update_session')->name('update.session');

    Route::post('/get-duplicate-value','get_duplicate_value')->name('get.duplicate.value');
    Route::post('/get-combain-duplicate-value','get_combain_duplicate_value')->name('get.combain.duplicate.value');

    Route::get('/get-dropdown/{table_name?}/{field_name?}/{value?}/{other_field?}/{other_value?}/{not_equal_field?}/{not_equal_value?}','get_dropdown')->name('get.dropdown');

    Route::get('/return-message-history/{id?}/{table_name?}','return_message_history')->name('return.message.history');
    Route::get('/activity-history/{id?}/{table_name?}','activity_history')->name('activity.history');

});

Route::controller(LoginController::class)->group(function(){

    Route::get('/login','login_page')->name('admin.login')->middleware(['logincheck']);
    Route::get('/','login_page')->name('admin.login')->middleware(['logincheck']);
    Route::post('/login-store','login_store')->name('admin.login.store');

    Route::get('/change-password','change_password_page')->name('change.password')->middleware(['ChangePassLoginChaeck']);
    Route::post('/change-password','change_password_store')->name('change.password')->middleware(['ChangePassLoginChaeck']);

    Route::get('/forgot-password','forgot_password_page')->name('forgot.password');
    Route::post('/forgot-password','forgot_password_store')->name('forgot.password');
    Route::get('/resend-forgot-password/{email}','resend_forgot_password')->name('forgot.resend.password');

    Route::get('/reset-password/{email}','reset_password_page')->name('reset.password');
    Route::post('/reset-password/{email}','reset_password_store')->name('reset.password');

    Route::get('/resend-verify-email/{token}','resend_verify_email')->name('resend.verify.email')->middleware(['logincheck']);
    Route::get('/verify-email/{token}','verify_email_page')->name('verify.email');
});

Route::controller(DashboardController::class)->group(function(){

    // get.....
    Route::get('/dashboard','dashboard')->name('admin.dashboard')->middleware(['DashboardCheck']);

    Route::get('/logout','logout')->name('logout');
    Route::get('/lock','lock')->name('lock')->middleware(['LockCheck']);
    Route::post('/lock','un_lock')->name('lock')->middleware(['LockCheck']);
});

Route::controller(SystemInfoController::class)->group(function(){

    Route::get('/system-config','system_config_add_page')->name('system.config.add')->middleware(['CheckRoute']);
    Route::post('/system-config','system_config_strore')->name('system.config.add')->middleware(['CheckRoute']);

    Route::get('/system-user-rules','user_rules_add_page')->name('system.config.user_rules')->middleware(['CheckRoute']);
    Route::post('/system-user-rules','user_rules_strore')->name('system.config.user_rules')->middleware(['CheckRoute']);

    Route::get('/system-session-time','session_time_page')->name('system.config.session_time')->middleware(['CheckRoute']);
    Route::post('/system-session-time','session_time_strore')->name('system.config.session_time')->middleware(['CheckRoute']);

});

Route::controller(UserGroupController::class)->group(function(){

    Route::get('/add-user-group','add_page')->name('user_management.user_group.add')->middleware(['CheckRoute']);
    Route::post('/add-user-group','store')->name('user_management.user_group.add')->middleware(['CheckRoute']);

    Route::get('/view-user-group','grid_page')->name('user_management.user_group.view')->middleware(['CheckRoute']);
    Route::post('/view-user-group','grid')->name('user_management.user_group.view')->middleware(['CheckRoute']);
    Route::get('/view-user-group/{id?}','details_view')->name('user_management.user_group.view')->middleware(['CheckRoute']);

    Route::get('/edit-user-group','grid_page')->name('user_management.user_group.edit')->middleware(['CheckRoute']);
    Route::post('/edit-user-group','grid')->name('user_management.user_group.edit')->middleware(['CheckRoute']);
    Route::get('/edit-user-group/{id?}','edit_page')->name('user_management.user_group.edit')->middleware(['CheckRoute']);
    Route::post('/edit-user-group/{id?}','update')->name('user_management.user_group.edit')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-user-group','grid_page')->name('user_management.user_group.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-user-group','grid')->name('user_management.user_group.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-user-group/{id?}','sent_to_checker_page')->name('user_management.user_group.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-user-group/{id?}','sent_to_checker')->name('user_management.user_group.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-user-group','grid_page')->name('user_management.user_group.verify')->middleware(['CheckRoute']);
    Route::post('/verify-user-group','grid')->name('user_management.user_group.verify')->middleware(['CheckRoute']);
    Route::get('/verify-user-group/{id?}','verify_page')->name('user_management.user_group.verify')->middleware(['CheckRoute']);
    Route::post('/verify-user-group/{id?}','verify')->name('user_management.user_group.verify')->middleware(['CheckRoute']);

    Route::get('/delete-user-group','grid_page')->name('user_management.user_group.delete')->middleware(['CheckRoute']);
    Route::post('/delete-user-group','grid')->name('user_management.user_group.delete')->middleware(['CheckRoute']);
    Route::get('/delete-user-group/{id?}','delete_page')->name('user_management.user_group.delete')->middleware(['CheckRoute']);
    Route::post('/delete-user-group/{id?}','delete')->name('user_management.user_group.delete')->middleware(['CheckRoute']);

    Route::get('/deactive-user-group','grid_page')->name('user_management.user_group.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-user-group','grid')->name('user_management.user_group.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-user-group/{id?}','deactive_page')->name('user_management.user_group.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-user-group/{id?}','deactive')->name('user_management.user_group.deactive')->middleware(['CheckRoute']);

    Route::get('/active-user-group','grid_page')->name('user_management.user_group.active')->middleware(['CheckRoute']);
    Route::post('/active-user-group','grid')->name('user_management.user_group.active')->middleware(['CheckRoute']);
    Route::get('/active-user-group/{id?}','active_page')->name('user_management.user_group.active')->middleware(['CheckRoute']);
    Route::post('/active-user-group/{id?}','active')->name('user_management.user_group.active')->middleware(['CheckRoute']);

    Route::get('/right-user-group','grid_page')->name('user_management.user_group.right')->middleware(['CheckRoute']);
    Route::post('/right-user-group','grid')->name('user_management.user_group.right')->middleware(['CheckRoute']);
    Route::get('/right-user-group/{id?}','right_page')->name('user_management.user_group.right')->middleware(['CheckRoute']);
    Route::post('/right-user-group/{id?}','right')->name('user_management.user_group.right')->middleware(['CheckRoute']);

    Route::get('/user-group-right-details/{id?}','user_group_right_details')->name('user_management.user_group.right.details');
    Route::get('/user-group-chenged-right-details/{id?}','user_group_chenged_right_details')->name('user_management.user_group.chenged.right.details');
    Route::get('/user-group-history/{id?}','user_group_history')->name('user_management.user_group.history');
});

Route::controller(UserController::class)->group(function(){

    Route::get('/add-user','add_page')->name('user_management.user.add')->middleware(['CheckRoute']);
    Route::post('/add-user','store')->name('user_management.user.add')->middleware(['CheckRoute']);

    Route::get('/view-user','grid_page')->name('user_management.user.view')->middleware(['CheckRoute']);
    Route::post('/view-user','grid')->name('user_management.user.view')->middleware(['CheckRoute']);
    Route::get('/view-user/{id?}','details_view')->name('user_management.user.view')->middleware(['CheckRoute']);

    Route::get('/edit-user','grid_page')->name('user_management.user.edit')->middleware(['CheckRoute']);
    Route::post('/edit-user','grid')->name('user_management.user.edit')->middleware(['CheckRoute']);
    Route::get('/edit-user/{id?}','edit_page')->name('user_management.user.edit')->middleware(['CheckRoute']);
    Route::post('/edit-user/{id?}','update')->name('user_management.user.edit')->middleware(['CheckRoute']);

    Route::get('/delete-user','grid_page')->name('user_management.user.delete')->middleware(['CheckRoute']);
    Route::post('/delete-user','grid')->name('user_management.user.delete')->middleware(['CheckRoute']);
    Route::get('/delete-user/{id?}','delete_page')->name('user_management.user.delete')->middleware(['CheckRoute']);
    Route::post('/delete-user/{id?}','delete')->name('user_management.user.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-user','grid_page')->name('user_management.user.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-user','grid')->name('user_management.user.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-user/{id?}','sent_to_checker_page')->name('user_management.user.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-user/{id?}','sent_to_checker')->name('user_management.user.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-user','grid_page')->name('user_management.user.verify')->middleware(['CheckRoute']);
    Route::post('/verify-user','grid')->name('user_management.user.verify')->middleware(['CheckRoute']);
    Route::get('/verify-user/{id?}','verify_page')->name('user_management.user.verify')->middleware(['CheckRoute']);
    Route::post('/verify-user/{id?}','verify')->name('user_management.user.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-user','grid_page')->name('user_management.user.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-user','grid')->name('user_management.user.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-user/{id?}','deactive_page')->name('user_management.user.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-user/{id?}','deactive')->name('user_management.user.deactive')->middleware(['CheckRoute']);

    Route::get('/active-user','grid_page')->name('user_management.user.active')->middleware(['CheckRoute']);
    Route::post('/active-user','grid')->name('user_management.user.active')->middleware(['CheckRoute']);
    Route::get('/active-user/{id?}','active_page')->name('user_management.user.active')->middleware(['CheckRoute']);
    Route::post('/active-user/{id?}','active')->name('user_management.user.active')->middleware(['CheckRoute']);

    Route::get('/unlock-user','grid_page')->name('user_management.user.unlock')->middleware(['CheckRoute']);
    Route::post('/unlock-user','grid')->name('user_management.user.unlock')->middleware(['CheckRoute']);
    Route::get('/unlock-user/{id?}','unlock_page')->name('user_management.user.unlock')->middleware(['CheckRoute']);
    Route::post('/unlock-user/{id?}','unlock')->name('user_management.user.unlock')->middleware(['CheckRoute']);

    Route::get('/right-user','grid_page')->name('user_management.user.right')->middleware(['CheckRoute']);
    Route::post('/right-user','grid')->name('user_management.user.right')->middleware(['CheckRoute']);
    Route::get('/right-user/{id?}','right_page')->name('user_management.user.right')->middleware(['CheckRoute']);
    Route::post('/right-user/{id?}','right')->name('user_management.user.right')->middleware(['CheckRoute']);

    Route::get('/reset-password-user','grid_page')->name('user_management.user.reset')->middleware(['CheckRoute']);
    Route::post('/reset-password-user','grid')->name('user_management.user.reset')->middleware(['CheckRoute']);
    Route::get('/reset-password-user/{id?}','reset_password_page')->name('user_management.user.reset')->middleware(['CheckRoute']);
    Route::post('/reset-password-user/{id?}','reset_password')->name('user_management.user.reset')->middleware(['CheckRoute']);

    Route::get('/user-right-details/{id?}','right_details')->name('user_management.user.right.details');
    Route::get('/user-chenged-right-details/{id?}','user_chenged_right_details')->name('user_management.user.chenged.right.details');
    Route::get('/user-history/{id?}','history')->name('user_management.user.history');
});

Route::controller(ProfileController::class)->group(function(){

    Route::get('/change-user-password','change_password_page')->name('user_management.profile.change_password')->middleware(['CheckRoute']);
    Route::post('/change-user-password','change_password')->name('user_management.profile.change_password')->middleware(['CheckRoute']);

    Route::get('/profile-details','profile_details_page')->name('user_management.profile.profile_details')->middleware(['CheckRoute']);
});

Route::controller(DepartmentController::class)->group(function(){

    Route::get('/add-department','add_page')->name('reference.department.add')->middleware(['CheckRoute']);
    Route::post('/add-department','store')->name('reference.department.add')->middleware(['CheckRoute']);

    Route::get('/view-department','grid_page')->name('reference.department.view')->middleware(['CheckRoute']);
    Route::post('/view-department','grid')->name('reference.department.view')->middleware(['CheckRoute']);
    Route::get('/view-department/{id?}','details_view')->name('reference.department.view')->middleware(['CheckRoute']);

    Route::get('/edit-department','grid_page')->name('reference.department.edit')->middleware(['CheckRoute']);
    Route::post('/edit-department','grid')->name('reference.department.edit')->middleware(['CheckRoute']);
    Route::get('/edit-department/{id?}','edit_page')->name('reference.department.edit')->middleware(['CheckRoute']);
    Route::post('/edit-department/{id?}','update')->name('reference.department.edit')->middleware(['CheckRoute']);

    Route::get('/delete-department','grid_page')->name('reference.department.delete')->middleware(['CheckRoute']);
    Route::post('/delete-department','grid')->name('reference.department.delete')->middleware(['CheckRoute']);
    Route::get('/delete-department/{id?}','delete_page')->name('reference.department.delete')->middleware(['CheckRoute']);
    Route::post('/delete-department/{id?}','delete')->name('reference.department.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-department','grid_page')->name('reference.department.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-department','grid')->name('reference.department.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-department/{id?}','sent_to_checker_page')->name('reference.department.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-department/{id?}','sent_to_checker')->name('reference.department.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-department','grid_page')->name('reference.department.verify')->middleware(['CheckRoute']);
    Route::post('/verify-department','grid')->name('reference.department.verify')->middleware(['CheckRoute']);
    Route::get('/verify-department/{id?}','verify_page')->name('reference.department.verify')->middleware(['CheckRoute']);
    Route::post('/verify-department/{id?}','verify')->name('reference.department.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-department','grid_page')->name('reference.department.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-department','grid')->name('reference.department.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-department/{id?}','deactive_page')->name('reference.department.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-department/{id?}','deactive')->name('reference.department.deactive')->middleware(['CheckRoute']);

    Route::get('/active-department','grid_page')->name('reference.department.active')->middleware(['CheckRoute']);
    Route::post('/active-department','grid')->name('reference.department.active')->middleware(['CheckRoute']);
    Route::get('/active-department/{id?}','active_page')->name('reference.department.active')->middleware(['CheckRoute']);
    Route::post('/active-department/{id?}','active')->name('reference.department.active')->middleware(['CheckRoute']);

    Route::get('/department-history/{id?}','department_history')->name('reference.department.history');

});

Route::controller(DesignationController::class)->group(function(){

    Route::get('/add-designation','add_page')->name('reference.designation.add')->middleware(['CheckRoute']);
    Route::post('/add-designation','store')->name('reference.designation.add')->middleware(['CheckRoute']);

    Route::get('/view-designation','grid_page')->name('reference.designation.view')->middleware(['CheckRoute']);
    Route::post('/view-designation','grid')->name('reference.designation.view')->middleware(['CheckRoute']);
    Route::get('/view-designation/{id?}','details_view')->name('reference.designation.view')->middleware(['CheckRoute']);

    Route::get('/edit-designation','grid_page')->name('reference.designation.edit')->middleware(['CheckRoute']);
    Route::post('/edit-designation','grid')->name('reference.designation.edit')->middleware(['CheckRoute']);
    Route::get('/edit-designation/{id?}','edit_page')->name('reference.designation.edit')->middleware(['CheckRoute']);
    Route::post('/edit-designation/{id?}','update')->name('reference.designation.edit')->middleware(['CheckRoute']);

    Route::get('/delete-designation','grid_page')->name('reference.designation.delete')->middleware(['CheckRoute']);
    Route::post('/delete-designation','grid')->name('reference.designation.delete')->middleware(['CheckRoute']);
    Route::get('/delete-designation/{id?}','delete_page')->name('reference.designation.delete')->middleware(['CheckRoute']);
    Route::post('/delete-designation/{id?}','delete')->name('reference.designation.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-designation','grid_page')->name('reference.designation.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-designation','grid')->name('reference.designation.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-designation/{id?}','sent_to_checker_page')->name('reference.designation.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-designation/{id?}','sent_to_checker')->name('reference.designation.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-designation','grid_page')->name('reference.designation.verify')->middleware(['CheckRoute']);
    Route::post('/verify-designation','grid')->name('reference.designation.verify')->middleware(['CheckRoute']);
    Route::get('/verify-designation/{id?}','verify_page')->name('reference.designation.verify')->middleware(['CheckRoute']);
    Route::post('/verify-designation/{id?}','verify')->name('reference.designation.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-designation','grid_page')->name('reference.designation.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-designation','grid')->name('reference.designation.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-designation/{id?}','deactive_page')->name('reference.designation.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-designation/{id?}','deactive')->name('reference.designation.deactive')->middleware(['CheckRoute']);

    Route::get('/active-designation','grid_page')->name('reference.designation.active')->middleware(['CheckRoute']);
    Route::post('/active-designation','grid')->name('reference.designation.active')->middleware(['CheckRoute']);
    Route::get('/active-designation/{id?}','active_page')->name('reference.designation.active')->middleware(['CheckRoute']);
    Route::post('/active-designation/{id?}','active')->name('reference.designation.active')->middleware(['CheckRoute']);

    Route::get('/designation-history/{id?}','history')->name('reference.designation.history');
});

Route::controller(DivisionController::class)->group(function(){

    Route::get('/add-division','add_page')->name('reference.division.add')->middleware(['CheckRoute']);
    Route::post('/add-division','store')->name('reference.division.add')->middleware(['CheckRoute']);

    Route::get('/view-division','grid_page')->name('reference.division.view')->middleware(['CheckRoute']);
    Route::post('/view-division','grid')->name('reference.division.view')->middleware(['CheckRoute']);
    Route::get('/view-division/{id?}','details_view')->name('reference.division.view')->middleware(['CheckRoute']);

    Route::get('/edit-division','grid_page')->name('reference.division.edit')->middleware(['CheckRoute']);
    Route::post('/edit-division','grid')->name('reference.division.edit')->middleware(['CheckRoute']);
    Route::get('/edit-division/{id?}','edit_page')->name('reference.division.edit')->middleware(['CheckRoute']);
    Route::post('/edit-division/{id?}','update')->name('reference.division.edit')->middleware(['CheckRoute']);

    Route::get('/delete-division','grid_page')->name('reference.division.delete')->middleware(['CheckRoute']);
    Route::post('/delete-division','grid')->name('reference.division.delete')->middleware(['CheckRoute']);
    Route::get('/delete-division/{id?}','delete_page')->name('reference.division.delete')->middleware(['CheckRoute']);
    Route::post('/delete-division/{id?}','delete')->name('reference.division.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-division','grid_page')->name('reference.division.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-division','grid')->name('reference.division.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-division/{id?}','sent_to_checker_page')->name('reference.division.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-division/{id?}','sent_to_checker')->name('reference.division.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-division','grid_page')->name('reference.division.verify')->middleware(['CheckRoute']);
    Route::post('/verify-division','grid')->name('reference.division.verify')->middleware(['CheckRoute']);
    Route::get('/verify-division/{id?}','verify_page')->name('reference.division.verify')->middleware(['CheckRoute']);
    Route::post('/verify-division/{id?}','verify')->name('reference.division.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-division','grid_page')->name('reference.division.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-division','grid')->name('reference.division.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-division/{id?}','deactive_page')->name('reference.division.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-division/{id?}','deactive')->name('reference.division.deactive')->middleware(['CheckRoute']);

    Route::get('/active-division','grid_page')->name('reference.division.active')->middleware(['CheckRoute']);
    Route::post('/active-division','grid')->name('reference.division.active')->middleware(['CheckRoute']);
    Route::get('/active-division/{id?}','active_page')->name('reference.division.active')->middleware(['CheckRoute']);
    Route::post('/active-division/{id?}','active')->name('reference.division.active')->middleware(['CheckRoute']);

    Route::get('/division-history/{id?}','history')->name('reference.division.history');
});

Route::controller(DistrictController::class)->group(function(){

    Route::get('/add-district','add_page')->name('reference.district.add')->middleware(['CheckRoute']);
    Route::post('/add-district','store')->name('reference.district.add')->middleware(['CheckRoute']);

    Route::get('/view-district','grid_page')->name('reference.district.view')->middleware(['CheckRoute']);
    Route::post('/view-district','grid')->name('reference.district.view')->middleware(['CheckRoute']);
    Route::get('/view-district/{id?}','details_view')->name('reference.district.view')->middleware(['CheckRoute']);

    Route::get('/edit-district','grid_page')->name('reference.district.edit')->middleware(['CheckRoute']);
    Route::post('/edit-district','grid')->name('reference.district.edit')->middleware(['CheckRoute']);
    Route::get('/edit-district/{id?}','edit_page')->name('reference.district.edit')->middleware(['CheckRoute']);
    Route::post('/edit-district/{id?}','update')->name('reference.district.edit')->middleware(['CheckRoute']);

    Route::get('/delete-district','grid_page')->name('reference.district.delete')->middleware(['CheckRoute']);
    Route::post('/delete-district','grid')->name('reference.district.delete')->middleware(['CheckRoute']);
    Route::get('/delete-district/{id?}','delete_page')->name('reference.district.delete')->middleware(['CheckRoute']);
    Route::post('/delete-district/{id?}','delete')->name('reference.district.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-district','grid_page')->name('reference.district.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-district','grid')->name('reference.district.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-district/{id?}','sent_to_checker_page')->name('reference.district.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-district/{id?}','sent_to_checker')->name('reference.district.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-district','grid_page')->name('reference.district.verify')->middleware(['CheckRoute']);
    Route::post('/verify-district','grid')->name('reference.district.verify')->middleware(['CheckRoute']);
    Route::get('/verify-district/{id?}','verify_page')->name('reference.district.verify')->middleware(['CheckRoute']);
    Route::post('/verify-district/{id?}','verify')->name('reference.district.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-district','grid_page')->name('reference.district.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-district','grid')->name('reference.district.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-district/{id?}','deactive_page')->name('reference.district.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-district/{id?}','deactive')->name('reference.district.deactive')->middleware(['CheckRoute']);

    Route::get('/active-district','grid_page')->name('reference.district.active')->middleware(['CheckRoute']);
    Route::post('/active-district','grid')->name('reference.district.active')->middleware(['CheckRoute']);
    Route::get('/active-district/{id?}','active_page')->name('reference.district.active')->middleware(['CheckRoute']);
    Route::post('/active-district/{id?}','active')->name('reference.district.active')->middleware(['CheckRoute']);

    Route::get('/district-history/{id?}','history')->name('reference.district.history');

});

Route::controller(FloorController::class)->group(function(){

    Route::get('/add-floor','add_page')->name('reference.floor.add')->middleware(['CheckRoute']);
    Route::post('/add-floor','store')->name('reference.floor.add')->middleware(['CheckRoute']);

    Route::get('/view-floor','grid_page')->name('reference.floor.view')->middleware(['CheckRoute']);
    Route::post('/view-floor','grid')->name('reference.floor.view')->middleware(['CheckRoute']);
    Route::get('/view-floor/{id?}','details_view')->name('reference.floor.view')->middleware(['CheckRoute']);

    Route::get('/edit-floor','grid_page')->name('reference.floor.edit')->middleware(['CheckRoute']);
    Route::post('/edit-floor','grid')->name('reference.floor.edit')->middleware(['CheckRoute']);
    Route::get('/edit-floor/{id?}','edit_page')->name('reference.floor.edit')->middleware(['CheckRoute']);
    Route::post('/edit-floor/{id?}','update')->name('reference.floor.edit')->middleware(['CheckRoute']);

    Route::get('/delete-floor','grid_page')->name('reference.floor.delete')->middleware(['CheckRoute']);
    Route::post('/delete-floor','grid')->name('reference.floor.delete')->middleware(['CheckRoute']);
    Route::get('/delete-floor/{id?}','delete_page')->name('reference.floor.delete')->middleware(['CheckRoute']);
    Route::post('/delete-floor/{id?}','delete')->name('reference.floor.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-floor','grid_page')->name('reference.floor.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-floor','grid')->name('reference.floor.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-floor/{id?}','sent_to_checker_page')->name('reference.floor.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-floor/{id?}','sent_to_checker')->name('reference.floor.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-floor','grid_page')->name('reference.floor.verify')->middleware(['CheckRoute']);
    Route::post('/verify-floor','grid')->name('reference.floor.verify')->middleware(['CheckRoute']);
    Route::get('/verify-floor/{id?}','verify_page')->name('reference.floor.verify')->middleware(['CheckRoute']);
    Route::post('/verify-floor/{id?}','verify')->name('reference.floor.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-floor','grid_page')->name('reference.floor.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-floor','grid')->name('reference.floor.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-floor/{id?}','deactive_page')->name('reference.floor.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-floor/{id?}','deactive')->name('reference.floor.deactive')->middleware(['CheckRoute']);

    Route::get('/active-floor','grid_page')->name('reference.floor.active')->middleware(['CheckRoute']);
    Route::post('/active-floor','grid')->name('reference.floor.active')->middleware(['CheckRoute']);
    Route::get('/active-floor/{id?}','active_page')->name('reference.floor.active')->middleware(['CheckRoute']);
    Route::post('/active-floor/{id?}','active')->name('reference.floor.active')->middleware(['CheckRoute']);

    Route::get('/floor-history/{id?}','history')->name('reference.floor.history');
});

Route::controller(CounterController::class)->group(function(){

    Route::get('/add-counter','add_page')->name('reference.counter.add')->middleware(['CheckRoute']);
    Route::post('/add-counter','store')->name('reference.counter.add')->middleware(['CheckRoute']);

    Route::get('/view-counter','grid_page')->name('reference.counter.view')->middleware(['CheckRoute']);
    Route::post('/view-counter','grid')->name('reference.counter.view')->middleware(['CheckRoute']);
    Route::get('/view-counter/{id?}','details_view')->name('reference.counter.view')->middleware(['CheckRoute']);

    Route::get('/edit-counter','grid_page')->name('reference.counter.edit')->middleware(['CheckRoute']);
    Route::post('/edit-counter','grid')->name('reference.counter.edit')->middleware(['CheckRoute']);
    Route::get('/edit-counter/{id?}','edit_page')->name('reference.counter.edit')->middleware(['CheckRoute']);
    Route::post('/edit-counter/{id?}','update')->name('reference.counter.edit')->middleware(['CheckRoute']);

    Route::get('/delete-counter','grid_page')->name('reference.counter.delete')->middleware(['CheckRoute']);
    Route::post('/delete-counter','grid')->name('reference.counter.delete')->middleware(['CheckRoute']);
    Route::get('/delete-counter/{id?}','delete_page')->name('reference.counter.delete')->middleware(['CheckRoute']);
    Route::post('/delete-counter/{id?}','delete')->name('reference.counter.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-counter','grid_page')->name('reference.counter.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-counter','grid')->name('reference.counter.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-counter/{id?}','sent_to_checker_page')->name('reference.counter.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-counter/{id?}','sent_to_checker')->name('reference.counter.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-counter','grid_page')->name('reference.counter.verify')->middleware(['CheckRoute']);
    Route::post('/verify-counter','grid')->name('reference.counter.verify')->middleware(['CheckRoute']);
    Route::get('/verify-counter/{id?}','verify_page')->name('reference.counter.verify')->middleware(['CheckRoute']);
    Route::post('/verify-counter/{id?}','verify')->name('reference.counter.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-counter','grid_page')->name('reference.counter.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-counter','grid')->name('reference.counter.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-counter/{id?}','deactive_page')->name('reference.counter.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-counter/{id?}','deactive')->name('reference.counter.deactive')->middleware(['CheckRoute']);

    Route::get('/active-counter','grid_page')->name('reference.counter.active')->middleware(['CheckRoute']);
    Route::post('/active-counter','grid')->name('reference.counter.active')->middleware(['CheckRoute']);
    Route::get('/active-counter/{id?}','active_page')->name('reference.counter.active')->middleware(['CheckRoute']);
    Route::post('/active-counter/{id?}','active')->name('reference.counter.active')->middleware(['CheckRoute']);

    Route::get('/counter-history/{id?}','history')->name('reference.counter.history');
});

Route::controller(ServiceController::class)->group(function(){

    Route::get('/add-service','add_page')->name('reference.service.add')->middleware(['CheckRoute']);
    Route::post('/add-service','store')->name('reference.service.add')->middleware(['CheckRoute']);

    Route::get('/view-service','grid_page')->name('reference.service.view')->middleware(['CheckRoute']);
    Route::post('/view-service','grid')->name('reference.service.view')->middleware(['CheckRoute']);
    Route::get('/view-service/{id?}','details_view')->name('reference.service.view')->middleware(['CheckRoute']);

    Route::get('/edit-service','grid_page')->name('reference.service.edit')->middleware(['CheckRoute']);
    Route::post('/edit-service','grid')->name('reference.service.edit')->middleware(['CheckRoute']);
    Route::get('/edit-service/{id?}','edit_page')->name('reference.service.edit')->middleware(['CheckRoute']);
    Route::post('/edit-service/{id?}','update')->name('reference.service.edit')->middleware(['CheckRoute']);

    Route::get('/delete-service','grid_page')->name('reference.service.delete')->middleware(['CheckRoute']);
    Route::post('/delete-service','grid')->name('reference.service.delete')->middleware(['CheckRoute']);
    Route::get('/delete-service/{id?}','delete_page')->name('reference.service.delete')->middleware(['CheckRoute']);
    Route::post('/delete-service/{id?}','delete')->name('reference.service.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-service','grid_page')->name('reference.service.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-service','grid')->name('reference.service.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-service/{id?}','sent_to_checker_page')->name('reference.service.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-service/{id?}','sent_to_checker')->name('reference.service.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-service','grid_page')->name('reference.service.verify')->middleware(['CheckRoute']);
    Route::post('/verify-service','grid')->name('reference.service.verify')->middleware(['CheckRoute']);
    Route::get('/verify-service/{id?}','verify_page')->name('reference.service.verify')->middleware(['CheckRoute']);
    Route::post('/verify-service/{id?}','verify')->name('reference.service.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-service','grid_page')->name('reference.service.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-service','grid')->name('reference.service.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-service/{id?}','deactive_page')->name('reference.service.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-service/{id?}','deactive')->name('reference.service.deactive')->middleware(['CheckRoute']);

    Route::get('/active-service','grid_page')->name('reference.service.active')->middleware(['CheckRoute']);
    Route::post('/active-service','grid')->name('reference.service.active')->middleware(['CheckRoute']);
    Route::get('/active-service/{id?}','active_page')->name('reference.service.active')->middleware(['CheckRoute']);
    Route::post('/active-service/{id?}','active')->name('reference.service.active')->middleware(['CheckRoute']);

    Route::get('/service-history/{id?}','history')->name('reference.service.history');
});

Route::controller(UapzilaController::class)->group(function(){

    Route::get('/add-upazila','add_page')->name('reference.upazila.add')->middleware(['CheckRoute']);
    Route::post('/add-upazila','store')->name('reference.upazila.add')->middleware(['CheckRoute']);

    Route::get('/view-upazila','grid_page')->name('reference.upazila.view')->middleware(['CheckRoute']);
    Route::post('/view-upazila','grid')->name('reference.upazila.view')->middleware(['CheckRoute']);
    Route::get('/view-upazila/{id?}','details_view')->name('reference.upazila.view')->middleware(['CheckRoute']);

    Route::get('/edit-upazila','grid_page')->name('reference.upazila.edit')->middleware(['CheckRoute']);
    Route::post('/edit-upazila','grid')->name('reference.upazila.edit')->middleware(['CheckRoute']);
    Route::get('/edit-upazila/{id?}','edit_page')->name('reference.upazila.edit')->middleware(['CheckRoute']);
    Route::post('/edit-upazila/{id?}','update')->name('reference.upazila.edit')->middleware(['CheckRoute']);

    Route::get('/delete-upazila','grid_page')->name('reference.upazila.delete')->middleware(['CheckRoute']);
    Route::post('/delete-upazila','grid')->name('reference.upazila.delete')->middleware(['CheckRoute']);
    Route::get('/delete-upazila/{id?}','delete_page')->name('reference.upazila.delete')->middleware(['CheckRoute']);
    Route::post('/delete-upazila/{id?}','delete')->name('reference.upazila.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-upazila','grid_page')->name('reference.upazila.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-upazila','grid')->name('reference.upazila.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-upazila/{id?}','sent_to_checker_page')->name('reference.upazila.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-upazila/{id?}','sent_to_checker')->name('reference.upazila.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-upazila','grid_page')->name('reference.upazila.verify')->middleware(['CheckRoute']);
    Route::post('/verify-upazila','grid')->name('reference.upazila.verify')->middleware(['CheckRoute']);
    Route::get('/verify-upazila/{id?}','verify_page')->name('reference.upazila.verify')->middleware(['CheckRoute']);
    Route::post('/verify-upazila/{id?}','verify')->name('reference.upazila.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-upazila','grid_page')->name('reference.upazila.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-upazila','grid')->name('reference.upazila.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-upazila/{id?}','deactive_page')->name('reference.upazila.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-upazila/{id?}','deactive')->name('reference.upazila.deactive')->middleware(['CheckRoute']);

    Route::get('/active-upazila','grid_page')->name('reference.upazila.active')->middleware(['CheckRoute']);
    Route::post('/active-upazila','grid')->name('reference.upazila.active')->middleware(['CheckRoute']);
    Route::get('/active-upazila/{id?}','active_page')->name('reference.upazila.active')->middleware(['CheckRoute']);
    Route::post('/active-upazila/{id?}','active')->name('reference.upazila.active')->middleware(['CheckRoute']);

    Route::get('/upazila-history/{id?}','history')->name('reference.upazila.history');

});

Route::controller(BranchController::class)->group(function(){

    Route::get('/add-branch','add_page')->name('reference.branch.add')->middleware(['CheckRoute']);
    Route::post('/add-branch','store')->name('reference.branch.add')->middleware(['CheckRoute']);

    Route::get('/view-branch','grid_page')->name('reference.branch.view')->middleware(['CheckRoute']);
    Route::post('/view-branch','grid')->name('reference.branch.view')->middleware(['CheckRoute']);
    Route::get('/view-branch/{id?}','details_view')->name('reference.branch.view')->middleware(['CheckRoute']);

    Route::get('/edit-branch','grid_page')->name('reference.branch.edit')->middleware(['CheckRoute']);
    Route::post('/edit-branch','grid')->name('reference.branch.edit')->middleware(['CheckRoute']);
    Route::get('/edit-branch/{id?}','edit_page')->name('reference.branch.edit')->middleware(['CheckRoute']);
    Route::post('/edit-branch/{id?}','update')->name('reference.branch.edit')->middleware(['CheckRoute']);

    Route::get('/delete-branch','grid_page')->name('reference.branch.delete')->middleware(['CheckRoute']);
    Route::post('/delete-branch','grid')->name('reference.branch.delete')->middleware(['CheckRoute']);
    Route::get('/delete-branch/{id?}','delete_page')->name('reference.branch.delete')->middleware(['CheckRoute']);
    Route::post('/delete-branch/{id?}','delete')->name('reference.branch.delete')->middleware(['CheckRoute']);

    Route::get('/sent-to-checker-branch','grid_page')->name('reference.branch.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-branch','grid')->name('reference.branch.sent_to_checker')->middleware(['CheckRoute']);
    Route::get('/sent-to-checker-branch/{id?}','sent_to_checker_page')->name('reference.branch.sent_to_checker')->middleware(['CheckRoute']);
    Route::post('/sent-to-checker-branch/{id?}','sent_to_checker')->name('reference.branch.sent_to_checker')->middleware(['CheckRoute']);

    Route::get('/verify-branch','grid_page')->name('reference.branch.verify')->middleware(['CheckRoute']);
    Route::post('/verify-branch','grid')->name('reference.branch.verify')->middleware(['CheckRoute']);
    Route::get('/verify-branch/{id?}','verify_page')->name('reference.branch.verify')->middleware(['CheckRoute']);
    Route::post('/verify-branch/{id?}','verify')->name('reference.branch.verify')->middleware(['CheckRoute']);

    Route::get('/deactive-branch','grid_page')->name('reference.branch.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-branch','grid')->name('reference.branch.deactive')->middleware(['CheckRoute']);
    Route::get('/deactive-branch/{id?}','deactive_page')->name('reference.branch.deactive')->middleware(['CheckRoute']);
    Route::post('/deactive-branch/{id?}','deactive')->name('reference.branch.deactive')->middleware(['CheckRoute']);

    Route::get('/active-branch','grid_page')->name('reference.branch.active')->middleware(['CheckRoute']);
    Route::post('/active-branch','grid')->name('reference.branch.active')->middleware(['CheckRoute']);
    Route::get('/active-branch/{id?}','active_page')->name('reference.branch.active')->middleware(['CheckRoute']);
    Route::post('/active-branch/{id?}','active')->name('reference.branch.active')->middleware(['CheckRoute']);    
    
    Route::get('/branch-history/{id?}','history')->name('reference.branch.history');
});

Route::controller(ReportController::class)->group(function(){

    Route::get('/user-activity-report','user_activity_report_page')->name('report.user_report.activity_report')->middleware(['CheckRoute']);
    Route::post('/user-activity-report','user_activity_report')->name('report.user_report.activity_report')->middleware(['CheckRoute']);

    Route::get('/user-log-report','user_log_report_page')->name('report.user_report.log_report')->middleware(['CheckRoute']);
    Route::post('/user-log-report','user_log_report')->name('report.user_report.log_report')->middleware(['CheckRoute']);

    Route::get('/user-list-report','user_list_report_page')->name('report.user_report.user_list')->middleware(['CheckRoute']);
    Route::post('/user-list-report','user_list_report')->name('report.user_report.user_list')->middleware(['CheckRoute']);

    Route::get('/user-token-report','user_token_report_page')->name('report.token_report.user_wise_report')->middleware(['CheckRoute']);
    Route::post('/user-token-report','user_token_report')->name('report.token_report.user_wise_report')->middleware(['CheckRoute']);
});

Route::controller(TokenController::class)->group(function(){

    Route::get('/registration-url','registration_url_page')->name('service.url.registration_url')->middleware(['CheckRoute']);
    Route::post('/registration-url','registration_url_create')->name('service.url.registration_url')->middleware(['CheckRoute']);

    Route::get('/registration/{token?}','registration')->name('service.url.registration');
    Route::post ('/registration','generate_token')->name('service.url.registration');

    Route::get('/counter-wise-display-url','counter_wise_display_url_page')->name('service.url.counter_wise_display_url')->middleware(['CheckRoute']);
    Route::post('/counter-wise-display-url','counter_wise_display_url_create')->name('service.url.counter_wise_display_url')->middleware(['CheckRoute']);

    Route::get('/counter-wise-display/{token?}','counter_wise_display')->name('service.url.counter.wise.display');
    Route::post('/counter-wise-display','get_counter_wise_token_data')->name('service.url.counter.wise.display');


    Route::get('/call','call_page')->name('service.manage_call.call')->middleware(['CheckRoute']);
    Route::post('/call','call_token')->name('service.manage_call.call')->middleware(['CheckRoute']);

    Route::get('/release-counter','release_page')->name('service.manage_call.release_counter')->middleware(['CheckRoute']);
    Route::post('/release-counter','release_counter')->name('service.manage_call.release_counter')->middleware(['CheckRoute']);

    Route::get('/my-call-list','my_call_list_grid_page')->name('service.manage_call.my_call_list')->middleware(['CheckRoute']);
    Route::post('/my-call-list','my_call_list_grid')->name('service.manage_call.my_call_list')->middleware(['CheckRoute']);
    Route::get('/my-call-list/{id?}','my_call_list_details_view')->name('service.brmanage_callanch.my_call_list')->middleware(['CheckRoute']);

});