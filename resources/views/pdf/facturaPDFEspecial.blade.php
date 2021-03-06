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
    <tr style="height:1.2cm">
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
        <td class="tg-cw2b"></td>{{--Codigo del cliente--}}
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="4">{{$venta->cliente->nit}}</td>
        <td class="tg-cw2b" colspan="2"></td>
        <td class="tg-cw2b">{{$venta->fecha->format('d-m-Y')}}</td>
    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">{{$venta->cliente->nombre}}</td>
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
        <td class="tg-cw2b" colspan="5">{{$venta->cliente->direccion}}</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">{{$venta->cliente->municipio->nombre}}</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="3">{{$venta->condicion_pago->nombre}}</td>

    </tr>

    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="3">{{$venta->cliente->giro}}</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b" colspan="3">{{$venta->vendedor->nombre}} {{$venta->vendedor->apellido}}</td>
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
    @foreach($venta->detalle_otras_ventas as $salida)
        <tr style="height: 1.2cm">
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b" colspan="3"><div style="width: 7.5cm">{{$salida->detalle}}</div></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b"></td>
            <td class="tg-cw2b">$ {{number_format($salida->venta_gravada,2)}}</td>
        </tr>
        @php($i++)
    @endforeach
    {{--@for($i;$i<=10;$i++)--}}
        {{--<tr>--}}
            {{--<td class="tg-cw2b">--</td>--}}
            {{--<td class="tg-cw2b" colspan="3"></td>--}}
            {{--<td class="tg-cw2b"></td>--}}
            {{--<td class="tg-cw2b"></td>--}}
            {{--<td class="tg-cw2b"></td>--}}
            {{--<td class="tg-cw2b"></td>--}}
            {{--<td class="tg-cw2b"></td>--}}
            {{--<td class="tg-cw2b"></td>--}}
        {{--</tr>--}}
    {{--@endfor--}}

    <tr style="height: 2cm">
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
        <td class="tg-cw2b" colspan="5">{{$venta->venta_total_letras}}</td>
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
        <td class="tg-cw2b">SUMA</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">$ {{$venta->suma}}</td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">FLETE</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">$ ({{$venta->flete}})</td>
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
        <td class="tg-cw2b">$ {{ $venta->suma - $venta->flete }}</td>
    </tr>
    <tr>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">COMISION</td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b"></td>
        <td class="tg-cw2b">$ {{number_format($venta->venta_total_con_impuestos,2)}}</td>
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
<script src="{{ asset('/plugins/jquery.min.js') }}" type="text/javascript"></script>
<script>
    $(document).ready(function () {
        window.print();
    });
</script>
</html>
