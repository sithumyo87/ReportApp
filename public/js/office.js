function url() {
    return (
        $('meta[name="app-url"]').attr("content")
    );
}

$(function () {
    $("#chkPo").click(function () {
        if ($(this).is(":checked")) {
            $("#dvPo").show();
        } else {
            $("#dvPo").hide();
        }
    });
});

$(function () {
    $("#chkVender").click(function () {
        if ($(this).is(":checked")) {
            $("#dvVender").show();
        } else {
            $("#dvVender").hide();
        }
    });
});

//Date Picker
$(document).ready(function() { 

    // bootstrap date-picker
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";

    $('.date-picker').each(function(){
        $(this).datepicker({
            dateFormat: 'dd-mm-yy',
            todayHighlight: true,
        });
        $(this).attr('autocomplete', 'off');
    });

    
    
    $('.select2').select2();

    tinymce.init({
        selector: 'textarea.tinymce-editor',
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount', 'image',
            'textcolor'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help ' + ' forecolor backcolor ',
        content_css: '//www.tiny.cloud/css/codepen.min.css'
    });


    if($('.sig').length > 0){
        // signature
        var sig = $('.sig').signature({
            syncField: $(this).find('.signature64'), 
            syncFormat: 'PNG',
            color: '#00008B'
        });
        $('.clear').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $(".signature64", this).val('');
        });
        $('.sign-button').click(function(e) {
            e.preventDefault();
            sig.signature('clear');
            $(".signature64", this).val('');
        }); 
    }

});

//Quotation 
$(document).on('select2:select','#quo-get-name', function(e) {
    var data = e.params.data;
// 			console.log(data.id);
    if (data.id == 'Attn Name') {
        window.location.reload();
    } else {
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                document.getElementById('when-choose-quo-name').innerHTML = xhttp.responseText;
            }
        }
        xhttp.open('GET', base_url() + 'office2020/get_data_from_quo_name/' + data.id, true);
        xhttp.send();
    }
});

$(document).on('select2:select','#quo-get-company', function(e) {
    var data = e.params.data;
// 			console.log(data.id);
    if (data.id == 'Company Name') {
        window.location.reload();
    } else {
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                $('#to-hide-name-when-choose-quo-company').css('display', 'none');
                document.getElementById('when-choose-quo-company').innerHTML = xhttp.responseText;
            }
        }
        xhttp.open('GET', base_url() + 'office2020/get_data_from_quo_company/' + data.id, true);
        xhttp.send();
    }
});

$(document).on('select2:select select2:unselect select2:clear','.attn-customer', function(e) {
    console.log('custoemr chnge');
    var attn = $('.attn-customer').val();
    attn_on_change(attn, '');
});

$(document).on('select2:select select2:unselect select2:clear','.attn-company', function(e) {
    console.log('cmp chnge');
    var company = $('.attn-company').val(); 
    attn_on_change('', company);
});

function attn_on_change(attn, company){
    var urlPath = url() + 'OfficeManagement/attnOnChange?attn='+ attn + '&company=' + company;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {
            $('.attn-form').html(data);
            $('.select2').select2({
                tags: true,
                placeholder: "Select an Option",
                allowClear: true,
                width: '100%'
            });
        },
    });
}

$(document).on('click','#quo-tax-check', function(e) {
    checkBox = document.getElementById('quo-tax-check');
    var id = $('#quo-tax-check').data('id');
    var total = $('#quo-tax-check').data('total');
    var tax = '0';
    // Check if the element is selected/checked
    if(checkBox.checked) {
        var tax = '5';
    }
    var urlPath = url() + 'OfficeManagement/quoTaxCheck?id=' + id + '&tax=' + tax+ '&total=' + total;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {
           $('#tax-amount').html(data['tax_amount']);
           $('#grand-total').html(data['grand_total']);
        },
    });
});

$(document).on('click','.edit-note', function(e) {
    var id = $(this).data('id');
    var note = $(this).data('note');

    $('#noteId').val(id);
    $('#note').val(note);
});

$(document).on('click','.note-reset', function(e) {
    $('#noteId').val('');
    $('#note').val('');
});


