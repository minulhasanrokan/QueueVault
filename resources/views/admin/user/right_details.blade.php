<div class="container-fluid">
    <div class="card card-default">
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
                                        <label class="control-label input-label" for="g_id_checkbox_{{$i}}">{{$right_group['g_name']}}</label>
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
                                                                <label class="control-label input-label" for="c_id_checkbox_{{$i}}_{{$j}}">{{$right_cat['c_name']}}</label>
                                                            </h5>
                                                            @php
                                                                if(isset($all_right['right_arr'][$right_group['g_id']][$right_cat['c_id']]) && !empty($all_right['right_arr'][$right_group['g_id']][$right_cat['c_id']])){

                                                                    $k=0;
                                                                    $status = 1;

                                                                    foreach($all_right['right_arr'][$right_group['g_id']][$right_cat['c_id']] as $right){

                                                                        $checked= 'span_red';

                                                                        if(isset($user_right[$right_group['g_id']][$right_cat['c_id']][$right['r_id']]['r_id'])){

                                                                            $checked ='span_green';
                                                                        }
                                                                        
                                                                        $k++;
                                                            @endphp     
                                                                        <label class="control-label input-label {{$checked}}" for="r_id_checkbox_{{$i}}_{{$j}}_{{$k}}">{{$right['r_name']}}</label>
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

                                                                    @php
                                                                }
                                                            @endphp
                                                        </div>
                                                    </div>
                                        @php
                                                }

                                                @endphp

                                                @php
                                            }
                                        @endphp
                                    </div>
                                </fieldset>
                            </div>
                @php
                        }

                        @endphp

                        @php
                    }
                @endphp
                
            </div>
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