{{-- modal para eliminar --}}
<div class="modal modal-success fade" id="modalPrecio" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ingrese el nuevo precio del producto</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="nuevoPrecio" action="/" method="POST">
                        {{ csrf_field() }}

                        {{-- Precio anterior --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><b>Precio actual</b></label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input readonly type="number" class="form-control" name="precio_anterior" id="precioAnteriorID">
                                </div>
                            </div>
                        </div>

                        {{-- Precio nuevo --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><b>Precio nuevo</b></label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input required type="number" class="form-control" name="precio_nuevo">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline" id="btnEnviar">Asignar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->