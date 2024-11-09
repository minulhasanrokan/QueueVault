<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Floor Name <span class="span_red">*</span></label>
                        <div id="floor_id_container"></div>
                        <span id="floor_id-error" class="error">Please Select Floor Name</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Counter <span class="span_red">*</span></label>
                        <div id="counter_id_container"></div>
                        <span id="counter_id-error" class="error">Please Select Counter</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" tabindex="3">Release Counter</button>
        </div>
    </div>
</div>

<script type="text/javascript">

    load_drop_down('floors','id,floor_name,assign_service','floor_id','floor_id_container','Select Floor',0,0,0,1,'load_counter();','branch_id','{{ltrim($branch_id,",")}}',0,0);

    blank_drop_down('counter_id','counter_id_container','Select Counter',0,0,2);

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#floor_id').focus();
    });

    function load_counter(){

        load_drop_down('counters','id,counter_name','counter_id','counter_id_container','Select Counter',1,0,0,2,'','floor_id',$("#floor_id").val(),0,0);
    }

    function validation(){

        if( form_validation('floor_id#select*counter_id#select','Floor*Counter')==false ){
            
            return false;
        }

        confirm_box('Load Token', 'Are You Sure Want To Release Counter?','release');
    }

    function release(){

        var form_data = new FormData();

        form_data.append("floor_id", $("#floor_id").val());
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

                all_response(response,'{{route(request()->route()->getName())}}','Release Counter');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

</script>