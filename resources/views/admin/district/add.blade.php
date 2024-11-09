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
                        <div id="division_id_container"></div>
                        <span id="division_id-error" class="error">Please Select Division Name</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>District Name <span class="span_red">*</span></label>
                        <input type="text" class="form-control" id="district_name" name="district_name" placeholder="Enter District Name" maxlength="100" onfocusout ="check_duplicate_value('district_name','districts',this.value,0);" value="" tabindex="2" autofocus>
                        <span id="district_name-error" class="error">Please Enter District Name</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>District Code <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="4" id="district_code" name="district_code" placeholder="Enter District Code" onfocusout ="check_duplicate_value('district_code','districts',this.value,0);" value="" tabindex="3">
                        <span id="district_code-error" class="error">Please Enter District Code</span>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>District Title <span class="span_red">*</span></label>
                        <input type="text" class="form-control" maxlength="100" id="district_title" name="district_title" placeholder="Enter District Title" value="" tabindex="4">
                        <span id="district_title-error" class="error">Please Enter District Title</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>District Details</label>
                        <textarea class="form-control" id="district_details" maxlength="250" name="district_details" tabindex="5" ></textarea>
                        <span id="district_details-error" class="error">Please Enter District Details</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="6">Add District</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('divisions','id,division_name','division_id','division_id_container','Select Division',0,0,0,1,'',0,0,0,0);
    
    $('#district_details').summernote({
        height: 170,
        tabDisable: false
    });

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#division_id').focus();
    });

    function validation(){
        
        if( form_validation('division_id#select*district_name#text*district_code#text*district_title#text','Division*District Name*District Code*District Title')==false ){

            return false;
        }

        confirm_box('Add', 'Are You Sure Want To Save District Info?','add_data');
    }

    function add_data(){

        var form_data = new FormData();

        form_data.append("division_id", $("#division_id").val());
        form_data.append("district_name", $("#district_name").val());
        form_data.append("district_code", $("#district_code").val());
        form_data.append("district_title", $("#district_title").val());
        form_data.append("district_details", $("#district_details").val());
    
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

                all_response(response,'{{route('reference.district.view')}}','View District');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>