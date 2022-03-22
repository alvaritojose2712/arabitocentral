function Fallas({
	qFallas,
	setqFallas,
	orderCatFallas,
	setorderCatFallas,
	orderSubCatFallas,
	setorderSubCatFallas,
	ascdescFallas,
	setascdescFallas,
	fallas,
	delFalla,
    openReporteFalla,

}) {
	return (
		<div className="container">
			<div className="col">
				<div className="d-flex justify-content-between align-items-center">
					<h1>Fallas</h1> 
					<div className="btn-group">
						<button className={("btn ")+(orderCatFallas=="proveedor"?"btn-dark":"btn-outline-success")} onClick={()=>setorderCatFallas("proveedor")}>Por Proveedor</button>
						<button className={("btn ")+(orderCatFallas=="categoria"?"btn-dark":"btn-outline-success")} onClick={()=>setorderCatFallas("categoria")}>Por Categoría</button>
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
				
				<div className="container mt-4">
					{fallas&&Object.entries(fallas)?
							Object.entries(fallas).map((e,i)=>
								<div className="m-3" key={i}>
									<h3>{e[0]} <button className="btn btn-outline-success" onClick={() => openReporteFalla(e[1] ? e[1][0] ? e[1][0].producto.id_proveedor:null:null)}><i className="fa fa-file"></i></button></h3>
									<table className="table m-3">
										<thead>
											<tr>
												<th className="cell1">ID</th>
												<th className="cell4">Descripción</th>
												<th className="cell2">Proveedor</th>
												<th className="cell1">Ct.</th>
												<th className="cell2">Fecha</th>
											</tr>
										</thead>
										<tbody>
											{e[1].map(ee=>
												<tr key={ee.id}>
													<td>{ee.id}</td>
													<td>{ee.producto.descripcion}</td>
													<td>{ee.producto.proveedor.descripcion}</td>
													<td>{ee.producto.cantidad}</td>
													<td>{ee.created_at}</td>
													<td><i className="fa fa-times text-danger" data-id={ee.id} onClick={delFalla}></i></td>
												</tr>
											)}
										</tbody>
									</table>
								</div>
							)
						:null}
				</div>
				
			</div>
		</div>
	)
}

export default Fallas