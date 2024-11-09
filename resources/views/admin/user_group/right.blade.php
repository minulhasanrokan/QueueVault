<div class="container-fluid">
    <div class="card card-default">
        <div class="card-header" id="menu_data">
            {!!$menu_data!!}
        </div>
        <div class="card-body">
            <div class="row">
                @php

                    if(isset($all_right['group_arr']) && !empty($all_right['group_arr'])){

                        $i=0;

                        foreach($all_right['group_arr'] as $right_group){

                            $i++;
                @endphp
                            <div class="col-md-12">
                                <fieldset class="scheduler-border">
                                    <legend class="scheduler-border">
                                        <label class="control-label input-label" for="g_id_checkbox_{{$i}}">{{$right_group['g_name']}} <!--<i class="fa {{$right_group['g_icon']}}"></i>--></label>
                                        <input type="checkbox" value="0" id="g_id_checkbox_{{$i}}" name="g_id_checkbox_{{$i}}" onclick="select_all_group_right({{$i}});" />
                                        <input type="hidden" id="g_id_{{$i}}" name="g_id_{{$i}}" value="{{$right_group['g_id']}}" />
                                    </legend>
                                    <div class="row">
                                        @php
                                            if(isset($all_right['cat_arr'][$right_group['g_id']]) && !empty($all_right['cat_arr'][$right_group['g_id']])){

                                                $j = 0;

                                                foreach($all_right['cat_arr'][$right_group['g_id']] as $right_cat){

                                                    $j++;
                                        @endphp     
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <h5>
                                                                <label class="control-label input-label" for="c_id_checkbox_{{$i}}_{{$j}}">{{$right_cat['c_name']}} <!--<i class="fa {{$right_cat['c_icon']}}"></i>--></label>
                                                                <input type="checkbox" value="0" id="c_id_checkbox_{{$i}}_{{$j}}" name="c_id_checkbox_{{$i}}_{{$j}}" onclick="select_all_cat_right({{$i}},{{$j}});" />
                                                                <input type="hidden" id="c_id_{{$i}}_{{$j}}" name="c_id_{{$i}}_{{$j}}" value="{{$right_cat['c_id']}}" />
                                                            </h5>
                                                            @php
                                                                if(isset($all_right['right_arr'][$right_group['g_id']][$right_cat['c_id']]) && !empty($all_right['right_arr'][$right_group['g_id']][$right_cat['c_id']])){

                                                                    $k=0;
                                                                    $status = 1;

                                                                    foreach($all_right['right_arr'][$right_group['g_id']][$right_cat['c_id']] as $right){

                                                                        $checked= "";
                                                                        $value = 0;

                                                                        if(isset($group_right[$right_group['g_id']][$right_cat['c_id']][$right['r_id']]['r_id'])){

                                                                            $checked ="checked";

                                                                            $value = 1;
                                                                        }
                                                                        else{

                                                                            $status = 0;
                                                                        }

                                                                        $k++;
                                                            @endphp
                                                                        <input {{$checked}} type="checkbox" value="{{$value}}" id="r_id_checkbox_{{$i}}_{{$j}}_{{$k}}" name="r_id_checkbox_{{$i}}_{{$j}}_{{$k}}" onclick="select_right({{$i}},{{$j}},{{$k}});" />
                                                                        <input type="hidden" id="r_id_{{$i}}_{{$j}}_{{$k}}" name="r_id_{{$i}}_{{$j}}_{{$k}}" value="{{$right['r_id']}}" />
                                                                        <label class="control-label input-label" for="r_id_checkbox_{{$i}}_{{$j}}_{{$k}}">{{$right['r_name']}} <!--<i class="fa {{$right['r_icon']}}"></i>--></label>
                                                                        <br>
                                                            @php
                                                                    }

                                                                    if($status==1){

                                                                        @endphp
                                                                            <script type="text/javascript">
                                                                                select_right({{$i}},{{$j}},{{$k}});
                                                                            </script>
                                                                        @php
                                                                    }

                                                                    @endphp

                                                                        <input type="hidden" id="r_id_max_{{$i}}_{{$j}}" name="r_id_max_{{$i}}_{{$j}}" value="{{$k}}" />
                                                                    @php
                                                                }
                                                            @endphp
                                                        </div>
                                                    </div>
                                        @php
                                                }

                                                @endphp

                                                    <input type="hidden" id="c_id_max_{{$i}}" name="c_id_max_{{$i}}" value="{{$j}}" />
                                                @php
                                            }
                                        @endphp
                                    </div>
                                </fieldset>
                            </div>
                @php
                        }

                        @endphp

                            <input type="hidden" id="g_id_max" name="g_id_max" value="{{$i}}" />
                        @php
                    }
                @endphp
                
            </div>
        </div>
        <div class="card-footer">
            <button type="button" onclick="validation();" class="btn btn-primary float-right" >Update User Group Right</button>
        </div>
    </div>
</div>
<style type="text/css">
    fieldset.scheduler-border {
        border: 1px solid #0069d9 !important;
        padding-left: 10px !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }

    legend.scheduler-border {
        width:inherit; /* Or auto */
        padding:0 5px; /* To give a bit of padding on the left and right */
        border-bottom:none;
    }
</style>
<script type="text/javascript">

    function validation(){

        confirm_box('User Group Right', 'Are You Sure Want To Update User Group Right?','update_right');
    }

    function update_right(){

        var max_g = $("#g_id_max").val();

        var form_data = new FormData();

        form_data.append("g_id_max", max_g);

        for(var i=1; i<=max_g; i++){

            form_data.append("g_id_checkbox_"+i, $("#g_id_checkbox_"+i).val());
            form_data.append("g_id_"+i, $("#g_id_"+i).val());
            form_data.append("c_id_max_"+i, $("#c_id_max_"+i).val());

            var c_id_max = $("#c_id_max_"+i).val();

            for(var j=1; j<=c_id_max; j++){

                form_data.append("c_id_checkbox_"+i+"_"+j, $("#c_id_checkbox_"+i+"_"+j).val());
                form_data.append("c_id_"+i+"_"+j, $("#c_id_"+i+"_"+j).val());
                form_data.append("r_id_max_"+i+"_"+j, $("#r_id_max_"+i+"_"+j).val());

                var r_id_max = $("#r_id_max_"+i+"_"+j).val();

                for(var k=1; k<=r_id_max; k++){

                    form_data.append("r_id_checkbox_"+i+"_"+j+"_"+k, $("#r_id_checkbox_"+i+"_"+j+"_"+k).val());
                    form_data.append("r_id_"+i+"_"+j+"_"+k, $("#r_id_"+i+"_"+j+"_"+k).val());
                }
            }
        }
    
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

                all_response(response,'{{route('user_management.user_group.view')}}','Right View User Group');
            },
            error: function(xhr, status, error) {

                response_error_alert();
            }
        });
    }
</script>