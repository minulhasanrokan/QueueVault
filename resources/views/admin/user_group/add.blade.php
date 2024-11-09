<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>User Group Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="group_name" placeholder="Enter User Group Name" maxlength="100" onfocusout ="check_duplicate_value('group_name','user_groups',this.value,0);" value="" tabindex="1">
                        <span id="group_name-error" class="error">Please Enter User Group Name</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>User Group Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="group_code" name="group_code" placeholder="Enter User Group Code" onfocusout ="check_duplicate_value('group_code','user_groups',this.value,0);" value="" tabindex="2">
                        <span id="group_code-error" class="error">Please Enter User Group Code</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>User Group Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="group_title" name="group_title" placeholder="Enter User Group Title" value="" tabindex="3">
                        <span id="group_title-error" class="error">Please Enter User Group Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>User Group Details</label>
                        <textarea class="form-control" id="group_details" maxlength="250" name="group_details" tabindex="4" ></textarea>
                        <span id="group_details-error" class="error">Please Enter User Group Details</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>User Group Photo <span class="span_red">*</span></label>
                        <input onchange="readImageUrl(this,'group_logo_photo');" type="file" accept="image/png,image/jpeg" class="form-control" id="group_logo" name="group_logo" placeholder="Enter User Group Photo" tabindex="5">
                        <input type="hidden" name="hidden_group_logo" id="hidden_group_logo" value="">
                        <span id="group_logo-error" class="error">Please Enter User Group Photo</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <img class="rounded avatar-lg" width="80" height="80" id="group_logo_photo" src="{{asset('uploads/user_group/user_group_logo.png')}}"/>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>User Group Icon <span class="span_red">*</span></label>
                        <input onchange="readImageUrl(this,'group_icon_photo');" type="file" accept="image/png,image/jpeg" class="form-control" id="group_icon" name="group_icon" placeholder="Enter User Group Icon" tabindex="6">
                        <input type="hidden" name="hidden_group_icon" id="hidden_group_icon" value="">
                        <span id="group_icon-error" class="error">Please Enter User Group Icon</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <img class="rounded avatar-lg" width="80" height="80" id="group_icon_photo" src="{{asset('uploads/user_group/user_group_icon.png')}}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="7">Add User Group</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#group_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#group_name').focus();
    });

    function validation(){

        if( form_validation('group_name#text*group_code#text*group_title#text','User Group Name*User Group Code*User Group Title')==false ){

            return false;
        }

        var hidden_group_logo = $("#hidden_group_logo").val();
        var hidden_group_icon = $("#hidden_group_icon").val();

        if(hidden_group_logo==''){

            if( form_validation('group_logo#file','User Group Logo')==false ){

                return false;
            } 
        }

        if(hidden_group_icon==''){

            if( form_validation('group_icon#file','User Group Icon')==false ){

                return false;
            } 
        }

        confirm_box('Add', 'Are You Sure Want To Save User Group Info?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("group_name", $("#group_name").val());
        form_data.append("group_code", $("#group_code").val());
        form_data.append("group_title", $("#group_title").val());
        form_data.append("group_details", $("#group_details").val());
        form_data.append("hidden_group_logo", $("#hidden_group_logo").val());
        form_data.append("hidden_group_icon", $("#hidden_group_icon").val());

        var group_logo = $('#group_logo')[0].files;
        form_data.append('group_logo',group_logo[0]);

        var group_icon = $('#group_icon')[0].files;
        form_data.append('group_icon',group_icon[0]);
    
        $.ajax({
            async: false,
            url: "{{route(request()->route()->getName())}}",
            type: "POST",
            data: form_data,
            processData: false,
            contentType: false,
            "beforeSend": function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            },
            success: function(response) {

                all_response(response,'{{route('user_management.user_group.view')}}','View User Group');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>
    

