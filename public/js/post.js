$(document).ready(function(){
    $('#post_content').gre();
    $('.send').click(function(){
        if($('#post_content').val() === '')
        {
            if (!confirm('Está a punto de envíar un post vacío.¿Desea continuar?')) {
               return false;
            }
        }
    });
    $('#newPostButton').click(function(){
        $('#newPostForm').toggle();
        console.log($('.frameBody').length);
    });
});
