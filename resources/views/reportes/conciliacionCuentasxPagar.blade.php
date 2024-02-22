
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
        <div class="d-flex justify-content-center align-items-center">
            <div>
                <span class="h3 m-5">RELACIÓN DE DEUDA</span> 
            </div>
            <div>
                <span class="h1">
                    @if ($proveedor)
                        {{$proveedor->descripcion}}
                    @endif    
                </span> 
                <br>
                <b>
                    @php
                        echo date("d-m-Y H:i")
                    @endphp
                </b>

            </div>

        </div>
        <table class="table table-bordered table-striped">
            <tr>
                <th>ESTATUS</th>
                <th>PROVEEDOR</th>
                <th># FACT</th>
                <th>SUCURSAL</th>
                <th>EMISIÓN</th>
                <th>VENCIMIENTO</th>
                <th>MONTO</th>
            </tr>
            @foreach ($detalles as $cuenta)
                <tr>
                    <th>
                        <button class="w-100 btn @if ($cuenta->condicion=="pagadas") btn-medsuccess @endif @if ($cuenta->condicion=="vencidas") btn-danger @endif @if ($cuenta->condicion=="porvencer") btn-sinapsis @endif @if ($cuenta->condicion=="semipagadas") btn-primary @endif @if ($cuenta->condicion=="abonos") btn-success @endif"> 
                            {{$cuenta->condicion}}
                        </button>
                    </th>
                    <th>{{$cuenta->proveedor->descripcion}}</th>
                    <td>
                        {{$cuenta->numfact}}
                    </td>
                    <td>
                        {{$cuenta->sucursal->codigo}}
                    </td>
                    <td>
                        {{$cuenta->fechaemision}}
                    </td>
                    <td>
                        {{$cuenta->fechavencimiento}}
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

