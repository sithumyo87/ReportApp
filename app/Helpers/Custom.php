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
    if ($percent > 0 && $percent != '') {
        $value = $price * ($percent / 100);
        $float_num = $price + $value;
        if ($float_num > floor($float_num)){
            $float_num = floor($float_num)+1;
        } 
    }
    return $float_num;
}
function payments(){
    return [
        '1' => '50/50',
        '2' => '60/40',
        '3' => '80/20',
        '4' => 'Cash',
        '5' => 'Credit',
        '6' => 'Other'
    ];
}
function get_payment($type) {
    switch ($type) {
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
            return 'Cash';
            break;
        case '5':
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

function getInvoiceDate($invoice, $type=null, $advance_data=null){
    $date = isset($advance_data) ? $advance_data->Date : $invoice->Date;
    if($type == 'first'){
        $date = $invoice->finv_date;
    }else if($type== 'second'){
        $date = $invoice->sinv_date;
    }else if($type == 'cash' || $type == 'credit'){
        $date = $invoice->sinv_date;
    }else if($type != ''){
        // get date from advance table
    }
    return date('d-m-Y', strtotime($date));
}
function getReceiptDate($receipt, $type=null, $advance_data=null){
    $date = isset($advance_data) ? $advance_data->receipt_date : $receipt->Date;
    if($type == 'first'){
        $date = $receipt->frec_date;
    }else if($type== 'second'){
        $date = $receipt->srec_date;
    }else if($type == 'cash' || $type == 'credit'){
        $date = $receipt->srec_date;
    }else if($type != ''){
        // get date from advance table
    }
    return date('d-m-Y', strtotime($date));
}

function getInvoiceCheck($invoice){
    $state = false;
    if($invoice->Advance != '6'){ // not other payment
        if($invoice->Advance != '4' && $invoice->Advance != '5'){ // not cash and credit
            if($invoice->FirstInvoice != 1 || $invoice->SecondInvoice != 1){
                $state = true;
            }
        }else{
            if($invoice->FirstInvoice != 1 && $invoice->SecondInvoice != 1){
                $state = true;
            }
        }
    }
    return $state;
}
function getReceiptCheck($receipt, $invoice){
    $state = false;
    if($receipt->Advance != '6'){ // not other payment
        if($receipt->Advance != '4' && $receipt->Advance != '5'){ // not cash and credit
            if(($receipt->First_Receipt != 1 && $invoice->FirstInvoice == 1) || ($receipt->Second_Receipt != 1  && $invoice->SecondInvoice == 1)){
                $state = true;
            }
        }else{
            if($receipt->First_Receipt != 1 && $receipt->Second_Receipt != 1 && $invoice->FirstInvoice == 1 && $invoice->SecondInvoice == 1){
                $state = true;
            }
        }
    }
    return $state;
}

function getInvoiceType($invoice){
    $type = '';
    if($invoice->Advance != '6'){ // not other payment
        if($invoice->Advance != '4' && $invoice->Advance != '5'){ // not cash and credit
            if($invoice->FirstInvoice != 1){
                $type = 1;
            }else{
                $type = 2;
            }
        }else{
            if($invoice->FirstInvoice != 1 && $invoice->SecondInvoice != 1){
                $type = 3;
            }
        }
    }else{
        $type = 4;
    }
    return $type;
}
function getReceiptType($receipt){
    $type = '';
    if($receipt->Advance != '6'){ // not other payment
        if($receipt->Advance != '4' && $receipt->Advance != '5'){ // not cash and credit
            if($receipt->First_Receipt != 1){
                $type = 1;
            }else{
                $type = 2;
            }
        }else{
            if($receipt->First_Receipt != 1 && $receipt->Second_Receipt != 1){
                $type = 3;
            }
        }
    }else{
        $type = 4;
    }
    return $type;
}

function getInvRecName($invoice){
    $name = '';
    if($invoice->Advance == '1'){
        $name = '50/50';
    }else if($invoice->Advance == '2'){
        $name = '60/40';
    }else if($invoice->Advance == '3'){
        $name = '80/20';
    }else if($invoice->Advance == '4'){
        $name = 'CASH';
    }else if($invoice->Advance == '5'){
        $name = 'CREDIT';
    }else{

    }
    return $name;
}

function otherPaymentRemainAmt($invoice, $advance_last, $details){
    if(isset($advance_last)){
        return $advance_last->Balance;
    }else{
        $total = 0;
        foreach ($details as $value) {
            $total += percent_price($value->Unit_Price, $value->percent) * $value->Qty;
        }
    
        $taxAmount = ($invoice->tax_id * ($total - $invoice->Discount))/100;
        $grandTotal = $total - $invoice->Discount + $taxAmount;
        return $grandTotal;
    }
}

function advanceName($nth){
    if($nth == 1){
        return 'FIRST';
    }else if($nth == 2){
        return 'SECOND';
    }else if($nth == 3){
        return 'THIRD';
    }else if($nth == 4){
        return 'FOURTH';
    }else if($nth == 5){
        return 'FIFTH';
    }else if($nth == 6){
        return 'SIXTH';
    }else if($nth == 7){
        return 'SEVENTH';
    }else{
        return $nth. 'th ';
    }
}