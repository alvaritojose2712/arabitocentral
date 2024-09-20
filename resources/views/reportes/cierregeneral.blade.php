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
		.text-warning{
			background: yellow;
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
	{{-- 
	cxp
	cxc
	prestamos
	abono
	
	perdidatasa
	pagoproveedor
	pagoproveedorbs
	pagoproveedorbancodivisa
	gastofijo
	gastovariable
	fdi
	ingreso_credito
	efectivo
	debito
	debitobs
	transferencia
	transferenciabs
	biopago
	biopagobs
	
	utilidadbruta
	utilidadneta
	cajaregistradora
	cajachica
	cajafuerte
	cajamatriz
	bancobs
	bancodivisa
	inventariobase
	inventarioventa
	numventas
	nomina
	numsucursales
	estado 
	--}}

    <div class="text-center">
        <h1>CIERRE DIARIO CENTRAL</h1>
        <h3>{{$fecha}}</h3>
    </div>

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
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="text-warning">$ {{moneda($ingreso_credito)}}</td>
				<td class="text-success">$ {{moneda($efectivo)}}</td>
				<td class="text-success">$ {{moneda($debito)}}</td>
				<td class="text-success">$ {{moneda($transferencia)}}</td>
				<td class="text-success">$ {{moneda($biopago)}}</td>
				<td class="text-success">$ {{moneda($efectivo+$debito+$transferencia+$biopago)}}</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td class="text-sinapsis">Bs. {{moneda($debitobs)}}</td>
				<td class="text-sinapsis">Bs. {{moneda($transferenciabs)}}</td>
				<td class="text-sinapsis">Bs. {{moneda($biopagobs)}}</td>
				<td class="text-sinapsis">Bs. {{moneda($debitobs+$transferenciabs+$biopagobs)}}</td>
			</tr>
		</tbody>
	</table>

	<div class="text-danger text-center">
		<h2>EGRESOS</h2>
	</div>
	<table class="table">
		<thead>
			<tr>
				<th>FDI</th>
				<th class="text-warning">GASTOS FIJOS</th>
				<th class="text-warning">GASTOS VARIABLES</th>
				<th class="text-warning">TOTAL GASTO</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>$ {{moneda($fdi)}}</td>
				<td>$ {{moneda($gastofijo)}}</td>
				<td>$ {{moneda($gastovariable)}}</td>
				<td>$ {{moneda($gastofijo+$gastovariable)}}</td>
			</tr>

			<tr>
				<th colspan="6">
					<span class="text-danger h2">
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

				<th class="text-danger">PERDIDA</th>
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
</body>
</html>