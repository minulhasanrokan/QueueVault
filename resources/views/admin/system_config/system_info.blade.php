<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header">
            <h3 class="card-title">System Destails Information (All <span class="span_red">*</span> Marked Are Mandatory)</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>System Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="system_name" name="system_name" placeholder="Enter System Name" value="{{$system_data['system_name']}}" maxlength="200" tabindex="1" autofocus>
                        <span id="system_name-error" class="error">Please Enter System Name</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>System Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="system_title" name="system_title" placeholder="Enter System Title" value="{{$system_data['system_title']}}" maxlength="200"  tabindex="2">
                        <span id="system_title-error" class="error">Please Enter System Title</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>System Slogan <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="system_slogan" name="system_slogan" placeholder="Enter System Slogan" value="{{$system_data['system_slogan']}}" maxlength="200"  tabindex="3">
                        <span id="system_slogan-error" class="error">Please Enter System Slogan</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>System E-mail <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="system_email" name="system_email" placeholder="Enter System E-mail" value="{{$system_data['system_email']}}" maxlength="100"  tabindex="4">
                        <span id="system_email-error" class="error">Please Enter System E-mail</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>System Mobile <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="system_mobile" name="system_mobile" placeholder="Enter System Mobile" value="{{$system_data['system_mobile']}}" maxlength="11"  tabindex="5">
                        <span id="system_mobile-error" class="error">Please Enter System Mobile</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>System Copy Right <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="system_copy_right" name="system_copy_right" placeholder="Enter System Copy Right Text" value="{{$system_data['system_copy_right']}}" maxlength="200" tabindex="6">
                        <span id="system_copy_right-error" class="error">Please Enter System Copy Right Text</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>System Version <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="system_version" name="system_version" placeholder="Enter System Version" value="{{$system_data['system_version']}}" maxlength="50"  tabindex="7">
                        <span id="system_version-error" class="error">Please Enter System Version </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>System Address <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="system_address" name="system_address" placeholder="Enter System Address" value="{{$system_data['system_address']}}" maxlength="200" tabindex="8">
                        <span id="system_address-error" class="error">Please Enter System Address </span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>System Details</label>
                        <textarea class="form-control" id="system_details" name="system_details" maxlength="250" tabindex="9">{!!$system_data['system_details']!!}</textarea>
                        <span id="system_details-error" class="error">Please Enter System Details</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>System Logo <span class="span_red">*</span></label>
                        <input onchange="readImageUrl(this,'system_logo_photo');" type="file" accept="image/png,image/jpeg" class="form-control" id="system_logo" name="system_logo" placeholder="Enter System Logo" tabindex="10">
                        <input type="hidden" name="hidden_system_logo" id="hidden_system_logo" value="{{$system_data['system_logo']}}">
                        <span id="system_logo-error" class="error">Please Enter System Logo</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <img class="rounded avatar-lg" width="80" height="80" id="system_logo_photo" src="{{asset('uploads/system')}}/{{$system_data['system_logo']}}"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>System Icon <span class="span_red">*</span></label>
                        <input onchange="readImageUrl(this,'system_icon_photo');" type="file" accept="image/png,image/jpeg" class="form-control" id="system_icon" name="system_icon" placeholder="Enter System Icon" tabindex="11">
                        <input type="hidden" name="hidden_system_icon" id="hidden_system_icon" value="{{$system_data['system_icon']}}">
                        <span id="system_icon-error" class="error">Please Enter System Icon</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <img class="rounded avatar-lg" width="80" height="80" id="system_icon_photo" src="{{asset('uploads/system')}}/{{$system_data['system_icon']}}"/>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>System Background <span class="span_red">*</span></label>
                        <input onchange="readImageUrl(this,'system_bg_image_photo');" type="file" accept="image/png,image/jpeg" class="form-control" id="system_bg_image" name="system_bg_image" placeholder="Enter System Background" tabindex="12">
                        <input type="hidden" name="hidden_system_icon" id="hidden_system_bg_image" value="{{$system_data['system_bg_image']}}">
                        <span id="system_bg_image-error" class="error">Please Enter System Background Image</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <img class="rounded avatar-lg" width="80" height="80" id="system_bg_image_photo" src="{{asset('uploads/system')}}/{{$system_data['system_bg_image']}}"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="13">Update Info</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#system_details').summernote({
        height: 150,
        tabDisable: true
    });

    $('#system_name').focus();

    function validation(){

        if( form_validation('system_name#text*system_title#text*system_slogan#text*system_email#email*system_mobile#mobile*system_copy_right#text*system_version#text*system_address#text','System Name*System Title*System Slogan*System E-mail*System Mobile*System Copy Right Text*System Version*System Address')==false ){

            return false;
        }

        var hidden_system_logo = $("#hidden_system_logo").val();
        var hidden_system_icon = $("#hidden_system_icon").val();
        var hidden_system_bg_image = $("#hidden_system_bg_image").val();

        if(hidden_system_logo==''){

            if( form_validation('system_logo#file','System Logo')==false ){

                return false;
            } 
        }

        if(hidden_system_icon==''){

            if( form_validation('system_icon#file','System Icon')==false ){

                return false;
            } 
        }

        if(hidden_system_bg_image==''){

            if( form_validation('system_bg_image#file','System Background')==false ){

                return false;
            } 
        }

        confirm_box('System Info', 'Are You Sure Want To Save System Info?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("system_name", $("#system_name").val());
        form_data.append("system_title", $("#system_title").val());
        form_data.append("system_slogan", $("#system_slogan").val());
        form_data.append("system_email", $("#system_email").val());
        form_data.append("system_mobile", $("#system_mobile").val());
        form_data.append("system_address", $("#system_address").val());
        form_data.append("system_details", $("#system_details").val());
        form_data.append("system_copy_right", $("#system_copy_right").val());
        form_data.append("system_version", $("#system_version").val());
        form_data.append("hidden_system_logo", $("#hidden_system_logo").val());
        form_data.append("hidden_system_icon", $("#hidden_system_icon").val());
        form_data.append("hidden_system_bg_image", $("#hidden_system_bg_image").val());

        var system_logo = $('#system_logo')[0].files;
        form_data.append('system_logo',system_logo[0]);

        var system_icon = $('#system_icon')[0].files;
        form_data.append('system_icon',system_icon[0]);

        var system_bg_image = $('#system_bg_image')[0].files;
        form_data.append('system_bg_image',system_bg_image[0]);
    
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

                all_response(response,'{{route(request()->route()->getName())}}','System Info');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>
    

