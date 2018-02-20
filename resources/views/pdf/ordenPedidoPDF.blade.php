<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<style type="text/css">
    .tg {
        border-collapse: collapse;
        border-spacing: 0;
        border-color: #ccc;
        border: solid;
        border-width: 1px
    }

    .tg td {
        font-family: Arial, sans-serif;
        font-size: 14px;
        padding: 5px 11px;
        border-style: solid;
        border-width: 0px;
        overflow: hidden;
        word-break: normal;
        border-color: #ccc;
        color: #333;
        background-color: #fff;
    }

    .tg th {
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: normal;
        padding: 5px 11px;
        border-style: solid;
        border-width: 0px;
        overflow: hidden;
        word-break: normal;
        border-color: #ccc;
        color: #333;
        background-color: #f0f0f0;
    }

    .tg .tg-0vnf {
        font-size: 15px;
        text-align: center
    }

    .tg .tg-kr94 {
        font-size: 12px;
        text-align: center
    }

    .tg .tg-huh2 {
        font-size: 14px;
        text-align: center
    }

    .tg .tg-rg0h {
        font-size: 12px;
        text-align: center;
        vertical-align: top
    }
    .page-break {
        page-break-after: always;
    }
</style>
<table class="tg" style="width: 100%">
    <tr>
        <th class="tg-0vnf" colspan="2">LGL S.A. DE C.V.</th>
        <th class="tg-huh2" colspan="3">ORDEN DE PEDIDO VENDEDOR</th>
        <th class="tg-kr94"></th>
        <th class="tg-kr94">N°</th>
        <th class="tg-rg0h">{{str_pad($ordenPedido->id, 5, '0', STR_PAD_LEFT)}}</th>
    </tr>
    <tr>
        <td class="tg-kr94">FECHA</td>
        <td class="tg-kr94" colspan="3">{{$ordenPedido->fecha->format('d/m/Y')}}</td>
        <td class="tg-kr94">FECHA ENTREGA</td>
        @if($ordenPedido->fecha_entrega != null)
            <td class="tg-kr94" colspan="3">{{$ordenPedido->fecha_entrega->format('d/m/Y')}}</td>
        @else
            <td class="tg-kr94" colspan="3">Sin fecha entrega</td>
        @endif
    </tr>
    <tr>
        <td class="tg-kr94">CLIENTE</td>
        <td class="tg-kr94" colspan="3">{{$ordenPedido->cliente->nombre}}</td>
        <td class="tg-kr94">VENDEDOR</td>
        <td class="tg-kr94" colspan="3">{{$ordenPedido->vendedor->nombre}} {{$ordenPedido->vendedor->apellido}}</td>
    </tr>
    <tr>
        <td class="tg-rg0h">MUNICIPIO</td>
        <td class="tg-rg0h" colspan="3">{{$ordenPedido->cliente->municipio->nombre}}</td>
        <td class="tg-rg0h">CONDICIÓN DE PAGO</td>
        <td class="tg-rg0h" colspan="3">{{$ordenPedido->condicion_pago->nombre}}</td>
    </tr>
    <tr>
        <td class="tg-rg0h">DIRECCIÓN</td>
        <td class="tg-rg0h" colspan="4">{{$ordenPedido->cliente->direccion}}</td>
        <td class="tg-rg0h"></td>
        <td class="tg-rg0h">TIPO DOCUMENTO</td>
        <td class="tg-rg0h">{{$ordenPedido->tipo_documento->codigo}}</td>
    </tr>
    <tr>
        <td class="tg-rg0h" colspan="8"></td>
    </tr>
    <tr>
        <td class="tg-rg0h">CÓDIGO</td>
        <td class="tg-rg0h">UNIDAD <br>MEDIDA</td>
        <td class="tg-rg0h" colspan="3">DESCRIPCION</td>
        <td class="tg-rg0h">PRECIO <br>UNITARIO</td>
        <td class="tg-rg0h">VENTAS <br>EXENTAS</td>
        <td class="tg-rg0h">VENTAS<br>GRAVADAS</td>
    </tr>
    @foreach($ordenPedido->salidas as $salida)
        <tr>
            <td class="tg-rg0h">{{$salida->movimiento->producto->codigo}}</td>
            <td class="tg-rg0h">{{$salida->cantidad}} {{$salida->unidad_medida->abreviatura}}</td>
            @if($salida->descripcion_presentacion != null)
                <td class="tg-rg0h" colspan="3">{{$salida->movimiento->producto->nombre}} ({{$salida->descripcion_presentacion}})</td>
            @else
                <td class="tg-rg0h" colspan="3">{{$salida->movimiento->producto->nombre}}</td>
            @endif
            <td class="tg-rg0h">{{number_format($salida->precio_unitario,4)}}</td>
            <td class="tg-rg0h">{{number_format($salida->venta_exenta,2)}}</td>
            <td class="tg-rg0h">{{number_format($salida->venta_gravada,2)}}</td>
        </tr>
    @endforeach

    @if($ordenPedido->tipo_documento->codigo == 'CCF')
        {{--<tr>--}}
            {{--<td class="tg-rg0h" colspan="6" rowspan="2"></td>--}}
            {{--<td class="tg-rg0h">V. EXENTAS</td>--}}
            {{--<td class="tg-rg0h">{{number_format($ordenPedido->ventas_exentas,2)}}</td>--}}
        {{--</tr>--}}
        <tr>
            <td class="tg-rg0h">SON</td>
            <td class="tg-rg0h" colspan="5">{{$ordenPedido->ventaTotalLetras}}</td>
            <td class="tg-rg0h">SUBTOTAL</td>
            <td class="tg-rg0h">{{number_format($ordenPedido->venta_total,2)}}</td>
        </tr>
        <tr>
            <td class="tg-rg0h" colspan="6" rowspan="2"></td>
            <td class="tg-rg0h">13% IVA</td>
            <td class="tg-rg0h">{{number_format(($ordenPedido->iva),2)}}</td>
        </tr>
        <tr>
            {{--<td class="tg-rg0h" colspan="6" rowspan="2"></td>--}}
            <td class="tg-rg0h">VENTAS TOTAL</td>
            <td class="tg-rg0h">{{number_format(($ordenPedido->venta_total_con_iva),2)}}</td>
        </tr>
    @else
        <tr>
            <td class="tg-rg0h">SON</td>
            <td class="tg-rg0h" colspan="5">{{$ordenPedido->ventaTotalLetras}}</td>
            <td class="tg-rg0h">SUBTOTAL</td>
            <td class="tg-rg0h">{{number_format($ordenPedido->venta_total_con_iva,2)}}</td>
        </tr>
        <tr>
            <td class="tg-rg0h" colspan="6" rowspan="2"></td>
            <td class="tg-rg0h">VENTAS TOTAL</td>
            <td class="tg-rg0h">{{number_format(($ordenPedido->venta_total_con_iva),2)}}</td>
        </tr>
    @endif
