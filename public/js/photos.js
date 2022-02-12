$(document).ready(function() {
    const id = $('.card').attr('data-id');
    var Ruta = Routing.generate('media', {'id': id});
    $(".upload").on('click', function() {
        var formData = new FormData();
        var html = "";

        for (let i = 0; i < $('#image')[0].files.length; i++)
        {
            var files = $('#image')[0].files[i];
            formData.append('file',files);
            $.ajax({
                url: Ruta,
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response != 0 && response['resp'] != '') {
                        html += "<div id='cromo"+response['id']+"' class='photoprev'>" +
                            "<div class='close'>" +
                            "<a class='deletePhoto' onclick='deletePhoto("+response['id']+")'>X</a>" +
                            "</div>" +
                            "<img src='"+response['resp']+"' alt='img'></div>";
                        $('.minis').html(html);
                        $('input#image').val('');
                        setTimeout(function(){
                            window.history.back();
                        },3000)
                    } else if(response['resp'] == '' && response['sop'] != '') {
                        html += "<div class='alert alert-danger' role='alert'>"+response['sop']+"</div>";
                        $('.minis').html(html);
                    } else {
                        alert('Formato de imagen incorrecto.');
                    }
                }
            });
        }

        return false;
    });
});

function deletePhoto(id)
{
    var Ruta = Routing.generate('mediaDel', {'id': id});
    $.ajax({
        url: Ruta,
        type: 'post',
        data: ({id:id}),
        contentType: false,
        processData: false,
        success: function(response) {
            if (response != 0 && response['resp'] != '') {
                $('#cromo'+id).remove();
            }  else {
                alert('Algo falló');
            }
        }
    });
}