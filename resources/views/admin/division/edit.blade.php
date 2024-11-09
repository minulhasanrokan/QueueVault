<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Division Name <span class="span_red">*</span></label>
                        <input type="hidden" id="update_id" name="update_id" value="{{$single_data->id}}">
                        <input type="hidden" id="actual_status" name="actual_status" value="{{$single_data->actual_status}}">
                        <input type="hidden" id="process_status" name="process_status" value="{{$single_data->process_status}}">
                        <input type="text" class="form-control" id="division_name" name="division_name" placeholder="Enter Division Name" maxlength="100" onfocusout ="check_duplicate_value('division_name','divisions',this.value,{{$single_data->id}});" value="{{$single_data->division_name}}" tabindex="1" autofocus>
                        <span id="division_name-error" class="error">Please Enter Division Name</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Division Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="division_code" name="division_code" placeholder="Enter Division Code" onfocusout ="check_duplicate_value('division_code','divisions',this.value,{{$single_data->id}});" value="{{$single_data->division_code}}" tabindex="2">
                        <span id="division_code-error" class="error">Please Enter Division Code</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Division Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="division_title" name="division_title" placeholder="Enter Division Title" value="{{$single_data->division_title}}" tabindex="3">
                        <span id="division_title-error" class="error">Please Enter Division Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Division Details</label>
                        <textarea class="form-control" id="division_details" maxlength="250" name="division_details" tabindex="4" >{!!$single_data->division_details!!}</textarea>
                        <span id="division_details-error" class="error">Please Enter Division Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="5">Update Division</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#division_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#division_name').focus();
    });

    function validation(){

        if( form_validation('division_name#text*division_code#text*division_title#text','Division Name*Division Code*Division Title')==false ){

            return false;
        }

        confirm_box('Edit', 'Are You Sure Want To Update Division Info?','update_data');
    }

    function update_data(){

        var form_data = new FormData();

        form_data.append("division_name", $("#division_name").val());
        form_data.append("division_code", $("#division_code").val());
        form_data.append("division_title", $("#division_title").val());
        form_data.append("division_details", $("#division_details").val());
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

                all_response(response,'{{route(request()->route()->getName())}}','Edit View Division');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>