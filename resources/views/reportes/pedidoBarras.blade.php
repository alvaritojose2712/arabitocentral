<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.ico') }}">
    <title>Reporte pedido {{$pedido->id}}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
   
</head>
<body>
    <section class="container-fluid">
			<table class="table">
				<thead>
					<tr class="text-center">
						<td colspan="6">
							<img src="{{ asset('images/logo.png') }}" width="200px">
						</td>
					</tr>
				</thead>
				<tbody>
					<tr class="text-center">
						<td colspan="2">
							<h5>Comercializadora y Distribuidora El Arabito 222 F.P.</h5>
						</td>
						<td colspan="4">
							<b>RIF. V-21628222-8</b>
							<hr>
							<span>
								Domicilio Fiscal: Elorza, Apure
							</span>
						</td>
					</tr>
					<tr>
						<th colspan="2" class=""><h5>SUCURSAL</h5> {{$pedido->sucursal->nombre}}</th>
						<th colspan="4" class="text-right"><h5>Fecha y hora de emisión</h5> {{$pedido->created_at}}</th>
					</tr>
					<tr>
						<th colspan="2" class="">NOTA ENTREGA</th>
						<th colspan="4" class="text-right text-danger">
							<h5>N° {{sprintf("%08d", $pedido->id)}}</h5>
								
								<img src="data:image/png;base64,{{$pedido->bar_pedido}}" alt="">
								
						</th>
					</tr>

					<tr class="tr-secondary">
						<th>
							Serial
						</th>
						<th>
							Código
						</th>
						<th>
							Descripción
						</th>
						<th>
							Cantidad
						</th>
						<th>
							P/U
						</th>
						<th class="text-right">
							Monto
						</th>
					</tr>
					@foreach ($pedido->items as $val)
						<tr class="tr-secondary">
							<td>
								<img src="data:image/png;base64,{{$val->bar}}" alt="">
							</td>
							<td>
								<small class="text-muted">{{$val->producto->codigo_proveedor}}</small><br>
								<small class="text-muted">{{$val->producto->codigo_barras}}</small>
							</td>
							<td>
								{{$val->producto->descripcion}} {{$val->producto->id}} 
							</td>
							<td>
								{{$val->cantidad}}
							</td>
							<td>
								{{$val->producto->precio}}
							</td>
							<td class="text-right">
								{{$val->monto}}
							</td>
						</tr>
					@endforeach

					<tr>
						<td class="text-right" colspan="4">Total:</td>
						<th class="text-right" colspan="">{{$pedido->venta}}</th>
					</tr>
				</tbody>

			</table>
        
    </section> 
    
    
</body>
</html>
