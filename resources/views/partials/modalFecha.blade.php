{{-- modal para eliminar --}}
<div class="modal modal-info fade" id="modalFecha" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ingrese el rango de fechas a consultar</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="consultarFecha" action="/" method="POST">
                        {{ csrf_field() }}

                        {{-- Fecha --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><b>Fecha inicio:</b></label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="fecha">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Fecha --}}
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label"><b>Fecha fin:</b></label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="fecha">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline" id="btnEnviar">Enviar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->