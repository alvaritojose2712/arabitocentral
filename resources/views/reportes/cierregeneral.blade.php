<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reporte Diario | CENTRAL</title>
	<style type="text/css">
		table, td, th {  
			border: 1px solid #ddd;
			text-align: center;
		}
		th{
			font-size:15px;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}
		.bg-white{
			background-color: white;
		}
		
		.long-text{
			width: 400px;
		}
		.border-top{
			border-top: 5px solid #000000;

		}

        .text-center{
			text-align: center !important;
		}
		.right{
			text-align: right !important;
		}

		.left{
			text-align: left !important;
		}
		
		.margin-bottom{
			margin-bottom:5px;
		}
		.amarillo{
			background-color:#FFFF84;
		}
		.verde{
			background-color:#84FF8D;
		}
		.rojo{
			background-color:#FF8484;
		}
		.sin-borde{
			border:none;
		}
		.text-sinapsis{
			background: #ed8207;
		}
		.text-warning{
			background: yellow;
		}
		.text-primary{
			background: rgb(109, 172, 255);
		}
		
		.text-success{
			background: green;
			color: white;
		}
		.text-danger{
			background: rgb(230, 0, 0);
			color: white;
		}
		.text-success-only{
			color: green;
		}
		.fs-3{
			font-size: xx-large;
			font-weight: bold;
		}

		.table-dark{
			background-color: #f2f2f2;
		}
		.container{
			width: 100%;
		}
		h1{
			font-size:3em;
		}
		.d-flex div{
			display: inline-block;
		}
		
		.tr-striped:nth-child(odd) {
            background-color: #8F9AA5;
        }
		.bg-danger-light{
			background-color: #fec7d1 !important; 
		}
		.bg-success-light{
			background-color: #c4f7c7 !important; 
		}

		

	</style>


