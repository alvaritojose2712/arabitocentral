export default function Fallas({
	getsucursalDetallesData,
	sucursalDetallesData,
	moneda,

}) {
	return (
		<div className="">
			<div className="d-flex justify-content-between align-items-center">
				<div className="btn-group">
					<button className="btn bt-success">Por Proveedor</button>
					<button className="btn bt-success">Por Categoría</button>
				</div>
				{/*<div className="d-flex text-right flex-column align-items-center">
					<h4>Frecuencia</h4>
					<div className="btn-group">
						<button className={("btn ")+(orderSubCatFallas=="todos"?"btn-secondary":"btn-outline-secondary")} onClick={()=>setorderSubCatFallas("todos")}>Todos</button>
						<button className={("btn ")+(orderSubCatFallas=="alta"?"btn-success":"btn-outline-success")} onClick={()=>setorderSubCatFallas("alta")}>Alta</button>
						<button className={("btn ")+(orderSubCatFallas=="media"?"btn-warning":"btn-outline-warning")} onClick={()=>setorderSubCatFallas("media")}>Media</button>
						<button className={("btn ")+(orderSubCatFallas=="baja"?"btn-danger":"btn-outline-danger")} onClick={()=>setorderSubCatFallas("baja")}>Baja</button>
					</div>
					
				</div>*/}
			</div>
			
			<table className="table m-3 w-100 h-100">
				<thead>
					<tr>
						<th className="cell1">SUCURSAL</th>
						<th className="cell4">Descripción</th>
						<th className="cell2">Proveedor</th>
						<th className="cell1">Ct.</th>
						<th className="cell2">Fecha</th>
					</tr>
				</thead>
				<tbody>
				{sucursalDetallesData.data?sucursalDetallesData.data.length?sucursalDetallesData.data[0].id_producto?
					sucursalDetallesData.data.map(e=>
							<tr key={e.id}>
								<td>{e.sucursal.codigo}</td>
								<td>{e.producto?e.producto.descripcion:(e.id_producto+" NO VINCULADO")}</td>
								<td>{e.producto?e.producto.id_proveedor:(e.id_producto+" NO VINCULADO")}</td>
								<td>{e.cantidad}</td>
								<td>{e.created_at}</td>
							</tr>
						)
					:null:null:null}
				</tbody>
			</table>
		</div>
	)
}
