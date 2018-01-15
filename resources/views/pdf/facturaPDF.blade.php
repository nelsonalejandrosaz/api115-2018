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
    .tg  {border-collapse:collapse;border-spacing:0;border:none;}
    .tg td{font-family:sans-serif;font-size:14px;padding:4px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
    .tg th{font-family:sans-serif;font-size:14px;font-weight:normal;padding:4px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;}
    .tg .tg-iuhm{font-size:9px;font-family:Arial, Helvetica, sans-serif !important;;vertical-align:top}
    .tg .tg-cw2b{font-size:9px;font-family:Arial, Helvetica, sans-serif !important;}
</style>
<table class="tg">
    <tr style="height:3cm">
        <th class="tg-cw2b" style="width:1.5cm"><br><br><br><br><br><br><br><br><br></th>
        <th class="tg-cw2b" style="width:4cm"><br></th>
        <th class="tg-cw2b" style="width:0.6cm"><br></th>
        <th class="tg-cw2b" style="width:2.6cm"><br></th>
        <th class="tg-cw2b" style="width:1.4cm"><br></th>
        <th class="tg-cw2b" style="width:1.3cm"><br></th>
        <th class="tg-cw2b" style="width:1.3cm"><br></th>
        <th class="tg-cw2b" style="width:0.4cm"><br></th>
        <th class="tg-cw2b" style="width:0.4cm"><br></th>
        <th class="tg-iuhm" style="width:2.3cm"><br></th>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="4">{{$venta->ordenPedido->cliente->nit}}</td>
        <td class="tg-cw2b" colspan="2"></td>
        <td class="tg-iuhm">{{$venta->fechaIngreso->format('d-m-Y')}}</td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">{{$venta->ordenPedido->cliente->nombre}}</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="2">N° ORDEN</td>
        <td class="tg-iuhm">{{$venta->ordenPedido->numero}}</td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="6">{{strtoupper($venta->ordenPedido->direccion)}}</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-iuhm"></td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="3">{{strtoupper($venta->ordenPedido->condicionPago)}}</td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
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
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-iuhm"></td>
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
        <td class="tg-iuhm"></td>
    </tr>
    <tr>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-cw2b"><br></td>
        <td class="tg-iuhm"><br></td>
    </tr>
    @php($i = 1)
    @foreach($venta->ordenPedido->salidas as $salida)
        <tr>
            <td class="tg-cw2b">{{$salida->movimiento->producto->codigo}}</td>
            <td class="tg-cw2b" colspan="3">{{$salida->movimiento->producto->nombre}}</td>
            <td class="tg-cw2b">{{$salida->unidadMedida->abreviatura}}</td>
            <td class="tg-cw2b">{{$salida->cantidad}}</td>
            <td class="tg-cw2b">${{number_format($salida->precioUnitario,2)}}</td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-iuhm">${{number_format($salida->ventaGravada,2)}}</td>
        </tr>
        @php($i++)
    @endforeach
    @for($i;$i<=10;$i++)
        <tr>
            <td class="tg-cw2b">--</td>
            <td class="tg-cw2b" colspan="3"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-iuhm"></td>
        </tr>
    @endfor
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="5">{{$venta->ordenPedido->ventaTotalLetras}}</td>
        <td class="tg-cw2b">SUBTOTAL</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-iuhm">{{number_format($venta->ordenPedido->ventaTotal,2)}}</td>
    </tr>
    <tr>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
    </tr>
    <tr>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
    </tr>
    <tr>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm">TOTAL</td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm">{{number_format($venta->ordenPedido->ventaTotal,2)}}</td>
    </tr>
    <tr>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
        <td class="tg-iuhm"></td>
    </tr>
</table>
</body>
</html>
