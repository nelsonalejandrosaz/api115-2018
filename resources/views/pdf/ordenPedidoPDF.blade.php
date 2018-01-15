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
</style>
<table class="tg">
    <tr>
        <th class="tg-0vnf" colspan="2">LGL S.A. DE C.V.</th>
        <th class="tg-huh2" colspan="3">ORDEN DE PEDIDO</th>
        <th class="tg-kr94"></th>
        <th class="tg-kr94">N°</th>
        <th class="tg-rg0h">{{str_pad($ordenPedido->id, 5, '0', STR_PAD_LEFT)}}</th>
    </tr>
    <tr>
        <td class="tg-kr94">FECHA</td>
        <td class="tg-kr94" colspan="3">{{$ordenPedido->fechaIngreso->format('d/m/Y')}}</td>
        <td class="tg-kr94">FECHA ENTREGA</td>
        @if($ordenPedido->fechaEntrega != null)
            <td class="tg-kr94" colspan="3">{{$ordenPedido->fechaEntrega->format('d/m/Y')}}</td>
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
        <td class="tg-rg0h" colspan="3">{{$ordenPedido->municipio->nombre}}</td>
        <td class="tg-rg0h">CONDICIÓN DE PAGO</td>
        <td class="tg-rg0h" colspan="3">{{$ordenPedido->condicionPago}}</td>
    </tr>
    <tr>
        <td class="tg-rg0h">DIRECCIÓN</td>
        <td class="tg-rg0h" colspan="4">{{$ordenPedido->direccion}}</td>
        <td class="tg-rg0h"></td>
        <td class="tg-rg0h"></td>
        <td class="tg-rg0h"></td>
    </tr>
    <tr>
        <td class="tg-rg0h" colspan="8"></td>
    </tr>
    <tr>
        <td class="tg-rg0h">CÓDIGO</td>
        <td class="tg-rg0h">UNIDAD <br>MEDIDA</td>
        <td class="tg-rg0h">CANTIDAD</td>
        <td class="tg-rg0h" colspan="2">DESCRIPCION</td>
        <td class="tg-rg0h">PRECIO <br>UNITARIO</td>
        <td class="tg-rg0h">VENTAS <br>EXENTAS</td>
        <td class="tg-rg0h">VENTAS<br>GRAVADAS</td>
    </tr>
    @foreach($ordenPedido->salidas as $salida)
        <tr>
            <td class="tg-rg0h">{{$salida->movimiento->producto->codigo}}</td>
            <td class="tg-rg0h">{{$salida->unidadMedida->abreviatura}}</td>
            <td class="tg-rg0h">{{$salida->cantidadOP}}</td>
            <td class="tg-rg0h" colspan="2">{{$salida->movimiento->producto->nombre}}</td>
            <td class="tg-rg0h">{{number_format($salida->precioUnitarioOP,5)}}</td>
            <td class="tg-rg0h">{{number_format($salida->ventaExenta,2)}}</td>
            <td class="tg-rg0h">{{number_format($salida->ventaGravada,2)}}</td>
        </tr>
    @endforeach
    <tr>
        <td class="tg-rg0h">SON</td>
        <td class="tg-rg0h" colspan="5">{{$ordenPedido->ventaTotalLetras}}</td>
        <td class="tg-rg0h">SUBTOTAL</td>
        <td class="tg-rg0h">{{number_format($ordenPedido->ventasGravadas,2)}}</td>
    </tr>
    <tr>
        <td class="tg-rg0h" colspan="6" rowspan="2"></td>
        <td class="tg-rg0h">V. EXENTAS</td>
        <td class="tg-rg0h">{{number_format($ordenPedido->ventasExentas,2)}}</td>
    </tr>
    <tr>
        <td class="tg-rg0h">VENTAS TOTAL</td>
        <td class="tg-rg0h">{{number_format($ordenPedido->ventaTotal,2)}}</td>
    </tr>
</table>
</body>
</html>
