<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Department Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="department_name" name="department_name" placeholder="Enter Department Name" maxlength="100" onfocusout ="check_duplicate_value('department_name','departments',this.value,0);" value="" tabindex="1" autofocus>
                        <span id="department_name-error" class="error">Please Enter Department Name</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Department Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="department_code" name="department_code" placeholder="Enter Department Code" onfocusout ="check_duplicate_value('department_code','departments',this.value,0);" value="" tabindex="2">
                        <span id="department_code-error" class="error">Please Enter Department Code</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Department Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="department_title" name="department_title" placeholder="Enter Department Title" value="" tabindex="3">
                        <span id="department_title-error" class="error">Please Enter Department Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Department Details</label>
                        <textarea class="form-control" id="department_details" maxlength="250" name="department_details" tabindex="4" ></textarea>
                        <span id="department_details-error" class="error">Please Enter Department Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="5">Add Department</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#department_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#department_name').focus();
    });

    function validation(){

        if( form_validation('department_name#text*department_code#text*department_title#text','Department Name*Department Code*Department Title')==false ){

            return false;
        }

        confirm_box('Add', 'Are You Sure Want To Save Department Info?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("department_name", $("#department_name").val());
        form_data.append("department_code", $("#department_code").val());
        form_data.append("department_title", $("#department_title").val());
        form_data.append("department_details", $("#department_details").val());
    
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

                all_response(response,'{{route('reference.department.view')}}','View Department');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>