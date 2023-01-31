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

function percent_price($price, $percent) {
    $float_num = $price;
    if ($percent > 0) {
        $value = $price * ($percent / 100);
        $float_num = $price + $value;
        if ($float_num > floor($float_num)){
            $float_num = floor($float_num)+1;
        } 
    }
    return $float_num;
}

function get_payment($type) {
    switch ($type) {
        case '50':
            return '50/50';
            break;
        case '60':
            return '60/40';
            break;
        case '80':
            return '80/20';
            break;
        case '1':
            return 'Cash';
            break;
        case '0':
            return 'Credit';
            break;
        default:
            return 'Other';
            break;
    }
}

function get_pay_term($id) {
    switch ($id) {
        case '1':
            return '50/50';
            break;
        case '2':
            return '60/40';
            break;
        case '3':
            return '80/20';
            break;
        case '4':
            return 'CASH';
            break;
        case '5':
            return 'CREDIT';
            break;
        default:
            return 'OTHER';
            break;
    }
}