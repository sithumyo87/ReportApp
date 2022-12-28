<?php 

use Illuminate\Support\Facades\Auth;

/**
 * Changing english numbers into myanmar numbers
 */
function num_translate($value) 
{
    $mya = array('၀','၁','၂','၃','၄','၅','၆','၇','၈','၉');
    $eng = array('0','1','2','3','4','5','6','7','8','9');
    return str_replace($eng,$mya,$value);
}

function date_translate($date){
    $date = $date != '' ? date('d-m-Y', strtotime($date)) : null;
    return num_translate($date);
}
