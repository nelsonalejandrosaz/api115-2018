var numero = 2;

$(document).on('ready', funcionPrincipal());

function funcionPrincipal() {
    $("body").on("click", ".btn-danger", fEliminarProducto);
    agregarFuncion();
}

function funcionNuevoProducto() {
    copia = $('#selectProductos').clone(false);
    $('#tblProductos')
        .append
        (
            $('<tr>').attr('id', 'rowProducto' + numero)
                .append
                (
                    $('<td>')
                        .append
                        (
                            numero
                        )
                )
                .append
                (
                    $('<td>')
                        .append
                        (
                            copia
                        )
                )
                .append
                (
                    $('<td>')
                        .append
                        (
                            '<div class="input-group"><input class="form-control" type="number" placeholder="100" name="cantidades[]" required><span class="input-group-addon unimed" id="spamUM">---</span></div>'
                        )
                )
                .append
                (
                    $('<td>')
                        .append
                        (
                            '<div class="input-group"><span class="input-group-addon">$</span><input type="number" class="form-control" placeholder="100" name="valoresTotales[]" required></div>'
                        )
                )
                .append
                (
                    $('<td>').attr('align', 'center')
                        .append
                        (
                            '<button type="button" class="btn btn-danger" type="button"><span class="fa fa-remove"></span></button>'
                        )
                )
        );
    //Initialize Select2 Elements
    $(".select2").select2();
    $(".select2").select2();
    numero++;
    // console.log('btn agregar');
    agregarFuncion();
}

function fEliminarProducto() {
    // $(this).remove().end();
    // $(this).closest('tr').remove();
    $(this).parent().parent().remove();
    numero--;
}

function agregarFuncion() {
    $('.selProd').each(
        function (index, value) {
            $(this).change(unidadMedida);
        });
}

function unidadMedida() {
    idSelect = $(this).parent().parent().find('#selectProductos').val();
    // console.log(idSelect);
    // unidadMedida = $(this).find('option[value="'+idSelect+'"]').data('um');
    // console.log($(this).find('option[value="'+idSelect+'"]').data('um'));
    // $(this).parent().parent().find('#spamUM').text(unidadMedida);
    um = $(this).find('option[value="' + idSelect + '"]').data('um');
    // console.log($(this).parent().parent().find('#spamUM').text(um));
    $(this).parent().parent().find('#spamUM').text(um)
}

