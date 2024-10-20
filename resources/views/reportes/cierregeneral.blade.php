<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Reporte Diario | CENTRAL</title>
	<style type="text/css">
		body{
			zoom: 0.6;
		}
		table, td, th {  
			border: 1px solid #ddd;
			text-align: center;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}
		.bg-white{
			background-color: white;
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
	

		.table-dark{
			background-color: #f2f2f2;
		}
		.container{
			width: 100%;
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

		.w-10{
			width: 10% !important;
		}
		.w-30{
			width: 30% !important;
		}
		.w-40{
			width: 40% !important;
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
						{{moneda( dividir(($efectivo+$debito+$transferencia+$biopago),$numventas) )}}
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
						<b>{{moneda(dividir($e["total"],$e["numventas"]))}}</b>
					</td>

				</tr>
			@endforeach
		</tbody>
	</table>

	<div class="text-danger text-center">
		<h2>EGRESOS</h2>
	</div>
	<table class="table">
		<tbody>
			<tr>
				<th>FDI</th>
				<th>$ {{moneda($fdi)}}</th>
			</tr>
			@foreach ($fdidata as $e)
				<tr>
					<td>
						{{@$e["concepto"]}}
					</td>
					<td>
						{{moneda(abs(@$e["montodolar"]))}}
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
	<hr>
	<table class="table">
		<thead>
			<tr>
				<th colspan="6">
					<span class="text-warning">
						GASTOS, CUOTAS, INTERESES
					</span>
				</th>
			</tr>
			<tr>
				<th class="text-warning">GASTOS FIJOS</th>
				<th class="text-warning">GASTOS VARIABLES</th>
				<th class="text-warning">TOTAL GASTO</th>
			</tr>
			<tr>
				<th>$ {{moneda($gastofijo)}}</th>
				<th>$ {{moneda($gastovariable)}}</th>
				<th>$ {{moneda($gastofijo+$gastovariable)}}</th>
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
						{{-- <tr>
							<td></td>
							<td>FIJOS {{$variable_fijokey}}</td>
							<td>VARIABLE {{$variable_fijokey}}</td>
							<td></td>
						</tr> --}}
						@foreach ($variable_fijo as $catkey => $cat)
							<tr>

								@if ($variable_fijokey==0)
									<td></td>
								@endif

								<td>

									{{@$catcajas[$catkey][0]["nombre"]}}
								</td>

								@if ($variable_fijokey==1)
									<td></td>
								@endif

								<td>
									{{moneda(abs(@$sumArrcat[$catkey]["sumdolar"]))}}

									
								</td>
							</tr>
						@endforeach
							
					@endforeach
				
				
				@endforeach
			@endforeach
		</tbody>
	</table>
	<hr>
	<table class="table">
		<tbody>
			<tr>
				<td></td>
				<th class="text-primary">CUOTA CRÉDITO</th>
				<th class="text-primary">COMISIÓN CRÉDITO</th>
				<th class="text-primary">INTERÉS CRÉDITO</th>
				<th class="text-primary">TOTAL</th>
			</tr>
			<tr>
				<td></td>
				<th>$ {{moneda($cuotacredito)}}</th>
				<th>$ {{moneda($comisioncredito)}}</th>
				<th>$ {{moneda($interescredito)}}</th>
				<th>$ {{moneda($cuotacredito+$comisioncredito+$interescredito)}}</th>
			</tr>
			@foreach ($cuota_credito_data as $e)
				<tr>
					<td>
						{{@$e["loteserial"]}}
					</td>
					<td>
						{{moneda(abs(@$e["monto_dolar"]))}}
					</td>
				</tr>
			@endforeach

			@foreach ($comision_credito_data as $e)
				<tr>
					<td>
						{{@$e["loteserial"]}}
					</td>
					<td></td>
					<td>
						{{moneda(abs(@$e["monto_dolar"]))}}
					</td>
				</tr>
			@endforeach

			@foreach ($interes_credito_data as $e)
				<tr>
					<td>
						{{@$e["loteserial"]}}
					</td>
					<td></td>
					<td></td>
					<td>
						{{moneda(abs(@$e["monto_dolar"]))}}
					</td>
				</tr>
			@endforeach
		</tbody>

	</table>
	<hr>
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

				<th>TOTAL NETO</th>
				<th>TOTAL BRUTO</th>

				<th class="text-danger">PÉRDIDA</th>
			</tr>
			<tr>
				<td>$ {{moneda($pagoproveedor)}}</td>
				<td>
					$ {{moneda($pagoproveedorbs)}} <br>
					Bs. {{moneda($pagoproveedorbsbs)}} <br>
					Bs/$ PROM. {{moneda($pagoproveedortasapromedio,4)}}
				</td>
				<td>$ {{moneda($pagoproveedorbancodivisa)}}</td>
				<td>$ {{moneda($pagoproveedor+$pagoproveedorbs+$pagoproveedorbancodivisa)}}</td>
				<td>$ {{moneda($pagoproveedor+$pagoproveedorbs+$pagoproveedorbancodivisa+$perdidatasa)}}</td>
				<td class="text-danger">$ {{moneda($perdidatasa)}}</td>
			</tr>
		</tbody>
	</table>
	<hr>
	<table class="table table-bordered">
		<tbody>
			@foreach ($pagoproveedorData["detalles"] as $i => $pagosproveedor)
				
				<tr>
					<td>{{$i+1}}</td>
					<th>
						{{$pagosproveedor["proveedor"]["descripcion"]}}
					</th>
					<td>
						{{$pagosproveedor["fechaemision"]}}
					</td>
					<td>
						<table class="table w-100">
							<tbody>
								@if (isset($pagosproveedor["banco"]))
									@foreach ($pagosproveedor["banco"] as $ee)
										<tr class="fs-5">
											<th class="text-right w-10">BANCO</th>
											<td class="text-muted w-30">
												{{$ee["loteserial"]}}
											</td>
											<td class="w-10">
												{{$ee["banco"]}}
											</td>
											<td class="w-10">
												<span class="text-sinapsis">{{moneda($ee["tasa"])}}</span>
											</td>
											<td class="w-30">
												<span class="text-success">Bs. {{moneda($ee["monto"])}}</span>
											</td>
										</tr>

									@endforeach
								@endif

								@if (isset($pagosproveedor["efectivo"]))
									@foreach ($pagosproveedor["efectivo"] as $ee)
										<tr class="fs-5">
											<th class="text-right w-10">EFECTIVO </th>
											<td class="text-muted w-40">
												{{$ee["concepto"]}}
											</td>
											<td class="w-40">
												{{$ee["montodolar"]}}
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</td>
					<td>
						$ {{moneda($pagosproveedor["monto"])}}
					</td>
				</tr>
			
			@endforeach
		</tbody>
	</table>
	<hr>
	<table class="table">
		<tbody>
			<tr>
				<th colspan="3">
					<span class="text-warning">
						CUENTAS POR PAGAR (CxP)
					</span>
				</th>
			</tr>
			<tr>
				<td>$ {{moneda($cxp)}}</td>
			</tr>
		</tbody>
	</table>
	<hr>
	<table class="table">
		<tbody>
			<tr>
				<th colspan="3">
					<span class="text-warning">
						CUENTAS POR COBRAR (CxC)
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
		<tbody>
			<tr>
				<th colspan="3">
					CRÉDITOS (CxC)
				</th>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td>$ {{moneda($cxc)}}</td>

			</tr>
		</tbody>
	</table>

	<br>
	<hr>
	<br>

	<table class="table">
		<thead>
			<tr>
				<th colspan="10">
					<span class="text-warning">
						CONCILIACIÓN BANCO
					</span>
				</th>
			</tr>
			<tr>
				<th>BANCO</th>
				<th>FECHA</th>
				<th class="bg-success-light">CUADRE REAL</th>
				<th class="">YA REPORTADO</th>
				<th>SALDO INCIAL</th>
				<th>INGRESO</th>
				<th>NO REPORTADO <i class="fa fa-exclamation-triangle"></i></th>
				<th>EGRESO</th>
				
				<th class="bg-success-light">CUADRE DIGITAL</th>
				<th class="text-right">CONCILIACIÓN</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($bancoData["xfechaCuadre"] as $e)
				<tr>
					<th
						style="background-color:{{$e["background"]}}, color:{{$e["color"]}}"
					>
						{{$e["banco_codigo"]}}
					</th>
					<th>{{$e["fecha"]}}</th>
					<th class="bg-success-light">
						{{$e["guardado"]?moneda($e["guardado"]["saldo_real_manual"]):"----"}}
					</th>
					<th class="">{{moneda($e["sireportadasum"])}}</th>
					<td class="bg-warning-light">
						<b>{{moneda($e["inicial"])}}</b>
						<br />
						{{$e["fecha_inicial"]}}
					</td>
					<th class="bg-success-light">{{moneda($e["ingreso"])}}</th>
					<th class="">{{moneda($e["noreportadasum"])}}</th>
					<th class="bg-danger-light">{{moneda($e["egreso"])}}</th>
					
					<th class="bg-success-light">{{moneda($e["balance"])}}</th>
					<th class={{ ($e["cuadre"]>-200 && $e["cuadre"]<200?"text-success text-light":"text-danger text-light")." fs-3 text-right"}}> {{moneda($e["cuadre"])}} </th>

				</tr>
			@endforeach
		</tbody>
	</table>
	<hr>
		
		<table className="table">
			<thead>
				<tr>
					<th colspan="6">
						<span class="text-warning">
							BANCO
						</span>
					</th>
				</tr>
				<tr>
					<th></th>
					<th></th>
					<th>SALDO REAL BS</th>
					<th>PUNTOS LIQUIDADOS BS</th>
					<th>TOTAL Bs.</th>
					<th>$</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($caja_actual_banco as $e)
					<tr>
						<td>{{$e["fecha"]}}</td>
						<td>{{$e["banco"]}}</td>
						
						<td className="text-sinapsis">{{moneda($e["saldo_real"])}}</td>
						<td className="text-sinapsis">{{moneda($e["puntos_liquidados"])}}</td>
						<td>{{moneda($e["saldo"])}}</td>
						<td className="text-success">{{moneda($e["saldo_dolar"])}}</td>

					</tr>
					
				@endforeach
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th class="text-sinapsis">{{moneda($bancobs)}}</th>
					<th class="text-success">{{moneda($bancodivisa)}}</th>
				</tr>
			</tbody>
		</table>
	<hr>
	<table class="table">
		<thead>
			<tr>
				<th colspan="8">
					<span class="text-warning">
						EFECTIVO
					</span>
				</th>
			</tr>
			<tr>
				<th colspan="7" class="text-primary">CAJA MATRIZ</th>
				<td><h3>{{moneda($matriz_actual)}}</h3></td>
			</tr>
		</thead>
		<tbody>
			@foreach ($caja_actual as $su => $e)
				<tr>
					<td rowspan="4">{{$su}}</td>
					<td></td>
					<th>DÓLAR</th>
					<th>BS</th>
					<th>PESO</th>
					<th>EURO</th>
					<th>TOTAL $</th>
					<td rowspan="4">{{moneda($e["sum_cajas"])}}</td>
				</tr>
				<tr>
					<td class="text-warning">CAJA REGISTRADORA</td>
					<td>{{moneda($e["caja_registradora"]["dolar"])}}</td>
					<td>{{moneda($e["caja_registradora"]["bs"])}}</td>
					<td>{{moneda($e["caja_registradora"]["peso"])}}</td>
					<td>{{moneda($e["caja_registradora"]["euro"])}}</td>
					<td>{{moneda($e["caja_registradora"]["total_dolar"])}}</td>
				</tr>
				<tr>
					<td class="text-sinapsis">CAJA CHICA</td>
					<td>{{moneda($e["caja_chica"]["dolar"])}}</td>
					<td>{{moneda($e["caja_chica"]["bs"])}}</td>
					<td>{{moneda($e["caja_chica"]["peso"])}}</td>
					<td>{{moneda($e["caja_chica"]["euro"])}}</td>
					<td>{{moneda($e["caja_chica"]["total_dolar"])}}</td>
				</tr>
				<tr>
					<td class="text-success">CAJA FUERTE</td>
					<td>{{moneda($e["caja_fuerte"]["dolar"])}}</td>
					<td>{{moneda($e["caja_fuerte"]["bs"])}}</td>
					<td>{{moneda($e["caja_fuerte"]["peso"])}}</td>
					<td>{{moneda($e["caja_fuerte"]["euro"])}}</td>
					<td>{{moneda($e["caja_fuerte"]["total_dolar"])}}</td>
				</tr>
			@endforeach
			<tr>
				<td rowspan="5"></td>
				<td></td>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
				<th rowspan="5">{{moneda($sum_caja_actual+$matriz_actual)}}</th>
			</tr>
			<tr>
				<th colspan="5" class="text-warning">CAJA REGISTRADORA</th>
							
				<th>{{moneda($sum_caja_regis_actual)}}</th>
			</tr>
			<tr>

				<th colspan="5" class="text-sinapsis">CAJA CHICA</th>
				
				<th>{{moneda($sum_caja_chica_actual)}}</th>
			</tr>
			<tr>
				<th colspan="5" class="text-success">CAJA FUERTE</th>
				
				<th>{{moneda($sum_caja_fuerte_actual)}}</th>
			</tr>
			<tr>
				<th colspan="5" class="text-primary">CAJA MATRIZ</th>
				
				<th>{{moneda($matriz_actual)}}</th>
			</tr>
			
			
		</tbody>
	</table>
</body>
</html>