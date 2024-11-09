<?php
   
use Carbon\Carbon;

// for "2024-05-31 20:24:42" to "31-05-2024 08:24:42 PM" from all details view
if (!function_exists('format_datetime_view')) {

    function format_datetime_view($datetime){

        if ($datetime=='' || $datetime==null) {
            
            return '';
        }
        
        return \Carbon\Carbon::parse($datetime)->format('d-m-Y h:i:s A');
    }
}

// for "dd/mm/yyy" to "yyyy-mm-dd" from database where condition
if (!function_exists('input_db_fromate')) {

    function input_db_fromate($dateString){

        return $date = DateTime::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
    }
}