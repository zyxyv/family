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
        success: function (data) {
            console.log(data['resp']);
        }
    })
}