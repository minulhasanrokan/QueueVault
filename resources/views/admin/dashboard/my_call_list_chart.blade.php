@php
    
    use Carbon\Carbon;
    $totalDays = Carbon::now()->daysInMonth;

    $data = App\Models\Token::my_call_list_chart();

    if(isset($dashboard_right['dashboard_right_arr']['r_route_name']['service']['service.manage_call']['my_call_list']) || $super_admin_status==1){

        $route_name = route('service.manage_call.my_call_list');

        $title = 'My Call List';
@endphp

        <div class="col-md-12 px-1">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">My Call List ({{Carbon::now()->format('F')}} - {{date('Y')}})</h3>
                        <a href="javascript:void(0);" onclick="get_new_page('{{$route_name}}','{{$title}}','','',0,0);">View Call List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative mb-4">
                        <canvas id="my_call_list_chart" height="200"></canvas>
                    </div>
                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fas fa-square text-primary"></i> Served
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-square text-danger"></i> Not Show
                        </span>
                        <span>
                            <i class="fas fa-square text-gray"></i> Other
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">

            var ticksStyle = {
                fontColor: '#495057',
                fontStyle: 'bold'
            };

            var mode = 'index';
            var intersect = true;
            
            var $my_call_list_chart = $('#my_call_list_chart')
            var my_call_list_chart = new Chart($my_call_list_chart, {
                type: 'bar',
                data: {
                    labels: [@for($i=1;$i<=$totalDays;$i++) {{$i.','}} @endfor],
                    datasets: [
                        {
                            backgroundColor: '#007bff',
                            borderColor: '#ced4da',
                            data: [
                                    @php
                                        if (isset($data[1])){

                                            for ($i=1;$i<=$totalDays;$i++) {

                                                if (isset($data[1][$i])){

                                                    echo $data[1][$i];
                                                }
                                                else{

                                                    echo 0;
                                                }

                                                echo ",";
                                            }
                                        }
                                    @endphp
                                ]
                        },
                        {
                            backgroundColor: '#dc3545',
                            borderColor: '#ced4da',
                            data: [
                                    @php
                                        if (isset($data[2])){

                                            for ($i=1;$i<=$totalDays;$i++) {

                                                if (isset($data[2][$i])){

                                                    echo $data[2][$i];
                                                }
                                                else{

                                                    echo 0;
                                                }

                                                echo ",";
                                            }
                                        }
                                    @endphp
                                ]
                        },
                        {
                            backgroundColor: '#ced4da',
                            borderColor: '#ced4da',
                            data: [
                                    @php
                                        if (isset($data[0])){

                                            for ($i=1;$i<=$totalDays;$i++) {

                                                if (isset($data[0][$i])){

                                                    echo $data[0][$i];
                                                }
                                                else{

                                                    echo 0;
                                                }

                                                echo ",";
                                            }
                                        }
                                    @endphp
                                ]
                        }
                    ]
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        mode: mode,
                        intersect: intersect
                    },
                    hover: {
                        mode: mode,
                        intersect: intersect
                    },
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            //display: false,
                            gridLines: {
                                display: true,
                                lineWidth: '4px',
                                color: 'rgba(0, 0, 0, .2)',
                                zeroLineColor: 'transparent'
                            },
                            ticks: $.extend({
                                beginAtZero: true,
                                callback: function (value) {
                                    if (value >= 1000) {
                                        value /= 1000
                                        value += 'k'
                                    }
                                    return value
                                }
                            }, ticksStyle)
                        }],
                        xAxes: [{
                            display: true,
                                gridLines: {
                                display: false
                            },
                            ticks: ticksStyle
                        }]
                    }
                }
            })
        </script>
@php
    }
@endphp