</table>

{{--<div class="page-break"></div>--}}
<br>
<br>

<table class="tg" style="width: 100%">
    <tr>
        <th class="tg-0vnf" colspan="2">LGL S.A. DE C.V.</th>
        <th class="tg-huh2" colspan="3">ORDEN DE PEDIDO BODEGA</th>
        <th class="tg-kr94"></th>
        <th class="tg-kr94">N°</th>
        <th class="tg-rg0h">{{str_pad($ordenPedido->id, 5, '0', STR_PAD_LEFT)}}</th>
    </tr>
    <tr>
        <td class="tg-kr94">FECHA</td>
        <td class="tg-kr94" colspan="3">{{$ordenPedido->fecha->format('d/m/Y')}}</td>
        <td class="tg-kr94">FECHA ENTREGA</td>
        @if($ordenPedido->fecha_entrega != null)
            <td class="tg-kr94" colspan="3">{{$ordenPedido->fecha_entrega->format('d/m/Y')}}</td>
        @else
            <td class="tg-kr94" colspan="3">Sin fecha entrega</td>
        @endif
    </tr>
    <tr>
        <td class="tg-kr94">VENDEDOR</td>
        <td class="tg-kr94" colspan="3">{{$ordenPedido->vendedor->nombre}} {{$ordenPedido->vendedor->apellido}}</td>
        <td class="tg-kr94">HORA ENVIADO</td>
        <td class="tg-kr94" colspan="3">{{ \Carbon\Carbon::now()->format('d/m/Y h:i:s A') }}</td>
    </tr>
    <tr>
        <td class="tg-rg0h" colspan="8"></td>
    </tr>
    <tr>
        <td class="tg-rg0h">CÓDIGO</td>
        <td class="tg-rg0h">UNIDAD <br>MEDIDA</td>
        <td class="tg-rg0h">CANTIDAD</td>
        <td class="tg-rg0h" colspan="4">DESCRIPCION</td>
        <td class="tg-rg0h">CANTIDAD <br>DESPACHAR</td>
    </tr>
    @foreach($ordenPedido->salidas as $salida)
        <tr>
            <td class="tg-rg0h">{{$salida->movimiento->producto->codigo}}</td>
            <td class="tg-rg0h">{{$salida->unidad_medida->abreviatura}}</td>
            <td class="tg-rg0h">{{$salida->cantidad}}</td>
            @if($salida->descripcion_presentacion != null)
                <td class="tg-rg0h" colspan="4">{{$salida->movimiento->producto->nombre}} ({{$salida->descripcion_presentacion}})</td>
            @else
                <td class="tg-rg0h" colspan="4">{{$salida->movimiento->producto->nombre}}</td>
            @endif
            <td class="tg-rg0h">{{number_format($salida->movimiento->cantidad,3)}}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
