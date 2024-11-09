<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Service Name <span class="span_red">*</span></label>
                        <input type="hidden" id="update_id" name="update_id" value="{{$single_data->id}}">
                        <input type="hidden" id="actual_status" name="actual_status" value="{{$single_data->actual_status}}">
                        <input type="hidden" id="process_status" name="process_status" value="{{$single_data->process_status}}">
                        <input type="text" class="form-control" id="service_name" name="service_name" placeholder="Enter Service Name" maxlength="100" onfocusout ="check_duplicate_value('service_name','services',this.value,{{$single_data->id}});" value="{{$single_data->service_name}}" tabindex="1" autofocus>
                        <span id="service_name-error" class="error">Please Enter Service Name</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Service Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="service_code" name="service_code" placeholder="Enter Service Code" onfocusout ="check_duplicate_value('service_code','services',this.value,{{$single_data->id}});" value="{{$single_data->service_code}}" tabindex="2">
                        <span id="service_code-error" class="error">Please Enter Service Code</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Service Letter <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="3" id="service_letter" name="service_letter" placeholder="Enter Service Letter" value="{{$single_data->service_letter}}" tabindex="3">
                        <span id="service_letter-error" class="error">Please Enter Service Letter</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Starting Number <span class="span_red">*</span></label>
                        <input type="text" class="form-control text_boxes_numeric" maxlength="4" id="service_start_number" name="service_start_number" placeholder="Enter Starting Number" value="{{$single_data->service_start_number}}" tabindex="4">
                        <span id="service_start_number-error" class="error">Please Enter Starting Number</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Service Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="service_title" name="service_title" placeholder="Enter Service Title" value="{{$single_data->service_title}}" tabindex="4">
                        <span id="service_title-error" class="error">Please Enter Service Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Service Details</label>
                        <textarea class="form-control" id="service_details" maxlength="250" name="service_details" tabindex="6" >{!!$single_data->service_details!!}</textarea>
                        <span id="service_details-error" class="error">Please Enter Service Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="7">Update Service</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#service_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#service_name').focus();
    });

    function validation(){

        if( form_validation('service_name#text*service_code#text*service_letter#alpha*service_start_number#number*service_title#text','Service Name*Service Code*Service Letter*Service Starting Number*Service Title')==false ){

            return false;
        }

        confirm_box('Edit', 'Are You Sure Want To Update Service Info?','update_data');
    }

    function update_data(){

        var form_data = new FormData();

        form_data.append("service_name", $("#service_name").val());
        form_data.append("service_code", $("#service_code").val());
        form_data.append("service_letter", $("#service_letter").val());
        form_data.append("service_start_number", $("#service_start_number").val());
        form_data.append("service_title", $("#service_title").val());
        form_data.append("service_details", $("#service_details").val());
        form_data.append("update_id", $("#update_id").val());
        form_data.append("actual_status", $("#actual_status").val());
        form_data.append("process_status", $("#process_status").val());
    
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

                all_response(response,'{{route(request()->route()->getName())}}','Edit View Service');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>