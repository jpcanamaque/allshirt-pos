$(function () {
    $("#tabs").tabs();

    $("input#daily_datepicker").datepicker();

    $('#daily_report_tbl').DataTable({
        "paging": false,
        "info": false,
    });
});
