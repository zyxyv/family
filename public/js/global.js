$(document).ready(init);

function init()
{
    $('#registration_form_birthname, #birthname').datepicker({
        minYear: "1970",
        changeMonth: true,
        changeYear: true
    });
}