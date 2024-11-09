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
                        <label>Service <span class="span_red">*</span></label>
                        <div id="service_id_container"></div>
                        <span id="service_id-error" class="error">Please Select Service</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Counter <span class="span_red">*</span></label>
                        <div id="counter_id_container"></div>
                        <span id="counter_id-error" class="error">Please Select Counter</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label></label>
                        <div class="input-group">
                            <button type="button" id="load" onclick="validation();" class="btn btn-primary ml-2" tabindex="3">Load</button>
                            <button type="button" id="cancel" onclick="load_counter_data('cancel');" class="btn btn-primary ml-2" tabindex="4">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-9" style="text-align: center; margin-top: 50px;">
                    <span style="font-size: 115px;" id="token_number">NIL</span>
                    <input type="hidden" name="token_id" id="token_id" value="0">
                    <div style="font-size: 30px;" id="waiting_time">&nbsp;</div>
                    <div style="font-size: 30px;" id="token_time">&nbsp;</div>
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="input-field col s6">
                                <button disabled id="served" onclick="load_counter_data('served')" class="btn btn-primary flot_right" style="min-width: 100px;">
                                    Served <i class="fas fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="input-field col s6">
                                <button disabled id="recall" onclick="load_counter_data('recall')" class="btn btn-primary flot_left" style="min-width: 100px;">
                                    Recall <i class="fas fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="input-field col s6">
                                <button disabled id="noshow" onclick="load_counter_data('noshow')" class="btn btn-primary flot_right" style="min-width: 100px;">
                                    No Show <i class="fas fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <div class="input-field col s6">
                                <button disabled id="callnext" onclick="load_counter_data('callnext')" id="next_call" class="btn btn-primary flot_left" style="min-width: 100px;">
                                    Call Next <i class="fas fa-solid fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div>
                        <div class="row">
                            <div class="col-md-12 p-1">
                                <div class="card card-primary">
                                    <div class="card-header">
                                        <h3 class="card-title">Next To Call</h3>
                                    </div>
                                    <div class="card-body" id="next_process_div" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    
    .flot_left{

        float: left;
    }

    .flot_right{

        float: right;
    }

    .next_process{
        text-align:center;
    }

    .background_red{

        background: #007bff;
    }
</style>

