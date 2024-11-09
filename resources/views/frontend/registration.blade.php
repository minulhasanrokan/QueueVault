<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Registration | {{$system_data['system_title']}}</title>
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

            .loading {
                z-index: 999999999999999;
                position: absolute;
                top: 0;
                left:-5px;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.4);
            }
            .loading-content {
                position: absolute;
                border: 16px solid #f3f3f3;
                border-top: 16px solid #3498db;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                top: 40%;
                left:50%;
                animation: spin 1s linear infinite;
            }
                  
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            @media  print {
                #loading,
                .wrapper {
                    display: none !important;
                }

                #printarea {
                    width: 3inch !important;
                    display: block !important;
                }

                body { height:0; margin: 0; text-align: center;}

                @page {
                    margin: 0.1in 0.5in 0.1in 0.5in;
                    size: letter portrait;
                }

                @page :first {
                    margin: 0.1in 0.5in 0.1in 0.5in;
                    size: letter portrait;
                }
            }

        </style>
    </head>
    <body  class="hold-transition light-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
        <section id="loading">
            <div id="loading-content"></div>
        </section>
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
                            @foreach($service_data as $data)
                                <div class="col-md-2 mt-1">
                                    <button type="button" onclick="generate_token('{{$data->id}}','{{$data->service_name}}')" class="btn btn-block btn-primary btn-lg">{{$data->service_name}}</button>
                                </div>
                            @endforeach                           
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
        <div id="printarea" class="printarea" style="text-align:center;margin-top: 20px; display:none"></div>
    </body>

    <script type="text/javascript">

        $(document).ready(function() {
            $('body').addClass('loaded');
            $('.modal').modal();
        });
        
        function generate_token(service_id,service_name) {
            
            showLoading();

            $.ajax({
                async: false,
                url: "{{route(request()->route()->getName())}}",
                type: "POST",
                data: {
                    branch_id: {{$token_data[0]!=''?$token_data[0]:'0'}},
                    floor_id: {{$token_data[1]!=''?$token_data[1]:'0'}},
                    service_id: service_id,
                },
                "beforeSend": function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(response) {

                    hideLoading(1000);

                    var data = response;

                    $('meta[name="csrf-token"]').attr('content', data.csrf_token);
                    $('input[name="_token"]').attr('value', data.csrf_token);

                    if(data.alert_type=='success'){

                        var html = '<p style="font-size: 25px; margin-top:-15px;">{{$system_data['system_title']}}</p><p style="font-size: 15px; margin-top:-15px;">' + service_name + '</p><h3 style="font-size: 25px; margin-bottom: 5px; margin-top:-12px; margin-bottom:16px;">' + data.data.token_number + '</h3><p style="font-size: 15px; margin-top: -16px;margin-bottom: 27px;">' + data.data.date + '</p><div style="margin-top:-20px; margin-bottom:15px;" align="center"></div><p style="font-size: 15px; margin-top:-12px;">Please wait for your turn</p><p style="font-size: 15px; margin-top:-12px;">Customer Waiting:' + data.data.customer_waiting + '</p><p style="text-align:left !important;font-size:10px;"></p><p style="text-align:right !important; margin-top:-23px;font-size:10px;"></p>';

                        $('#printarea').html(html);

                        window.print();
                    }
                },
                error: function(xhr, status, error) {

                    hideLoading(1000);

                   alert('Something Went Wrong! Please Try Again.');

                   return false;
                }
            });
        }
    </script>
</html>