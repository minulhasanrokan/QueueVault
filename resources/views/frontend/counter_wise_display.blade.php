<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Display | {{$system_data['system_title']}}</title>
        <link rel="icon" type="image/x-icon" href="{{asset('uploads/system')}}/{{$system_data['system_icon']}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/adminlte.css')}}">
        <link rel="stylesheet" href="{{asset('assets/plugins/jquery-ui/jquery-ui.min.css')}}">

        <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('assets/js/adminlte.js')}}"></script>

        <script src="{{asset('assets/js/common.js')}}"></script>
        <script src="{{asset('assets/plugins/jquery-ui/jquery-ui.min.js')}}"></script>

        <style type="text/css">

            .main-footer{
                margin: 0px !important;
            }

            #page_content{
                padding: 10px;
            }

            .brand-link{
                border: 0px !important;
                overflow: inherit !important;
                margin-top: 5px !important;
            }

            .navbar{
                border-bottom: 1px solid #007bff;
            }

            .counter_1{

                font-size: 30px;
                argin: 0px;
                text-align:center;
            }

            .counter_2{

                font-size: 30px;
                color: red;
                font-weight: bold;
                line-height: 1.2;
                text-align:center;
            }

            .counter_3{

                font-size: 30px;
                color: orange;
                font-weight: bold;
                text-align:center;
            }

            .counter_4{

                font-size: 30px;
                color: red;
                line-height: 1.2;
                text-align: center;
            }

            .row{
                margin: 0px !important;
            }

            .next_process{

                font-size: 30px;
                color: red;
                font-weight: bold;
                line-height: 1.15;
                text-align:center;
            }

            .background_red{

                background: #007bff;
            }

            .staticBackdrop_modal{
                top: 25%;
            }

            #modal_body{
                padding: 70px;
                text-align: center;
            }

        </style>
    </head>
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog staticBackdrop_modal" role="document" id="modal_dialog">
            <div class="modal-content" id="modal_content">
                <div class="modal-body" id="modal_body">
                    <h2 id="modal_token_number"></h2>
                    <h2 id="modal_counter_number"></h2>
                </div>
            </div>
        </div>
    </div>
    <body  class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <div class="wrapper">
            <nav class="navbar navbar-expand navbar-light">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <aside class="sidebar-light-primary">
                <div>
                    <div class="d-flex">
                            <a href="{{url('')}}" class="brand-link">
                            <img src="{{asset('uploads/system')}}/{{$system_data['system_logo']}}" alt="{{$system_data['system_title']}}" class="brand-image img-circle">
                            <span class="brand-text font-weight-light">{{$system_data['system_name']}}</span>
                        </a>
                    </div>
                </div>
            </aside>
            <div>
                <section class="content" id="page_content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <div>
                                    <div class="row">
                                        @php
                                            $total_counter = 0;

                                            $counter_arr = '';
                                        @endphp
                                        
                                        @foreach($counter_data as $counter)

                                            @php
                                                $total_counter++;

                                                $counter_arr .=$counter->id.': '.$counter->id.',';
                                            @endphp

                                            <div class="col-md-3 p-1">
                                                <div class="card card-primary">
                                                    <div class="card-header">
                                                        <h3 class="card-title" id="content_name_{{$counter->id}}">{{$counter->counter_name}}</h3>
                                                    </div>
                                                    <div class="card-body">
                                                       <div class="counter_1" style="font-size: 30px; margin: 0px; text-align:center;">Token Number</div>
                                                       <div class="counter_2" id="token_number_{{$counter->id}}">NIL</div>
                                                       <div class="counter_3" id="token_serving_{{$counter->id}}">NIL</div>
                                                       <div class="counter_1">Please proceed to</div>
                                                       <div class="counter_4" id="token_counter_{{$counter->id}}">NIL</div>
                                                       <input class="counter_inputvalue" type="hidden" name="current_call_status_{{$counter->id}}" id="current_call_status_{{$counter->id}}" value="0">
                                                       <input class="counter_inputvalue" type="hidden" name="pre_call_status_{{$counter->id}}" id="pre_call_status_{{$counter->id}}" value="0">
                                                       <input type="hidden" name="token_id_{{$counter->id}}" id="token_id_{{$counter->id}}" value="{{$counter->id}}">
                                                       <input type="hidden" name="counter_time_{{$counter->id}}" id="counter_time_{{$counter->id}}" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @for($total_counter; $total_counter<8; $total_counter++)
                                            <div class="col-md-3 p-1">
                                                <div class="card card-primary">
                                                    <div class="card-header">
                                                        <h3 class="card-title">Empty Counter</h3>
                                                    </div>
                                                    <div class="card-body">
                                                       <div class="counter_1" style="font-size: 30px; margin: 0px; text-align:center;">Token Number</div>
                                                       <div class="counter_2">NIL</div>
                                                       <div class="counter_3">NIL</div>
                                                       <div class="counter_1">Please proceed to</div>
                                                       <div class="counter_4">NIL</div>
                                                       <input type="hidden" name="current_call_status" id="current_call_status" value="0">
                                                       <input type="hidden" name="pre_call_status" id="pre_call_status" value="0">
                                                       <input type="hidden" name="token_id" id="token_id" value="0">
                                                       <input type="hidden" name="counter_time" id="counter_time" value="">
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div>
                                    <div class="row">
                                        <div class="col-md-12 p-1">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Next To Process</h3>
                                                </div>
                                                <div class="card-body" id="next_process_div"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>                          
                        </div>
                    </div>
                </section>
            </div>
            <footer class="main-footer">
                <strong>{{$system_data['system_copy_right']}}</strong>
                <div class="float-right d-none d-sm-inline-block">
                  <b>Version</b> {{$system_data['system_version']}}
                </div>
            </footer>
        </div>
        <audio id="called_sound" style="display: none;">
            <source src="{{asset('assets/sound/sound.mp3')}}" type="audio/mpeg">
        </audio>
    </body>
    <script type="text/javascript">

        var counter_arr = {@php echo $counter_arr; @endphp}

        function call_token() {
            
            $.ajax({
                async: false,
                url: "{{route(request()->route()->getName())}}",
                type: "POST",
                data: {
                    branch_id: '{{ltrim($token_data[0], ',')}}',
                    floor_id: '{{ltrim($token_data[1], ',')}}',
                    service_id: '{{ltrim($token_data[2], ',')}}',
                    counter_id: '{{ltrim($token_data[3], ',')}}',
                    type: 'call_token',
                },
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(response) {

                    var data = response;

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);

                    if(data.alert_type=='success'){

                        $(".counter_2").html('NIL');
                        $(".counter_3").html('NIL');
                        $(".counter_4").html('NIL');
                        //$(".counter_inputvalue").val(0);

                        data.data.forEach(function(row) {

                            $("#token_number_"+row.counter_id).html(row.token_number);
                            $("#token_serving_"+row.counter_id).html('SERVING');

                            var counter_name = document.getElementById('content_name_' + row.counter_id);

                            $("#token_counter_"+row.counter_id).html(counter_name.textContent);

                            var counter_time = $("#counter_time_"+row.counter_id).val();
                            var token_id = $("#token_id_"+row.counter_id).val();

                            $("#counter_time_"+row.counter_id).val(row.counter_time);
                            $("#current_call_status_"+row.counter_id).val(1);
                            $("#pre_call_status_"+row.counter_id).val(0);
                            $("#token_id_"+row.counter_id).val(row.id);

                            if(counter_time!=row.counter_time || token_id!=row.id){

                                var counter_name = document.getElementById('content_name_' + row.counter_id);

                                playToken(row.token_number,counter_name.textContent);
                            }
                        });
                    }
                    else{

                        response_error_alert();
                    }
                },
                error: function(xhr, status, error) {

                    response_error_alert();
                }
            });
        }

        call_token();

        setInterval(call_token, 3000);
        
        function get_token_data(){
        
            $.ajax({
                async: false,
                url: "{{route(request()->route()->getName())}}",
                type: "POST",
                data: {
                    branch_id: '{{ltrim($token_data[0], ',')}}',
                    floor_id: '{{ltrim($token_data[1], ',')}}',
                    service_id: '{{ltrim($token_data[2], ',')}}',
                    counter_id: '{{ltrim($token_data[3], ',')}}',
                    type: 'token_list',
                },
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(response) {

                    var data = response;

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);

                    if(data.alert_type=='success'){

                        $("#next_process_div").html('');

                        var sl = 1;

                        var next_process_div_data = "";

                        data.data.forEach(function(row) {

                            if(sl<=8){

                                if(next_process_div_data!=''){

                                   next_process_div_data += '<hr class="background_red">';
                                }

                                next_process_div_data += '<div class="next_process">'+row.token_number+'</div>';
                            }

                            sl++;
                        });

                        $("#next_process_div").html(next_process_div_data);
                    }
                    else{

                        response_error_alert();
                    }
                },
                error: function(xhr, status, error) {

                    response_error_alert();
                }
            });
        }

        function playToken(token,counter_name) {

            $("#modal_token_number").html(token);
            $("#modal_counter_number").html(counter_name);
            $('#staticBackdrop #modal_dialog').css('max-width', '300px');

            $('#staticBackdrop').modal('show');

            var [prefix, number] = token.split('-');

            var msg = new SpeechSynthesisUtterance();

            msg.text = `token number ${prefix} ${number.split('').join(' ')} ${counter_name}`;

            var called_sound = document.getElementById("called_sound");

            called_sound.play();

            called_sound.onended = function() {
                window.speechSynthesis.speak(msg);

                msg.onend = function() {
                    $('#staticBackdrop').modal('hide');
                };
            };
        }

        get_token_data();

        setInterval(get_token_data, 3000);

    </script>
</html>