</head>
<body>
    <div class="text-center">
        <h2>CIERRE DIARIO CENTRAL </h2>
        <h3>{{$fecha}}</h3>
        <h3>{{$numsucursales}} SUCURSALES</h3>
    </div>
	<div class="text-sinapsis text-center">
		<h2>INVENTARIO</h2>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th class="text-sinapsis">INVENTARIO BASE</th>
				<th class="text-success">INVENTARIO VENTA</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text-sinapsis">{{moneda($inventariobase)}}</td>
				<td class="text-success">{{moneda($inventarioventa)}}</td>
			</tr>
		</tbody>
	</table>


	<div class="bg-success-light text-center">
		<h2>INGRESOS</h2>
	</div>
	<table class="table">
		<thead>
			<tr>

				<th>CRÉDITO BANCARIO</th>
				<th>EFECTIVO</th>
				<th>DÉBITO</th>
				<th>TRANSFERENCIA</th>
				<th>BIOPAGO</th>
				<th>TOTAL</th>
				<td>VENTAS</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text-primary">$ {{moneda($ingreso_credito)}}</td>
				<td class="text-success">$ {{moneda($efectivo)}}</td>
				<td class="text-success">$ {{moneda($debito)}}</td>
				<td class="text-success">$ {{moneda($transferencia)}}</td>
				<td class="text-success">$ {{moneda($biopago)}}</td>
				<td class="text-success">$ {{moneda($efectivo+$debito+$transferencia+$biopago)}}</td>

				<td rowspan="4">
					{{$numventas}}
					<br>
					<b>
						{{moneda(($efectivo+$debito+$transferencia+$biopago)/$numventas)}}
					</b>
				</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td class="text-sinapsis">Bs. {{moneda($debitobs)}}</td>
				<td class="text-sinapsis">Bs. {{moneda($transferenciabs)}}</td>
				<td class="text-sinapsis">Bs. {{moneda($biopagobs)}}</td>
				<td class="text-sinapsis">Bs. {{moneda($debitobs+$transferenciabs+$biopagobs)}}</td>
			</tr>
			
			<tr>

				<th colspan="3">GANANCIA BRUTA</th>
				<th colspan="3">GANANCIA NETA</th>
			</tr>
			<tr>
				<td colspan="3">{{moneda($utilidadbruta)}}</td>
				<td colspan="3">{{moneda($utilidadneta)}}</td>
			</tr>

		</tbody>
		<tbody>
			@foreach ($ingresosData as $e)
				<tr>
					<th rowspan="2" class="text-right">{{$e["sucursal"]["codigo"]}}</th>
					<td class="text-success">
						$ {{moneda($e["efectivo"])}}
					</td>
					<td class="text-success">
						$ {{moneda($e["debito"])}} <br>
					</td>
					<td class="text-success">
						$ {{moneda($e["transferencia"])}} <br>
					</td>
					<td class="text-success">
						$ {{moneda($e["caja_biopago"])}} <br>
					</td>
	
					<td class="text-success">
						$ {{moneda($e["total"])}} <br>
					</td>
					<td></td>
					
				</tr>
				<tr>
					<td></td>
					<td class="text-sinapsis">
						Bs. {{moneda($e["puntodeventa_actual_bs"])}}
					</td>
					<td class="text-sinapsis">
						Bs. {{moneda($e["transferenciabs"])}}
					</td>
					<td class="text-sinapsis">
						Bs. {{moneda($e["biopagoserialmontobs"])}}
					</td>
					<td class="text-sinapsis">
						Bs. {{moneda($e["biopagoserialmontobs"]+$e["puntodeventa_actual_bs"]+$e["transferenciabs"])}}
					</td>
					<td>
						{{$e["numventas"]}} <br>
						<b>{{moneda($e["total"]/$e["numventas"])}}</b>
					</td>

				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="text-danger text-center">
		<h2>EGRESOS</h2>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th colspan="6">
					<span class="text-warning">
						FDI, GASTOS, INTERESES
					</span>
				</th>
			</tr>
			<tr>
				<th>FDI</th>
				<th class="text-warning">GASTOS FIJOS</th>
				<th class="text-warning">GASTOS VARIABLES</th>
				<th class="text-warning">TOTAL GASTO</th>
			</tr>
			<tr>
				<td>$ {{moneda($fdi)}}</td>
				<td>$ {{moneda($gastofijo)}}</td>
				<td>$ {{moneda($gastovariable)}}</td>
				<td>$ {{moneda($gastofijo+$gastovariable)}}</td>
			</tr>
		</thead>
		<tbody>
			@foreach ($gastos as $ingreso_egresokey => $ingreso_egreso)
				{{-- <tr>
					<td>{{$ingreso_egresokey}}</td>
				</tr> --}}

				@foreach ($ingreso_egreso as $catgeneralkey => $catgeneral)
					{{-- <tr>
						<td></td>
						<td>{{$catgeneralkey}}</td>
					</tr> --}}
					@foreach ($catgeneral as $variable_fijokey => $variable_fijo)
						<tr>
							<td></td>
							<td>FIJOS {{$variable_fijokey}}</td>
							<td>VARIABLE {{$variable_fijokey}}</td>
							<td></td>
						</tr>
						@if (isset($variable_fijo[1]))

							@foreach ($variable_fijo[1] as $catkey => $cat)
								<tr>
									<td></td>
									<td>{{$catkey}}</td>
									<td></td>
									<td></td>
								</tr>
							@endforeach
						@endif

						@if (isset($variable_fijo[0]))
							@foreach ($variable_fijo[0] as $catkey => $cat)
								<tr>
									<td></td>
									<td></td>
									<td>{{$catkey}}</td>
									<td></td>
								</tr>
							@endforeach
							
						@endif
					@endforeach
				
				
				@endforeach
			@endforeach
		</tbody>
		<tbody>
			
			<tr>
				<th class="text-primary">CUOTA CRÉDITO</th>
				<th class="text-primary">COMISIÓN CRÉDITO</th>
				<th class="text-primary">INTERÉS CRÉDITO</th>
				<th class="text-primary">TOTAL</th>
			</tr>
			<tr>
				<td>$ {{moneda($cuotacredito)}}</td>
				<td>$ {{moneda($comisioncredito)}}</td>
				<td>$ {{moneda($interescredito)}}</td>
				<td>$ {{moneda($cuotacredito+$comisioncredito+$interescredito)}}</td>
			</tr>
		</tbody>
		
	</table>
	<table class="table">
		<tbody>
			<tr>
				<th colspan="6">
					<span class="text-danger">
						PAGO A PROVEEDOR
					</span>
				</th>
			</tr>
			<tr>
				<th>EFECTIVO</th>
				<th>BANCO BS</th>
				<th>BANCO DIVISA</th>

				<th>TOTAL BRUTO</th>
				<th>TOTAL NETO</th>

				<th class="text-danger">PÉRDIDA</th>
			</tr>
			<tr>
				<td>$ {{moneda($pagoproveedor)}}</td>
				<td>
					$ {{moneda($pagoproveedorbs)}} <br>
					Bs. {{moneda($pagoproveedorbsbs)}} <br>
					Bs/$ PROM. {{moneda($pagoproveedortasapromedio)}}
				</td>
				<td>$ {{moneda($pagoproveedorbancodivisa)}}</td>
				<td>$ {{moneda($pagoproveedor+$pagoproveedorbs+$pagoproveedorbancodivisa)}}</td>
				<td>$ {{moneda($pagoproveedor+$pagoproveedorbs+$pagoproveedorbancodivisa+$perdidatasa)}}</td>
				<td class="text-danger">$ {{moneda($perdidatasa)}}</td>
			</tr>
		</tbody>
	</table>
	<table class="table">
		<tbody>
			<tr>
				<th colspan="3">
					<span class="text-warning">
						PRÉSTAMOS
					</span>
				</th>
			</tr>
			<tr>
				<th>PRÉSTAMOS</th>
				<th>ABONOS</th>
				<th>TOTAL</th>
			</tr>
			<tr>
				<td>$ {{moneda($prestamos)}}</td>
				<td>$ {{moneda($abono)}}</td>
				<td>$ {{moneda($prestamos-$abono)}}</td>
			</tr>
		</tbody>
	</table>

</body>
</html>