$(document).on('change','#authorizer', function(e) {
    var auth = $(this).val();
    var file = $(this).find(':selected').data('file')
    console.log('auth ' + file);

    $('#authorizer-img').attr('src', file);
});

$(document).on('select2:select select2:unselect select2:clear','.quo-select', function(e) {
    var quoId = $('.quo-select').val();
    console.log('quo chnge' + quoId);
    if(quoId != ''){
        quo_attn_on_change(quoId);
        $('.inv-to-gp').removeClass('hidden');
    }else{
        attn_on_change('', '');
        $('#sub').val('');
        $('#sub').removeAttr('readonly');
        $('.currency').removeClass('readonly');
        $('.inv-to-gp').addClass('hidden');
    }
});

function quo_attn_on_change(quoId){
    var urlPath = url() + 'OfficeManagement/quoAttnOnChange?quoId='+ quoId;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {
            // console.log(data);
            $('.quo-data-form').html(data);
            // $('.select2').select2({
            //     tags: true,
            //     placeholder: "Select an Option",
            //     allowClear: true,
            //     width: '100%'
            // });
        },
    });
}


$(document).on('click','#inv-tax-check', function(e) {

    checkBox = document.getElementById('inv-tax-check');
    var id = $('#inv-tax-check').data('id');
    var total = $('#inv-tax-check').data('total');
    var tax = '0';
    // Check if the element is selected/checked
    if(checkBox.checked) {
        var tax = '5';
    }
    var urlPath = url() + 'OfficeManagement/invTaxCheck?id=' + id + '&tax=' + tax+ '&total=' + total;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {
           $('#tax-amount').html(data['tax_amount']);
           $('#grand-total').html(data['grand_total']);
        },
    });
});


$(document).on('click','.bank-info-check', function(e) {
    var id = $(this).data('id');
    var bankId = $(this).data('bank');
    var check = '0';
    if($(this).prop("checked") == true) {
        check = '1';
    }
    var urlPath = url() + 'OfficeManagement/invBankCheck?id=' + id + '&bankId=' + bankId+ '&check=' + check;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {},
    });
});

$(document).on('select2:select select2:unselect select2:clear','#inv-select', function(e) {
    var invId = $(this).val();
    console.log('inv chnge' + invId);
    if(invId != ''){
        inv_attn_on_change(invId);
        $('.inv-to-gp').removeClass('hidden');
    }else{
        attn_on_change('', '');
        $('#sub').val('');
        $('#sub').removeAttr('readonly');
        $('.currency').removeClass('readonly');
        $('.payment').removeClass('readonly');
        $('.inv-to-gp').addClass('hidden');
    }
});

function inv_attn_on_change(invId){
    var urlPath = url() + 'OfficeManagement/invAttnOnChange?invId='+ invId;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {
            $('#inv-data-form').html(data);
        },
    });
}


$(document).on('click','#po-tax-check', function(e) {

    checkBox = document.getElementById('po-tax-check');
    var id = $('#po-tax-check').data('id');
    var total = $('#po-tax-check').data('total');
    var tax = '0';
    // Check if the element is selected/checked
    if(checkBox.checked) {
        var tax = '5';
    }
    var urlPath = url() + 'OfficeManagement/poTaxCheck?id=' + id + '&tax=' + tax+ '&total=' + total;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {
            console.log(data);
           $('#tax-amount').html(data['tax_amount']);
           $('#grand-total').html(data['grand_total']);
        },
    });
});


$(document).on('click','#quo-cb', function(e) {
    var value = $(this).val();
    var check = '0';
    if($(this).prop("checked") == true) {
        check = 1;
        $('#inv-cb').prop('checked', false);
        $("#inv_no").attr('disabled', 'disabled');
        $("#inv_no").val('').change();
        $("#quo_no").removeAttr('disabled');
    }else{
        $("#quo_no").attr('disabled', 'disabled');
        $("#quo_no").val('').change();
    }

    var quo_no = $('#quo_no').val();
    var inv_no = $('#inv_no').val();
    deliveryOrderQuoInvCheck(quo_no, inv_no);
});

