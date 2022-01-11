function passInvitations(social) {
    var Ruta = Routing.generate('passInvitation');
    let emails = $('#emails').val();
    $.ajax({
        type: "POST",
        url: Ruta,
        data: ({emails: emails,
                social: social}),
        async: true,
        dataType: "json",
        success: function () {
            const html = "<div class='container alert alert-success'>Invitaciones enviadas con exito.</div>";
            const content = $('.content');
            $('#emails').val('');
            content.before(html);
        }
    })
}