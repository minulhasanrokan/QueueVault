<style type="text/css">
    #ad_code_div{
        display: none;
    }
</style>
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
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Upazila Name <span class="span_red">*</span></label>
                        <div id="upazila_id_container"></div>
                        <span id="upazila_id-error" class="error">Please Select Upazila Name</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Assign Service <span class="span_red">*</span></label>
                        <div id="assign_service_container"></div>
                        <span id="assign_service-error" class="error">Please Select Assign Service</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Branch Code <span class="span_red">*</span></label>
                        <input type="hidden" id="update_id" name="update_id" value="{{$single_data->id}}">
                        <input type="hidden" id="actual_status" name="actual_status" value="{{$single_data->actual_status}}">
                        <input type="hidden" id="process_status" name="process_status" value="{{$single_data->process_status}}">
                        <input type="text" class="form-control" maxlength="4" id="branch_code" name="branch_code" placeholder="Enter Branch Code" onfocusout ="check_duplicate_value('branch_code','branches',this.value,{{$single_data->id}});" value="{{$single_data->branch_code}}" tabindex="5">
                        <span id="branch_code-error" class="error">Please Enter Branch Code</span>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Branch Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="branch_name" name="branch_name" placeholder="Enter Branch Name" onfocusout ="check_duplicate_value('branch_name','branches',this.value,{{$single_data->id}});" value="{{$single_data->branch_name}}" tabindex="6">
                        <span id="branch_name-error" class="error">Please Enter Branch Name</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Branch Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="branch_title" name="branch_title" placeholder="Enter Branch Title" value="{{$single_data->branch_title}}" tabindex="7">
                        <span id="branch_title-error" class="error">Please Enter Branch Title</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>AD Branch Status <span class="span_green">*</span></label>
                        <input type="checkbox" tabindex="8" {{$single_data->ad_status==1?'Checked':''}} name="ad_status" id="ad_status" onclick="change_ad_option(this.checked);" value="{{$single_data->ad_status}}">
                        <span id="ad_status-error" class="error">Please Checked AD Branch Status</span>
                    </div>
                </div>
                <div class="col-md-4" id="ad_code_div">
                    <div class="form-group">
                        <label>AD Code <span class="span_red">*</span></label>
                        <input class="form-control" type="text" tabindex="9" name="ad_code" id="ad_code" placeholder="Enter AD Code" value="{{$single_data->ad_code}}" onfocusout ="check_duplicate_value('ad_code','branches',this.value,{{$single_data->id}});"  maxlength="4">
                        <span id="ad_code-error" class="error">Please Enter AD Code</span>
                    </div>
                </div>
                <div class="col-md-4" id="ad_branch_id_div">
                    <div class="form-group">
                        <label>AD Branch Name <span class="span_red">*</span></label>
                        <div id="ad_branch_id_id_container"></div>
                        <span id="ad_branch_id-error" class="error">Please Select AD Branch Name</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Branch Details</label>
                        <textarea class="form-control" id="branch_details" maxlength="250" name="branch_details" tabindex="11" >{!!$single_data->branch_details!!}</textarea>
                        <span id="branch_details-error" class="error">Please Enter Branch Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="12">Update Branch</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('divisions','id,division_name','division_id','division_id_container','Select Division',0,{{$single_data->division_id}},0,1,'load_drop_down(\'districts\',\'id,district_name\',\'district_id\',\'district_id_container\',\'Select District\',0,0,0,2,\'load_upazila();\',\'division_id\',this.value,0,0);load_upazila();',0,0,0,0);

    load_drop_down('districts','id,district_name','district_id','district_id_container','Select District',0,'{{$single_data->district_id}}',0,2,'load_upazila();','division_id','{{$single_data->division_id}}',0,0);

    load_drop_down('upazilas','id,upazila_name','upazila_id','upazila_id_container','Select Upazila',0,'{{$single_data->upazila_id}}',0,2,'','district_id','{{$single_data->district_id}}',0,0);

    load_drop_down('services','id,service_name','assign_service','assign_service_container','Select Assign Service',1,'{{$single_data->assign_service}}',0,4,'',0,0,0,0);

    load_drop_down('branches','id,branch_name','ad_branch_id','ad_branch_id_id_container','Select AD Branch',0,{{$single_data->ad_branch_id}},0,10,'','ad_status',1,'id',{{$single_data->id}});
    
    $('#branch_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#division_id').focus();
    });

    {{$single_data->ad_status==1?'change_ad_option(1);':'change_ad_option(0);'}}

    function change_ad_option(check_statud){

        var ad_branch_id_div = document.getElementById("ad_branch_id_div");
        var ad_code_div = document.getElementById("ad_code_div");

        if (check_statud) {

            $("#ad_status").val(1);

            ad_branch_id_div.style.display = "none";
            ad_code_div.style.display = "block";
        }
        else{

            $("#ad_status").val(0);

            ad_code_div.style.display = "none";
            ad_branch_id_div.style.display = "block";
        }

    }

    function load_upazila(){

        var district_id = $("#district_id").val();

        load_drop_down('upazilas','id,upazila_name','upazila_id','upazila_id_container','Select Upazila',0,0,0,3,'','district_id',district_id,0,0);
    }

    function validation(){

        if( form_validation('division_id#select*district_id#select*upazila_id#select*assign_service#select*branch_code#text*branch_name#text*branch_title#text','Division*District*Upazila*Assign Service*Branch Code*Branch Name*Branch Title')==false ){

            return false;
        }

        var ad_status = $("#ad_status").val();

        if(ad_status==1){

            if( form_validation('ad_code#text','AD Code')==false ){

                return false;
            }
        }
        else{
            if( form_validation('ad_branch_id#select','AD Branch Name')==false ){

                return false;
            }
        }
        
        confirm_box('Add', 'Are You Sure Want To Update Branch Info?','update_data');
    }

    function update_data(){

        var form_data = new FormData();

        form_data.append("division_id", $("#division_id").val());
        form_data.append("district_id", $("#district_id").val());
        form_data.append("upazila_id", $("#upazila_id").val());
        form_data.append("assign_service", $("#assign_service").val());
        form_data.append("branch_code", $("#branch_code").val());
        form_data.append("branch_name", $("#branch_name").val());
        form_data.append("branch_title", $("#branch_title").val());
        form_data.append("branch_details", $("#branch_details").val());
        form_data.append("ad_status", $("#ad_status").val());
        form_data.append("ad_code", $("#ad_code").val());
        form_data.append("ad_branch_id", $("#ad_branch_id").val());
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

                all_response(response,'{{route(request()->route()->getName())}}','Edit View Branch');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>