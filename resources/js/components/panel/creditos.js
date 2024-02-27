export default function Creditos({
	getsucursalDetallesData,
	sucursalDetallesData,
	moneda,

}) {
	return (
		<div className="">
			<table className="table m-3 w-100 h-100">
				<thead>
					<tr>
						<th className="cell1">SUCURSAL</th>
						<th className="cell4" colSpan={2}>CLIENTE</th>
						<th className="cell2">
							SALDO
							<br />
							{sucursalDetallesData["num"]?
								<span className="bg-warning p-1">
									{moneda(sucursalDetallesData["num"])}
								</span>
							:null}
						</th>
						<th className="cell2">Fecha</th>
					</tr>
				</thead>
				<tbody>
				{sucursalDetallesData.data?sucursalDetallesData.data.length?sucursalDetallesData.data[0].id_cliente?
					sucursalDetallesData.data.map(e=>
							<tr key={e.id}>
								<td>{e.sucursal.codigo}</td>
								<td>{e.cliente?e.cliente.nombre:"NO"}</td>
								<td>{e.cliente?e.cliente.identificacion:"NO"}</td>
								<td>{moneda(e.saldo)}</td>
								<td>{e.created_at}</td>
							</tr>
						)
					:null:null:null}
				</tbody>
			</table>
		</div>
	)
}
