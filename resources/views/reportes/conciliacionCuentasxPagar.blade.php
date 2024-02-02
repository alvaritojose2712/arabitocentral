
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
    <title>RELACIÓN DE DEUDA</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   
</head>
<body>
    <section class="container-fluid">
        <h3 class="text-center">RELACIÓN DE DEUDA</h3>
        <table class="table table-bordered table-striped">
            <tr>
                <th>PROVEEDOR</th>
                <th>EMISIÓN</th>
                <th>VENCIMIENTO</th>
                <th>SUCURSAL</th>
                <th># FACT</th>
                <th>MONTO</th>
            </tr>
            @foreach ($detalles as $cuenta)
                <tr>
                    <th>{{$cuenta->proveedor->descripcion}}</th>
                    <td>
                        {{$cuenta->fechaemision}}
                    </td>
                    <td>
                        {{$cuenta->fechavencimiento}}
                    </td>
                    <td>
                        {{$cuenta->sucursal->codigo}}
                    </td>
                    <td>
                        {{$cuenta->numfact}}
                    </td>
                    <td class="fs-4 text-right">
                        {{number_format($cuenta->monto,2)}}
                    </td>
                </tr>
            @endforeach
        
        </table>
        
    </section> 
    
    
</body>
</html>

