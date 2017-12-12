console.log("Hola estoy vivo");
$('#modalEliminar').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var nombreObj = button.data('producto'); // Extract info from data-* attributes
    var idObj = button.data('id');
    var ruta = button.data('ruta');
    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
    var modal = $(this);
    modal.find('#mensaje').text('Realmente desea eliminar: ' + nombreObj);
    modal.find('#myform').attr("action", "/" + ruta +"/" + idObj);
})