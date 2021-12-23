export default function Reportes({resportes}) {
	return(
		<table className="table">
			<thead>
				<tr>
					<th>ID</th>
					<th>Descripción</th>
					<th>Sucursal</th>
				</tr>
			</thead>
			<tbody>
				{resportes.map(e=><tr key={e.id}>
					<td>{e.id_producto}</td>
					<td>{e.id_sucursal}</td>
				</tr>)}
			</tbody>
		</table>
	)
}