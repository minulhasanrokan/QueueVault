<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Branch Name <span class="span_red">*</span></label>
                        <div id="branch_id_container"></div>
                        <span id="branch_id-error" class="error">Please Select Branch Name</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Floor Name <span class="span_red">*</span></label>
                        <div id="floor_id_container"></div>
                        <span id="floor_id-error" class="error">Please Select Floor Name</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Assign Service <span class="span_red">*</span></label>
                        <div id="assign_service_container"></div>
                        <span id="assign_service-error" class="error">Please Select Assign Service</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Counter Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="counter_name" name="counter_name" placeholder="Enter Counter Name" maxlength="100" value="" tabindex="4" autofocus>
                        <span id="counter_name-error" class="error">Please Enter Counter Name</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Counter Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="counter_code" name="counter_code" placeholder="Enter Counter Code" value="" onfocusout ="check_duplicate_value('counter_code','counters',this.value,0);"  tabindex="5">
                        <span id="counter_code-error" class="error">Please Enter Counter Code</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Counter Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="counter_title" name="counter_title" placeholder="Enter Counter Title" value="" tabindex="6">
                        <span id="counter_title-error" class="error">Please Enter Counter Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Counter Details</label>
                        <textarea class="form-control" id="counter_details" maxlength="250" name="counter_details" tabindex="7" ></textarea>
                        <span id="counter_details-error" class="error">Please Enter Counter Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="8">Add Counter</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('branches','id,branch_name','branch_id','branch_id_container','Select Branch',0,0,0,1,'load_floor();',0,0,0,0);
    
    blank_drop_down('floor_id','floor_id_container','Select Floor',0,0,2);
    blank_drop_down('assign_service','assign_service_container','Select Assign Service',0,0,3);
    
    $('#counter_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#branch_id').focus();
    });

    function load_floor(){

        load_drop_down('floors','id,floor_name,assign_service','floor_id','floor_id_container','Select Floor',0,0,0,2,'load_assign_service();','branch_id',$("#branch_id").val(),0,0);

        blank_drop_down('assign_service','assign_service_container','Select Assign Service',0,0,3);
    }

    function load_assign_service(){

        var assign_service = $("#hidden_field-assign_service-"+$("#floor_id").val()).val();

        if(assign_service=='undefined'){

            blank_drop_down('assign_service','assign_service_container','Select Assign Service',0,0,3);
        }
        else{

            load_drop_down('services','id,service_name','assign_service','assign_service_container','Select Assign Service',1,0,0,3,'','id',assign_service,0,0);
        }
    }

    function validation(){
        
        if( form_validation('branch_id#select*floor_id#select*assign_service#select*counter_name#text*counter_code#text*counter_title#text','Branch*FLoor*Service*Counter Name*Counter Code*Counter Title')==false ){

            return false;
        }

        if(check_combain_duplicate_value('branch_id*floor_id*counter_name','counters',0,'counter_name')==false ){

            return false;
        }

        confirm_box('Add', 'Are You Sure Want To Save Counter Info?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("branch_id", $("#branch_id").val());
        form_data.append("floor_id", $("#floor_id").val());
        form_data.append("assign_service", $("#assign_service").val());
        form_data.append("counter_name", $("#counter_name").val());
        form_data.append("counter_code", $("#counter_code").val());
        form_data.append("counter_title", $("#counter_title").val());
        form_data.append("counter_details", $("#counter_details").val());
    
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

                all_response(response,'{{route('reference.counter.view')}}','View Counter');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>