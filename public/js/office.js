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
    var date_input = $('input[name="date"]'); //our date input has the name "date"
    var container = $('.bootstrap-iso form').length > 0 ? $('.bootstrap-iso form').parent() : "body";
    date_input.datepicker({
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
    })
})