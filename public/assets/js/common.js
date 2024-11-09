
function trim( stringToTrim ){
    
    return stringToTrim.toString().replace( /^\s+|\s+$/g, "" );
}

function readImageUrl(input,view_id){

    if (input.files && input.files[0]) {

        var reader = new FileReader();

        reader.onload = function(e){

            $('#'+view_id).attr('src', e.target.result).width(80).height(80);
        };

        reader.readAsDataURL(input.files[0]);
    }
}

function capitalizeFirstLetter(str) {

    let words = str.split(" ");
    
    for (let i = 0; i < words.length; i++) {
        
        words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
    }
    
    return words.join(" ");
}
    
function form_validation(control,msg_text){

    controlArr=control.split("*");
    msg_text=msg_text.split("*");

    var bgcolor='-moz-linear-gradient(bottom, rgb(254,151,174) 0%, rgb(255,255,255) 10%, rgb(254,151,174) 96%)';

    for (var i=0; i<controlArr.length; i++){

        var control=controlArr[i].split("#");

        var el = document.querySelector('#'+control[0]);

        if (!el){
            
            alert('Input Or Select Field Name: ' + msg_text[i] + ' Not Found');
        }

        document.getElementById(control[0]).style.backgroundImage="";

        var input_element = document.getElementById(control[0]);
        var inputValueLength = input_element.value.length;

        minlength = input_element.getAttribute('minlength');
        maxLength = input_element.getAttribute('maxlength');
        
        var type = control[1];

        if ( type == 'text')
        {
            if (trim(document.getElementById(control[0]).value)=="")
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Fill up "+msg_text[i]+' Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            if(minlength!=null)
            {
                if(inputValueLength<minlength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Value minimum length Is '+minlength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }

            if(maxLength!=null)
            {
                if(inputValueLength>maxLength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Value Maximum length Is '+maxLength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }
        }
        if ( type == 'alpha')
        {
            if (trim(document.getElementById(control[0]).value)=="")
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Fill up "+msg_text[i]+' Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            var alphaRegex = /^[a-zA-Z]*$/

            if (!alphaRegex.test(document.getElementById(control[0]).value))
            {
                document.getElementById(control[0]).focus();
                
                $("#" + control[0] + "-error").html(msg_text[i]+' Value Will Be Only Alpha');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            if(minlength!=null)
            {
                if(inputValueLength<minlength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Value minimum length Is '+minlength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }

            if(maxLength!=null)
            {
                if(inputValueLength>maxLength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Value Maximum length Is '+maxLength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }
        }
        if ( type == 'date')
        {
            if (trim(document.getElementById(control[0]).value)=="")
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Input "+msg_text[i]+' Field Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            var regexDate = /^\d{2}\/\d{2}\/\d{4}$/;

            if (!regexDate.test(trim(document.getElementById(control[0]).value))) {
                
                $("#" + control[0] + "-error").html("Please Input Valid "+msg_text[i]+' Field Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            var parts = trim(document.getElementById(control[0]).value).split("/");
            var day = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10);
            var year = parseInt(parts[2], 10);

            if (year < 1000 || year > 3000 || month === 0 || month > 12) {

                $("#" + control[0] + "-error").html("Please Input Valid "+msg_text[i]+' Field Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            if (month === 2) {

                var isLeapYear = (year % 4 === 0 && year % 100 !== 0) || (year % 400 === 0);

                if (day < 1 || (isLeapYear && day > 29) || (!isLeapYear && day > 28)) {

                    $("#" + control[0] + "-error").html("Please Input Valid "+msg_text[i]+' Field Value');
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }
            else{
                var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
                
                if (day < 1 || day > daysInMonth[month - 1]) {

                    $("#" + control[0] + "-error").html("Please Input Valid "+msg_text[i]+' Field Value');
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }
        }
        if ( type == 'file')
        {
            if (trim(document.getElementById(control[0]).value)=="")
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Input "+msg_text[i]+' File');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }
        }
        else if ( type == 'amount')
        {
            if (trim(document.getElementById(control[0]).value)=="")
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Fill up "+msg_text[i]+' Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            var numericOrFloatRegex = /^[+-]?\d+(\.\d+)?$/;

            if (!numericOrFloatRegex.test(document.getElementById(control[0]).value))
            {
                document.getElementById(control[0]).focus();
                
                $("#" + control[0] + "-error").html(msg_text[i]+' Value Will Be Amount Type Only');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            if (trim(document.getElementById(control[0]).value)<=0)
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html(msg_text[i]+' Value Can Not Be Less Then Or Equal Zero');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }
        }
        else if ( type == 'number')
        {
            if (trim(document.getElementById(control[0]).value)=="")
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Fill up "+msg_text[i]+' Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            var numberRegex = /^[0-9]+$/;

            if (!numberRegex.test(document.getElementById(control[0]).value))
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html(msg_text[i]+' Value Will Be Numaric Only');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            if(minlength!=null)
            {
                if(inputValueLength<minlength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Value minimum length Is '+minlength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }

            if(maxLength!=null)
            {
                if(inputValueLength>maxLength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Value Maximum length Is '+maxLength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }

            if (trim(document.getElementById(control[0]).value)<=0)
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html(msg_text[i]+' Value Can Not Be Less Then Or Equal Zero');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }
        }
        else if ( type  == 'email' )
        {
            if (trim(document.getElementById(control[0]).value)=="")
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Fill up "+msg_text[i]+' Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            var emailFormat = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if (!emailFormat.test(trim(document.getElementById(control[0]).value)))
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html(msg_text[i]+' Value Will Be E-mail Type');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            if(minlength!=null)
            {
                if(inputValueLength<minlength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Value minimum length Is '+minlength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }

            if(maxLength!=null)
            {
                if(inputValueLength>maxLength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Value Maximum length Is '+maxLength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }
        }
        else if ( type  == 'mobile' )
        {
            if (trim(document.getElementById(control[0]).value)=="")
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Fill up "+msg_text[i]+' Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            var nmobileRegex = /^\+?(01[3-9]\d{8})$/;

            if (!nmobileRegex.test(trim(document.getElementById(control[0]).value)))
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html(msg_text[i]+' Filed Value Will Be Mobile Number Type');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }

            if(minlength!=null)
            {
                if(inputValueLength<minlength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Filed Value minimum length Is '+minlength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }

            if(maxLength!=null)
            {
                if(inputValueLength>maxLength)
                {
                    $("#" + control[0] + "-error").html(msg_text[i]+' Filed Value Maximum length Is '+maxLength);
                    $("#" + control[0] + "-error").show();
                    $(".error").delay(800).fadeOut(800);

                    return 0;
                }
            }
        }
        else if (type=='select' )
        {
            if ( trim(document.getElementById(control[0]).value)=='')
            {
                document.getElementById(control[0]).focus();

                $("#" + control[0] + "-error").html("Please Select "+msg_text[i]+' Value');
                $("#" + control[0] + "-error").show();
                $(".error").delay(800).fadeOut(800);

                return 0;
            }
        }
        else if (type == 'checkbox' || type == 'radio')
        {
            if (new_elem=="") new_elem=control[0]; else new_elem=new_elem+","+control[0];

            $("#" + control[0] + "-error").html("Please Select "+msg_text[i]+' Value');
            $("#" + control[0] + "-error").show();
            $(".error").delay(800).fadeOut(800);

            return 0;
        }
    }

    return 1;
}

function showLoading() {
    
    document.querySelector('#loading').classList.add('loading');
    document.querySelector('#loading-content').classList.add('loading-content');
}

function hideLoading(time=1000) {

    setTimeout(() => {
        document.querySelector('#loading').classList.remove('loading');
        document.querySelector('#loading-content').classList.remove('loading-content');
    }, time);    
}

function hide_modal(){

    $("#staticBackdropLabel").html('');
    $("#modal_body").html('');
    $('#modal_body #menu_data').hide();
    $('#staticBackdrop').modal('hide');
}

function hide_right_modal(){

    $("#right_view_modal_title").html('');
    $("#right_view_modal_body").html('');
    $('#right_view_modal').modal('hide');
}

function hide_activity_history_modal(){

    $("#activity_history_modal_title").html('');
    $("#activity_history_modal_body").html('');
    $('#activity_history_modal').modal('hide');
}

function hide_history_modal(){

    $("#history_modal_title").html('');
    $("#history_modal_body").html('');
    $('#history_modal').modal('hide');
}

function select_all_group_right(id){

    var status = false;

    if ($('#g_id_checkbox_'+id).prop('checked')) {
        
        status = true;
        $('#g_id_checkbox_'+id).val(1);
    }
    else{

        $('#g_id_checkbox_'+id).val(0);
    }

    var max_c_id_sl = $("#c_id_max_"+id).val();

    for(var i=1; i<=max_c_id_sl; i++){

        var max_r_id_sl = $("#r_id_max_"+id+"_"+i).val();

        if(status==true){

            $('#c_id_checkbox_'+id+"_"+i).prop('checked', true);
            $('#c_id_checkbox_'+id+"_"+i).val(1);
        }
        else{

            $('#c_id_checkbox_'+id+"_"+i).prop('checked', false);
            $('#c_id_checkbox_'+id+"_"+i).val(0);
        }

        for(var j=1; j<=max_r_id_sl; j++){

            if(status==true){

                $('#r_id_checkbox_'+id+"_"+i+"_"+j).prop('checked', true);
                $('#r_id_checkbox_'+id+"_"+i+"_"+j).val(1);
            }
            else{

                $('#r_id_checkbox_'+id+"_"+i+"_"+j).prop('checked', false);
                $('#r_id_checkbox_'+id+"_"+i+"_"+j).val(0);
            }
        }
    }
}

function select_all_cat_right(g_id,cat_id){

    var status = false;

    if ($('#c_id_checkbox_'+g_id+"_"+cat_id).prop('checked')) {
        
        status = true;

        $('#c_id_checkbox_'+g_id+"_"+cat_id).val(1);
    }
    else{

        $('#g_id_checkbox_'+g_id).prop('checked', false);
        $('#g_id_checkbox_'+g_id).val(0);
        $('#c_id_checkbox_'+g_id+"_"+cat_id).val(0);
    }

    var max_r_id_sl = $("#r_id_max_"+g_id+"_"+cat_id).val();

    for(var j=1; j<=max_r_id_sl; j++){

        if(status==true){

            $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).prop('checked', true);
            $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).val(1);
        }
        else{

            $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).prop('checked', false);
            $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).val(0);
        }
    }

    check_group(g_id);
}

function select_right(g_id,cat_id,r_id){

    if ($('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+r_id).prop('checked')) {

        $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+r_id).val(1);

        var max_r_id_sl = $("#r_id_max_"+g_id+"_"+cat_id).val();

        var status = false;

        for(var j=1; j<=max_r_id_sl; j++){

            if ($('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).prop('checked')) {

                $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).val(1);

                status = true;
            }
            else{

                $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+j).val(0);

                status = false;
                break;
            }
        }

        if(status==true){

            $('#c_id_checkbox_'+g_id+"_"+cat_id).prop('checked', true);
            $('#c_id_checkbox_'+g_id+"_"+cat_id).val(1);

            check_group(g_id);
        }
        else{

            $('#c_id_checkbox_'+g_id+"_"+cat_id).prop('checked', false);
            $('#c_id_checkbox_'+g_id+"_"+cat_id).val(0);

            check_group(g_id);
        }
    }
    else{

        $('#c_id_checkbox_'+g_id+"_"+cat_id).prop('checked', false);
        $('#c_id_checkbox_'+g_id+"_"+cat_id).val(0);
        $('#g_id_checkbox_'+g_id).prop('checked', false);
        $('#g_id_checkbox_'+g_id).val(0);
        $('#r_id_checkbox_'+g_id+"_"+cat_id+"_"+r_id).val(0);
    }
}

function check_group(g_id){

    var max_c_id_sl = $("#c_id_max_"+g_id).val();

    var status = false;

    for(var i=1; i<=max_c_id_sl; i++){

        if ($('#c_id_checkbox_'+g_id+"_"+i).prop('checked')) {

            status = true;
            $('#c_id_checkbox_'+g_id+"_"+i).val(1);
        }
        else{

            $('#c_id_checkbox_'+g_id+"_"+i).val(0);

            status = false;
            break;
        }
    }

    if(status==true){

        $('#g_id_checkbox_'+g_id).prop('checked', true);
        $('#g_id_checkbox_'+g_id).val(1);
    }
    else{

        $('#g_id_checkbox_'+g_id).prop('checked', false);
        $('#g_id_checkbox_'+g_id).val(0);
    }
}

async function confirm_box(head,body,function_name){

    var status = await open_confirm_box(head, body).then(response => {return response});

    if(status==1)
    {
        $('#custom-modal').modal('hide');
        $('#custom-modal').on('hidden.bs.modal', function () {
            $('#custom-modal').off('hidden.bs.modal');
            showLoading();
            setTimeout(() => {
                callDynamicFunction(function_name);
            }, 0);
        });
    }
}

function callDynamicFunction(functionName) {
    
    if (typeof window[functionName] === 'function') {
        
        window[functionName]();
    }
    else{
        
        console.error('Function does not exist:', functionName);
    }
}

function open_confirm_box(head,body){

    return new Promise((resolve, reject) => {
        $('#modal-head').html('<h4 class="modal-title">' + head + '</h4>');
        $('#modal-body').html('<p style="margin-left: 10px;">' + body + '</p>');
        $('#modal-footer').html('<button tabindex="1" type="button" class="btn btn-primary" id="ok-btn">Ok</button><button tabindex="2" type="button" class="btn btn-danger" id="cancel-btn">Cancel</button>');
        $('#custom-modal').modal('show');

        $('#custom-modal').off('click', '#ok-btn');
        $('#custom-modal').off('click', '#cancel-btn');

        $('#custom-modal').on('shown.bs.modal', function () {
            $('#ok-btn').focus();
        });

        $('#custom-modal').on('shown.bs.modal', function () {
            $(document).on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); 
                    const focusedElement = document.activeElement;
                    if (focusedElement && focusedElement.tagName === 'BUTTON') {
                        $(focusedElement).click();
                    }
                }
            });
        });

        $('#custom-modal').on('click', '#ok-btn', function () {
            $('#custom-modal').modal('hide');
            resolve(1);
        });

        $('#custom-modal').on('click', '#cancel-btn', function () {
            $('#custom-modal').modal('hide');
            resolve(0);
        });
    });
}

// for "2024-05-31" to "31-05-2024"

function formatDate(dateStr) {

    if(trim(dateStr)==''){
        return '';
    }

    const [year, month, day] = dateStr.split('-');

    return `${day}-${month}-${year}`;
}

// for "2024-05-31 20:24:42" to "31-05-2024 08:24:42 PM"
function formatDatetime(inputDatetime) {

    if(trim(inputDatetime)==''){
        return '';
    }

    const parts = inputDatetime.split(' ');
    const datePart = parts[0];
    const timePart = parts[1];

    const dateParts = datePart.split('-');
    const year = dateParts[0];
    const month = dateParts[1];
    const day = dateParts[2];

    const timeParts = timePart.split(':');
    let hours = parseInt(timeParts[0]);
    const minutes = timeParts[1];
    const seconds = timeParts[2];


    const amPM = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12;

    const formattedDatetime = `${day}-${month}-${year} ${hours}:${minutes}:${seconds} ${amPM}`;

    return formattedDatetime;
}

function all_response(response,url,title){

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

        if (data.errors && data.success==false) {

            $.each(data.errors, function(field, errors) {

                $("#" + field).css("border-color", "red");
                $("#" +field+ "-error").html(errors);
                $("#" + field + "-error").show();
            });
        }
        else{

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

                get_new_page(url,title,'','',0,0)
            }
        }

        setTimeout(function() {

            $(".form-control").css("border-color", "");
        }, 3000);

        $(".error").delay(3000).fadeOut(800);
    }

    hideLoading(1000);
}

function response_error_alert(){

    hideLoading(1000);

    alert('Something Went Wrong! Please Try Again');

    //location.replace(base_url+'/logout');
}

function grid_response(json){

    hideLoading(1000);

    if(json=='Session Expire' || json=='Right Not Found'){

        alert(json);

        location.replace(base_url+'/logout');
    }
    else if(json=='lock'){

        alert(json);

        location.replace(base_url+'/lock');
    }
    else{

        if(json.csrf_token !== undefined){

            $('meta[name=csrf-token]').attr("content", json.csrf_token);
            $('input[name=_token]').attr("value", json.csrf_token);
        }

        return json.aaData;
    }
}

function two_date_validation(from_date_id,to_date_id){

    var from_date = $("#"+from_date_id).val().trim();
    var to_date = $("#"+to_date_id).val().trim();

    var fromParts = from_date.split('/');
    var toParts = to_date.split('/');
    var from = new Date(fromParts[2], fromParts[1] - 1, fromParts[0]);
    var to = new Date(toParts[2], toParts[1] - 1, toParts[0]);

    if (from > to) {

        $("#" +from_date_id+ "-error").html("From date must be earlier than or equal to To date.");
        $("#" +from_date_id+ "-error").show();
        $(".error").delay(800).fadeOut(800);

        return false;
    }
}

function make_datepicker(input_id){

    $('#'+input_id).datepicker({
        dateFormat: 'dd/mm/yy',
        autoclose: true,
        todayHighlight: true,
        orientation: "bottom"
    });
}

function copy_text(copy_text_id,field_type){

    var succeed;

    if(field_type=='input'){

        var tex_value = trim($("#"+copy_text_id).val());

        $("#"+copy_text_id).focus();

        if(tex_value==''){

            alert('Nothing Found For Copy');
            return false;
        }

        document.getElementById(copy_text_id).setSelectionRange(0,tex_value.length);

        try {

            succeed = document.execCommand("copy");
        }
        catch (e) {

            console.warn(e);

            succeed = false;
        }

        if(succeed==true){

            alert('Copied');
        }
        else{

            alert('Something Went Wrong');
        }

    }
    else if(field_type=='text'){

        if(trim(document.getElementById(copy_text_id).innerText)==''){

            alert('Nothing Found For Copy');
            return false;
        }

        const textarea = document.createElement('textarea');
        textarea.id = 'temp_element';
        textarea.style.height = 0;
        document.body.appendChild(textarea);
        textarea.value = trim(document.getElementById(copy_text_id).innerText);

        const selector = document.querySelector('#temp_element');
        selector.select();

        try {

            succeed = document.execCommand('copy');
            document.body.removeChild(textarea);
        }
        catch (e) {

            console.warn(e);

            succeed = false;
        }

        if(succeed==true){

            alert('Copied');
        }
        else{

            alert('Something Went Wrong');
        }

    }

    return succeed;
}