$(document).on('click','#inv-cb', function(e) {
    var value = $(this).val();
    var check = '0';
    if($(this).prop("checked") == true) {
        check = 1;
        $('#quo-cb').prop('checked', false);
        $("#quo_no").attr('disabled', 'disabled');
        $("#quo_no").val('').change();
        $("#inv_no").removeAttr('disabled');
    }else{
        $("#inv_no").attr('disabled', 'disabled');
        $("#inv_no").val('').change();
    }

    var quo_no = $('#quo_no').val();
    var inv_no = $('#inv_no').val();
    deliveryOrderQuoInvCheck(quo_no, inv_no);
});

$(document).on('click','#po-cb', function(e) {
    if($(this).prop("checked") == true) {
        $("#po_no").removeAttr('readonly');
    }else{
        $("#po_no").attr('readonly', 'readonly');
    }
});

$(document).on('select2:select select2:unselect select2:clear', '#quo_no', function(e){
    var quo_no = $(this).val();
    console.log('quo_no' + quo_no);
    deliveryOrderQuoInvCheck(quo_no, '');
});

$(document).on('select2:select select2:unselect select2:clear', '#inv_no', function(e){
    var inv_no = $(this).val();
    console.log('inv_no' + inv_no);
    deliveryOrderQuoInvCheck('', inv_no);
});

function deliveryOrderQuoInvCheck(quo_no, inv_no){
    var urlPath = url() + 'OfficeManagement/deliveryOrderQuoInvCheck?quo_no=' + quo_no + '&inv_no=' + inv_no;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {
            $('.inv-quo-group').html(data);
        },
    });
}

$(document).on('click','#invoiceTo', function(e) {
    if($(this).prop("checked") == true) {
        invoiceToCheck('','');
    }else{
        if($('.quo-select').length){
            var quoId = $('.quo-select').val();
            console.log('quo chnge' + quoId);
            if(quoId != ''){
                quo_attn_on_change(quoId);
            }else{
                attn_on_change('', '');
                $('#sub').val('');
                $('#sub').removeAttr('readonly');
                $('.currency').removeClass('readonly');
            }
        }
        if($('#inv-select').length){
            console.log('inv-select isset');
            var invId = $('#inv-select').val();
            console.log('inv change' + invId);
            if(invId != ''){
                inv_attn_on_change(invId);
            }else{
                attn_on_change('', '');
                $('#sub').val('');
                $('#sub').removeAttr('readonly');
                $('.currency').removeClass('readonly');
                $('.payment').removeClass('readonly');
            }
        }
    }
});

$(document).on('select2:select select2:unselect select2:clear','.inv-customer', function(e) {
    var attn = $(this).val();
    console.log('attn ' + attn);
    invoiceToCheck(attn, '');
});

$(document).on('select2:select select2:unselect select2:clear','.inv-company', function(e) {
    var company = $(this).val(); 
    invoiceToCheck('', company);
});

function invoiceToCheck(attn, company){
    var urlPath = url() + 'OfficeManagement/invoiceToCheck?attn=' + attn+ '&company=' + company;
    $.ajax({
        type: 'GET',
        url: urlPath,
        success: function(data) {
            $('.attn-form').html(data);
            $('.select2').select2({
                tags: true,
                placeholder: "Select an Option",
                allowClear: true,
                width: '100%'
            });
        },
    });
}

$(document).on('click', '.receivedButton', function (){
    $('#receivedId').val($(this).data('id'));
    $('#typeId').val($(this).data('type'));
});

$(document).on('click', '#info-gear', function (){
    $('.info-form').removeClass('hidden');
    $('.info-data').addClass('hidden');
});
$(document).on('click', '#info-cancel', function (){
    $('.info-data').removeClass('hidden');
    $('.info-form').addClass('hidden');
});

$(document).on('click', '#psw-gear', function (){
    $('.psw-form').removeClass('hidden');
    $('.psw-data').addClass('hidden');
});
$(document).on('click', '#psw-cancel', function (){
    $('.psw-form').addClass('hidden');
    $('.psw-data').removeClass('hidden');
});

$(document).on('click', '#email-gear', function (){
    $('.email-form').removeClass('hidden');
    $('.email-data').addClass('hidden');
});
$(document).on('click', '#email-cancel', function (){
    $('.email-data').removeClass('hidden');
    $('.email-form').addClass('hidden');
});