<script type="text/javascript">

    $("#cancel").prop("disabled", true);

    load_drop_down('floors','id,floor_name,assign_service','floor_id','floor_id_container','Select Floor',0,0,0,1,'load_service_id();','branch_id','{{ltrim($branch_id,",")}}',0,0);

    blank_drop_down('service_id','service_id_container','Select Service',0,0,2);
    blank_drop_down('counter_id','counter_id_container','Select Counter',0,0,3);

    $('#staticBackdrop').on('shown.bs.modal', function () {
        $('#service_id').focus();
    });

    function load_service_id(){

        var service_id = $("#hidden_field-assign_service-"+$("#floor_id").val()).val();

        if(service_id=='undefined'){

            blank_drop_down('service_id','service_id_container','Select Service',0,0,2);
        }
        else{

            load_drop_down('services','id,service_name','service_id','service_id_container','Select Service',1,0,0,2,'load_counter();','id',service_id,0,0);
        }

        blank_drop_down('counter_id','counter_id_container','Select Counter',0,0,3);
    }

    function load_counter(){

        load_drop_down('counters','id,counter_name','counter_id','counter_id_container','Select Counter',0,0,0,2,'','floor_id',$("#floor_id").val(),0,0);
    }

    function validation(){

        if( form_validation('floor_id#select*service_id#select*counter_id#select','Floor*Service*Counter')==false ){
            
            return false;
        }

        confirm_box('Load Token', 'Are You Sure Want To Load Token?','load_counter_data');
    }

    function load_counter_data(type='load'){

        $("#callnext").prop("disabled", false);

        $("#next_process_div").html('');
        $("#token_number").html('NIL');
        $("#token_time").html('&nbsp;');

        var form_data = new FormData();

        form_data.append("branch_id", '{{ltrim($branch_id,",")}}');
        form_data.append("floor_id", $("#floor_id").val());
        form_data.append("service_id", $("#service_id").val());
        form_data.append("counter_id", $("#counter_id").val());
        form_data.append("token_id", $("#token_id").val());
        form_data.append("type", type);

        showLoading();
    
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

                if(response=='Session Expire' || response=='Right Not Found'){

                    alert(response);

                    location.replace(base_url+'/logout');
                }
                else if(response=='lock'){

                    alert(response);

                    location.replace(base_url+'/lock');
                }
                else{

                    var data = response;

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);

                    switch(data.alert_type){

                        case 'info':
                        toastr.info(data.message);
                        break;

                        case 'success':
                        toastr.success(data.message);
                        break;

                        case 'warning':
                        toastr.warning(data.message);
                        break;

                        case 'error':
                        toastr.error(data.message);
                        break; 
                    }

                    if(data.alert_type=='success'){

                        stopTimer();

                        if(type=='load'){

                            $("#cancel").prop("disabled", false);
                            $("#load").prop("disabled", true);

                            $("#served").prop("disabled", true);
                            $("#recall").prop("disabled", true);
                            $("#noshow").prop("disabled", true);
                            $("#callnext").prop("disabled", false);
                        }
                        else if(type=='callnext'){

                            $("#served").prop("disabled", false);
                            $("#recall").prop("disabled", false);
                            $("#noshow").prop("disabled", false);
                            $("#callnext").prop("disabled", true);
                        }
                        else if(type=='recall'){

                            $("#served").prop("disabled", false);
                            $("#recall").prop("disabled", false);
                            $("#noshow").prop("disabled", false);
                            $("#callnext").prop("disabled", true);
                        }
                        else if(type=='served'){

                            $("#served").prop("disabled", true);
                            $("#recall").prop("disabled", true);
                            $("#noshow").prop("disabled", true);
                            $("#callnext").prop("disabled", false);
                        }
                        else if(type=='noshow'){

                            $("#served").prop("disabled", true);
                            $("#recall").prop("disabled", true);
                            $("#noshow").prop("disabled", true);
                            $("#callnext").prop("disabled", false);
                        }
                        else if(type=='cancel'){

                            $("#cancel").prop("disabled", true);
                            $("#load").prop("disabled", false);

                            stopTimer();

                            $("#served").prop("disabled", true);
                            $("#recall").prop("disabled", true);
                            $("#noshow").prop("disabled", true);
                            $("#callnext").prop("disabled", true);

                            $("#token_number").html('NIL');
                            $("#token_time").html('&nbsp;');
                            $("#waiting_time").html('&nbsp;');

                            $("#token_id").val(0);
                        }

                        if(data.call_next.data==undefined){

                            $("#token_number").html('NIL');
                            $("#token_time").html('&nbsp;');
                            $("#waiting_time").html('&nbsp;');
                            $("#token_id").val(0);

                            $("#served").prop("disabled", true);
                            $("#recall").prop("disabled", true);
                            $("#noshow").prop("disabled", true);
                            $("#callnext").prop("disabled", false);
                        }
                        else if(data.call_next.data['id']!='undefined'){

                            $("#served").prop("disabled", false);
                            $("#recall").prop("disabled", false);
                            $("#noshow").prop("disabled", false);
                            $("#callnext").prop("disabled", true);

                            $("#token_number").html(data.call_next.data['token_number']);
                            $("#token_time").html(data.call_next.data['counter_time']);
                            $("#waiting_time").html(data.call_next.data['waiting_time']);

                            $("#token_id").val(data.call_next.data['id']);

                            startTimer(data.call_next.data['counter_time']);
                        }
                        else{

                            $("#token_number").html('NIL');
                            $("#token_time").html('&nbsp;');
                            $("#waiting_time").html('&nbsp;');

                            $("#token_id").val(0);
                        }

                        var sl = 1;

                        var next_process_div_data = "";

                        if (Array.isArray(data.data)) {

                            data.data.forEach(function(row) {

                                if(sl<=7){

                                    if(next_process_div_data!=''){

                                       next_process_div_data += '<hr class="background_red">';
                                    }

                                    next_process_div_data += '<div class="next_process">'+row.token_number+'</div>';
                                }

                                sl++;
                            });
                        }

                        $("#next_process_div").html(next_process_div_data);
                    }
                    else{

                        stopTimer();

                        $("#served").prop("disabled", true);
                        $("#recall").prop("disabled", true);
                        $("#noshow").prop("disabled", true);
                        $("#callnext").prop("disabled", true);

                        $("#token_number").html('NIL');
                        $("#token_time").html('&nbsp;');
                        $("#waiting_time").html('&nbsp;');

                        $("#token_id").val(0);
                    }
                }

                hideLoading(1000);
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }

    var timerInterval;

    function startTimer(initialTime) {

        var [hours, minutes, seconds] = initialTime.split(':').map(Number);

        function updateTime() {

            seconds++;

            if (seconds >= 60) {
                seconds = 0;
                minutes++;
            }
            if (minutes >= 60) {
                minutes = 0;
                hours++;
            }

            let timeString = 
                (hours < 10 ? "0" + hours : hours) + ":" +
                (minutes < 10 ? "0" + minutes : minutes) + ":" +
                (seconds < 10 ? "0" + seconds : seconds);

            document.getElementById("token_time").textContent = timeString;
        }

        timerInterval = setInterval(updateTime, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);

        $("#token_time").html('&nbsp;');
    }

    stopTimer();

</script>