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
                        <label>Assign Service <span class="span_red">*</span></label>
                        <div id="assign_service_container"></div>
                        <span id="assign_service-error" class="error">Please Select Assign Service</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Floor Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="floor_name" name="floor_name" placeholder="Enter Floor Name" maxlength="100" onfocusout ="check_duplicate_value('floor_name','floors',this.value,0);" value="" tabindex="3" autofocus>
                        <span id="floor_name-error" class="error">Please Enter Floor Name</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Floor Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="floor_code" name="floor_code" placeholder="Enter Floor Code" onfocusout ="check_duplicate_value('floor_code','floors',this.value,0);" value="" tabindex="4">
                        <span id="floor_code-error" class="error">Please Enter Floor Code</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Floor Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="floor_title" name="floor_title" placeholder="Enter Floor Title" value="" tabindex="5">
                        <span id="floor_title-error" class="error">Please Enter Floor Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Floor Details</label>
                        <textarea class="form-control" id="floor_details" maxlength="250" name="floor_details" tabindex="6" ></textarea>
                        <span id="floor_details-error" class="error">Please Enter Floor Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="7">Add Floor</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('#floor_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#branch_id').focus();
    });

    load_drop_down('branches','id,branch_name,assign_service','branch_id','branch_id_container','Select Branch',0,0,0,1,'load_assign_service();','',0,0,0);

    blank_drop_down('assign_service','assign_service_container','Select Assign Service',0,0,2);

    function load_assign_service(){

        var assign_service = $("#hidden_field-assign_service-"+$("#branch_id").val()).val();

        if(assign_service=='undefined'){

            blank_drop_down('assign_service','assign_service_container','Select Assign Service',0,0,2);
        }
        else{

            load_drop_down('services','id,service_name','assign_service','assign_service_container','Select Assign Service',1,0,0,2,'','id',assign_service,0,0);
        }
    }

    function validation(){
        
        if( form_validation('branch_id#select*assign_service#select*floor_name#text*floor_code#text*floor_title#text','Branch*Service*Floor Name*Floor Code*Floor Title')==false ){

            return false;
        }

        confirm_box('Add', 'Are You Sure Want To Save Floor Info?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("branch_id", $("#branch_id").val());
        form_data.append("assign_service", $("#assign_service").val());
        form_data.append("floor_name", $("#floor_name").val());
        form_data.append("floor_code", $("#floor_code").val());
        form_data.append("floor_title", $("#floor_title").val());
        form_data.append("floor_details", $("#floor_details").val());
    
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

                all_response(response,'{{route('reference.floor.view')}}','View Floor');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>