function getSocial(id) {
  var Ruta = Routing.generate('getSocial');
  var RutaRetorno = Routing.generate('default');
  $.ajax({
    type: "POST",
    url: Ruta,
    data: ({id: id}),
    async: true,
    dataType: "json",
    success: function (data) {
      window.location.href = RutaRetorno;
    }
  })
}

function invitationAcept(id) {
  var Ruta = Routing.generate('invitationAcept');
  $.ajax({
    type: "POST",
    url: Ruta,
    data: ({id: id}),
    async: true,
    dataType: "json",
    success: function (data) {
      window.location = window.location.href;
    }
  })
}


