<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Designation Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="designation_name" name="designation_name" placeholder="Enter Designation Name" maxlength="100" onfocusout ="check_duplicate_value('designation_name','designations',this.value,0);" value="" tabindex="1" autofocus>
                        <span id="designation_name-error" class="error">Please Enter Designation Name</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Designation Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="designation_code" name="designation_code" placeholder="Enter Designation Code" onfocusout ="check_duplicate_value('designation_code','designations',this.value,0);" value="" tabindex="2">
                        <span id="designation_code-error" class="error">Please Enter Designation Code</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Designation Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="designation_title" name="designation_title" placeholder="Enter Designation Title" value="" tabindex="3">
                        <span id="designation_title-error" class="error">Please Enter Designation Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Designation Details</label>
                        <textarea class="form-control" id="designation_details" maxlength="250" name="designation_details" tabindex="4" ></textarea>
                        <span id="designation_details-error" class="error">Please Enter Designation Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="5">Add Designation</button>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#designation_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#designation_name').focus();
    });

    function validation(){

        if( form_validation('designation_name#text*designation_code#text*designation_title#text','Designation Name*Designation Code*Designation Title')==false ){

            return false;
        }

        confirm_box('Add', 'Are You Sure Want To Save Designation Info?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("designation_name", $("#designation_name").val());
        form_data.append("designation_code", $("#designation_code").val());
        form_data.append("designation_title", $("#designation_title").val());
        form_data.append("designation_details", $("#designation_details").val());
    
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

                all_response(response,'{{route('reference.designation.view')}}','View Branch');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>