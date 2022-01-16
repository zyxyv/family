$(document).ready(init);

function init()
{
    $('#registration_form_birthname, #birthname, #album_date').datepicker({
        minYear: "1970",
        changeMonth: true,
        changeYear: true
    });
}