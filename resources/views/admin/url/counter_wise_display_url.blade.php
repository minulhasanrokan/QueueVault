<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Branch Name <span class="span_red">*</span></label>
                        <div id="branch_id_container"></div>
                        <span id="branch_id-error" class="error">Please Select Branch Name</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Floor Name <span class="span_red">*</span></label>
                        <div id="floor_id_container"></div>
                        <span id="floor_id-error" class="error">Please Select Floor Name</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Service <span class="span_red">*</span></label>
                        <div id="service_id_container"></div>
                        <span id="service_id-error" class="error">Please Select Service</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Counter <span class="span_red">*</span></label>
                        <div id="counter_id_container"></div>
                        <span id="counter_id-error" class="error">Please Select Counter</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <button type="button" id="sssssss" onclick="copy_text('display_url','input');" class="btn btn-primary">Copy Display Url</button>
                            <input type="text" readonly id="display_url" name="display_url" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="5">Create Url</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('branches','id,branch_name','branch_id','branch_id_container','Select Branch',0,0,0,1,'load_floor();',0,0,0,0);
    
    blank_drop_down('floor_id','floor_id_container','Select Floor',0,0,2);
    blank_drop_down('service_id','service_id_container','Select Service',0,0,3);
    blank_drop_down('counter_id','counter_id_container','Select Counter',0,0,4);

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#branch_id').focus();
    });

    function load_floor(){

        load_drop_down('floors','id,floor_name,assign_service','floor_id','floor_id_container','Select Floor',0,0,0,2,'load_service_id();','branch_id',$("#branch_id").val(),0,0);

        blank_drop_down('service_id','service_id_container','Select Service',0,0,3);
        blank_drop_down('counter_id','counter_id_container','Select Counter',0,0,4);
    }

    function load_service_id(){

        var service_id = $("#hidden_field-assign_service-"+$("#floor_id").val()).val();

        if(service_id=='undefined'){

            blank_drop_down('service_id','service_id_container','Select Service',0,0,3);
        }
        else{

            load_drop_down('services','id,service_name','service_id','service_id_container','Select Service',1,0,0,3,'load_counter();','id',service_id,0,0);
        }

        blank_drop_down('counter_id','counter_id_container','Select Counter',0,0,4);
    }

    function load_counter(){

        load_drop_down('counters','id,counter_name','counter_id','counter_id_container','Select Counter',1,0,0,4,'','floor_id',$("#floor_id").val(),0,0);
    }

    function validation(){

        if( form_validation('branch_id#select*floor_id#select*service_id#select*counter_id#select','Branch*Floor*Service*Counter')==false ){
            
            return false;
        }

        var counter = $("#counter_id").val();

        var stringCounter = String(counter);
        var result = stringCounter.split(',');

        if(result.length>8){

            $("#counter_id-error").html("Maximum Counter Limit 8");
            $("#counter_id-error").show();
            $(".error").delay(800).fadeOut(800);

            return false;
        }

        confirm_box('Create Url', 'Are You Sure Want To Create Display Url?','create_url');
    }

    function create_url(){

        $("#display_url").val('');

        var form_data = new FormData();

        form_data.append("branch_id", $("#branch_id").val());
        form_data.append("floor_id", $("#floor_id").val());
        form_data.append("service_id", $("#service_id").val());
        form_data.append("counter_id", $("#counter_id").val());
    
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

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    var data = response;

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);

                    if (data.errors && data.success==false) {

                        $.each(data.errors, function(field, errors) {

                            $("#" + field).css("border-color", "red");
                            $("#" +field+ "-error").html(errors);
                            $("#" + field + "-error").show();
                        });
                    }
                    else{

                        switch(data.alert_type){

                            case 'info':
                            toastr.info(data.message);
                            break;

                            case 'success':
                            toastr.success(data.message);
                            break;

                            case 'warning':
                            toastr.warning(data.message);
                            break;

                            case 'error':
                            toastr.error(data.message);
                            break; 
                        }

                        if(data.alert_type=='success'){

                            $("#display_url").val(data.url);
                        }
                    }

                    setTimeout(function() {

                        $(".form-control").css("border-color", "");
                    }, 3000);

                    $(".error").delay(3000).fadeOut(800);
                }

                hideLoading(1000);
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>