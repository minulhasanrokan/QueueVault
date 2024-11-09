<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Division Name <span class="span_red">*</span></label>
                        <div id="division_id_container"></div>
                        <span id="division_id-error" class="error">Please Select Division Name</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>District Name <span class="span_red">*</span></label>
                        <div id="district_id_container"></div>
                        <span id="district_id-error" class="error">Please Select District Name</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upazila Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="upazila_name" name="upazila_name" placeholder="Enter Upazila Name" maxlength="100" onfocusout ="check_duplicate_value('upazila_name','upazilas',this.value,0);" value="" tabindex="3" autofocus>
                        <span id="upazila_name-error" class="error">Please Enter Upazila Name</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Upazila Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="upazila_code" name="upazila_code" placeholder="Enter Upazila Code" onfocusout ="check_duplicate_value('upazila_code','upazilas',this.value,0);" value="" tabindex="4">
                        <span id="upazila_code-error" class="error">Please Enter Upazila Code</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Upazila Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="upazila_title" name="upazila_title" placeholder="Enter Upazila Title" value="" tabindex="5">
                        <span id="upazila_title-error" class="error">Please Enter Upazila Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Upazila Details</label>
                        <textarea class="form-control" id="upazila_details" maxlength="250" name="upazila_details" tabindex="6" ></textarea>
                        <span id="upazila_details-error" class="error">Please Enter Upazila Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="7">Add Upazila</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('divisions','id,division_name','division_id','division_id_container','Select Division',0,0,0,1,"load_drop_down('districts','id,district_name','district_id','district_id_container','Select District',0,0,0,2,'','division_id',this.value,0,0);",0,0,0,0);

    blank_drop_down('district_id','district_id_container','Select District',0,0,2);
    
    $('#upazila_details').summernote({
        height: 170,
        tabDisable : false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#division_id').focus();
    });

    function validation(){
        
        if( form_validation('division_id#select*district_id#select*upazila_name#text*upazila_code#text*upazila_title#text','Division*District*Upazila Name*Upazila Code*Upazila Title')==false ){

            return false;
        }

        confirm_box('Add', 'Are You Sure Want To Save Upazila Info?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("division_id", $("#division_id").val());
        form_data.append("district_id", $("#district_id").val());
        form_data.append("upazila_name", $("#upazila_name").val());
        form_data.append("upazila_code", $("#upazila_code").val());
        form_data.append("upazila_title", $("#upazila_title").val());
        form_data.append("upazila_details", $("#upazila_details").val());
    
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

                all_response(response,'{{route('reference.upazila.view')}}','View Upazila');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>