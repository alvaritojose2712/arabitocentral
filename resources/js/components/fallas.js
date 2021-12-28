export default function FallasComponent({fallas}) {
	return(
		<>
			<h2>Fallas y pedido</h2>

			<table className="table">
				<thead>
					<tr>
						<th>ID</th>
						<th>Descripción</th>
					</tr>
				</thead>
				<tbody>
					{fallas.map(e=><tr key={e.id}>
						<td>{e.id_producto}</td>
						<td>{e.producto.descripcion}</td>
					</tr>)}
				</tbody>
			</table>
		</>
	)
}