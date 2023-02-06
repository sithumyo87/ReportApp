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
    var date_input = $('.date-picker'); //our date input has the name "date"
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    date_input.datepicker({
        format: 'dd-mm-yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    })
    $('.select2').select2();

    tinymce.init({
        selector: 'textarea.tinymce-editor',
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount', 'image'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        content_css: '//www.tiny.cloud/css/codepen.min.css'
    });
})

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
    }else{
        attn_on_change('', '');
        $('#sub').val('');
        $('#sub').removeAttr('readonly');
        $('.currency').removeClass('readonly');
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
    }else{
        attn_on_change('', '');
        $('#sub').val('');
        $('#sub').removeAttr('readonly');
        $('.currency').removeClass('readonly');
        $('.payment').removeClass('readonly');
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

