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
    var date_input = $('input[id="Date"]'); //our date input has the name "date"
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    date_input.datepicker({
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    })
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