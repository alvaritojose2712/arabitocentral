export default function FallasComponent({fallas}) {
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
				{fallas.map(e=><tr key={e.id}>
					<td>{e.id_producto}</td>
					<td>{e.producto.descripcion}</td>
					<td>{e.id_sucursal}</td>
				</tr>)}
			</tbody>
		</table>
	)
}