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

    @page {
        /*margin: 0;*/
        margin-left: 1.4cm;
    }

    .tg {
        border-collapse: collapse;
        border-spacing: 0;
        border: none;
        font-family: sans-serif;
        font-size: 8px;
    }

    .tg tr {
        /*height: 0.5cm;*/
    }

    .tg td {
        border: 0px none;
        overflow: hidden;
        word-break: normal;
    }

    .tg th {
        border: 0px none;
        overflow: hidden;
        word-break: normal;
    }

    .tg .tg-cw2b {
        vertical-align: top
    }

</style>
<table class="tg">
    <tr style="height:1.3cm">
        <th class="tg-cw2b" style="width:2.0cm"></th>
        <th class="tg-cw2b" style="width:4.7cm"><br></th>
        <th class="tg-cw2b" style="width:0.7cm"><br></th>
        <th class="tg-cw2b" style="width:2.6cm"><br></th>
        <th class="tg-cw2b" style="width:1.4cm"><br></th>
        <th class="tg-cw2b" style="width:1.4cm"><br></th>
        <th class="tg-cw2b" style="width:1.4cm"><br></th>
        <th class="tg-cw2b" style="width:0.6cm"><br></th>
        <th class="tg-cw2b" style="width:0.6cm"><br></th>
        <th class="tg-cw2b" style="width:2.5cm"><br></th>
    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">{{$venta->ordenPedido->cliente->id}}</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="4">{{$venta->ordenPedido->cliente->nit}}</td>
        <td class="tg-cw2b" colspan="2"></td>
        <td class="tg-cw2b">{{$venta->fechaIngreso->format('d-m-Y')}}</td>
    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">{{strtoupper($venta->ordenPedido->cliente->nombre)}}</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="2"></td>
        <td class="tg-cw2b"></td>
    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="5">{{strtoupper($venta->ordenPedido->direccion)}}</td>
        <td class="tg-cw2b">59342</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">{{strtoupper($venta->ordenPedido->municipio->nombre)}}</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="3">{{strtoupper($venta->ordenPedido->condicionPago)}}</td>

    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="3">GIRO</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="3">{{strtoupper($venta->ordenPedido->vendedor->nombreCompleto)}}</td>
    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="3"></td>
    </tr>

    <tr style="height: 0.7cm">
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
    </tr>

    @php($i = 1)
    @foreach($venta->ordenPedido->salidas as $salida)
        <tr>
            <td class="tg-cw2b">{{strtoupper($salida->movimiento->producto->codigo)}}</td>
            <td class="tg-cw2b" colspan="3">{{strtoupper($salida->movimiento->producto->nombre)}}</td>
            <td class="tg-cw2b">{{strtoupper($salida->unidadMedida->abreviatura)}}</td>
            <td class="tg-cw2b">{{$salida->cantidad}}</td>
            <td class="tg-cw2b">$ {{number_format($salida->precioUnitario,2)}}</td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b">$ {{number_format($salida->ventaGravada,2)}}</td>
        </tr>
        @php($i++)
    @endforeach
    @for($i;$i<=12;$i++)
        <tr>
            <td class="tg-cw2b">--</td>
            <td class="tg-cw2b" colspan="3"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
        </tr>
    @endfor

    <tr style="height: 0.6cm">
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="5">{{$venta->ordenPedido->ventaTotalLetras}}</td>
        <td class="tg-cw2b">SUBTOTAL</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">$ {{number_format($venta->ordenPedido->ventasGravadas,2)}}</td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">13% IVA</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">$ {{number_format($venta->ordenPedido->porcentajeIVA,2)}}</td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">TOTAL</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">$ {{number_format($venta->ordenPedido->ventaTotal,2)}}</td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
    </tr>
</table>
</body>
</html>
