$(document).ready(init);

function init()
{
    $("#modal").dialog({
        show:{
            effect: 'bounce',
            duration: 1000
        },
        opacity: 1,
        modal: true,
    });
    $('.ui-dialog-titlebar-close').remove();

}
