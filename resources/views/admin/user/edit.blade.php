<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Employee/User ID <span class="span_red">*</span></label>
                        <input type="hidden" id="update_id" name="update_id" value="{{$single_data->id}}">
                        <input type="hidden" id="actual_status" name="actual_status" value="{{$single_data->actual_status}}">
                        <input type="hidden" id="process_status" name="process_status" value="{{$single_data->process_status}}">
                        <input type="text" class="form-control" id="user_id" name="user_id" placeholder="Enter Employee/User ID" minlength="{{$system_data['userid_min_length']}}" maxlength="{{$system_data['password_max_length']}}" onfocusout ="check_duplicate_value('user_id','users',this.value,{{$single_data->id}});" value="{{$single_data->user_id}}" tabindex="1" autofocus>
                        <span id="user_id-error" class="error">Please Enter Employee/User ID</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Employee/User Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="name" name="name" placeholder="Enter Employee/User Name" value="{{$single_data->name}}" tabindex="2">
                        <span id="name-error" class="error">Please Enter Employee/User Name</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Employee/User Group <span class="span_red">*</span></label>
                        <div id="group_id_container"></div>
                        <span id="group_id-error" class="error">Please Select Employee/User Group</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Employee/User Branch <span class="span_red">*</span></label>
                        <div id="branch_id_container"></div>
                        <span id="branch_id-error" class="error">Please Select Employee/User Branch</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Employee/User Department <span class="span_red">*</span></label>
                        <div id="department_container"></div>
                        <span id="department-error" class="error">Please Select Employee/User Department</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Employee/User Assign Department <span class="span_red">*</span></label>
                        <div id="assign_department_container"></div>
                        <span id="assign_department-error" class="error">Please Select Employee/User Assign Department</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Employee/User Designation <span class="span_red">*</span></label>
                        <div id="designation_container"></div>
                        <span id="designation-error" class="error">Please Select Employee/User Designation</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Employee/User Address</label>
                        <input type="text" class="form-control" maxlength="200" id="address" name="address" placeholder="Enter Employee/User Address" value="{{$single_data->address}}" tabindex="8">
                        <span id="address-error" class="error">Please Enter Employee/User Address</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Employee/User Modile <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="11" id="mobile" name="mobile" placeholder="Enter Employee/User Modile" onfocusout ="check_duplicate_value('mobile','users',this.value,{{$single_data->id}});" value="{{$single_data->mobile}}" tabindex="9">
                        <span id="mobile-error" class="error">Please Enter Employee/User Modile</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Employee/User E-mail <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="email" name="email" placeholder="Enter Employee/User E-mail" onfocusout ="check_duplicate_value('email','users',this.value,{{$single_data->id}});" value="{{$single_data->email}}" tabindex="10">
                        <span id="email-error" class="error">Please Enter Employee/User E-mail</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Employee/User Photo</label>
                        <input onchange="readImageUrl(this,'user_photo_photo');" type="file" accept="image/png,image/jpeg" class="form-control" id="user_photo" name="user_photo" placeholder="Enter Employee/User Photo" value="" tabindex="11">
                        <input type="hidden" name="hidden_user_photo" id="hidden_user_photo" value="{{$single_data['user_photo']}}">
                        <span id="user_photo-error" class="error">Please Enter Employee/User Photo</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <img class="rounded avatar-lg" width="80" height="80" id="user_photo_photo" src="{{asset('uploads/user')}}/{{$single_data['user_photo']!=''?$single_data['user_photo']:'user.png'}}"/>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Employee/User Details</label>
                        <textarea class="form-control" id="details" maxlength="250" name="details" tabindex="12" >{!!$single_data->details!!}</textarea>
                        <span id="details-error" class="error">Please Enter Employee/User Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="13">Update Employee/User</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('user_groups','id,group_name','group_id','group_id_container','Select Employee/User Group',0,'{{$single_data->group_id}}',0,3,'',0,0,0,0);
    load_drop_down('branches','id,branch_name','branch_id','branch_id_container','Select Employee/User Branch',0,'{{$single_data->branch_id}}',0,4,'',0,0,0,0);
    load_drop_down('departments','id,department_name','department','department_container','Select Employee/User Department',0,'{{$single_data->department}}',0,5,'',0,0,0,0);
    load_drop_down('departments','id,department_name','assign_department','assign_department_container','Select Employee/User Assign Department',1,'{{$single_data->assign_department}}',0,6,'',0,0,0,0);
    load_drop_down('designations','id,designation_name','designation','designation_container','Select Employee/User Designation',0,'{{$single_data->designation}}',0,7,'',0,0,0,0);

    $('#details').summernote({
        height: 150,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#user_id').focus();
    });

    function validation(){

        if( form_validation('user_id#number*name#text*group_id#select*branch_id#select*department#select*assign_department#select*designation#select*mobile#mobile*email#email','Employee/User ID*Employee/User Name*Employee/User Name*Employee/User Branch*Employee/User Department*Employee/User Assign Department*Employee/User Designation*Employee/User Modile*Employee/User Modile')==false ){

            return false;
        }

        confirm_box('Add', 'Are You Sure Want To Update Employee/User Info?','update_data');
    }

    function update_data(){

        var form_data = new FormData();

        form_data.append("user_id", $("#user_id").val());
        form_data.append("name", $("#name").val());
        form_data.append("group_id", $("#group_id").val());
        form_data.append("branch_id", $("#branch_id").val());
        form_data.append("department", $("#department").val());
        form_data.append("assign_department", $("#assign_department").val());
        form_data.append("designation", $("#designation").val());
        form_data.append("address", $("#address").val());
        form_data.append("mobile", $("#mobile").val());
        form_data.append("email", $("#email").val());
        form_data.append("details", $("#details").val());
        form_data.append("update_id", $("#update_id").val());
        form_data.append("actual_status", $("#actual_status").val());
        form_data.append("process_status", $("#process_status").val());
        form_data.append("hidden_user_photo", $("#hidden_user_photo").val());

        var user_photo = $('#user_photo')[0].files;
        form_data.append('user_photo',user_photo[0]);
    
        $.ajax({
            async: false,
            url: "{{route(request()->route()->getName())}}/{{$single_data->id}}",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            "beforeSend": function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {

                all_response(response,'{{route(request()->route()->getName())}}','Edit View Employee/User');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>