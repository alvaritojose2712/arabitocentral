export default function PedidoSelect({
	pedidoData,
	setshowCantidadCarrito,
	setDelCarrito,
	setCtCarrito,
	delPedido,
	moneda,
	sendPedidoSucursal,
	showPedidoBarras,
	aprobarRevisionPedido,
}) {
	return(
		<div className="container">
			<div className="text-center"><i className="text-danger fa fa-times" onClick={()=>setshowCantidadCarrito("procesar")}></i></div>
			{pedidoData?
				pedidoData.sucursal?
					<div className="d-flex justify-content-between border p-1">
            <div className="w-50">
            	<div>
            		<small className="text-muted fst-italic">{pedidoData.created_at}</small>
            	</div>
            	<div className="d-flex align-items-center">
	            	<span className="fs-3 fw-bold">{pedidoData.sucursal.nombre}</span>
	            	<span className="btn btn-secondary m-1">{pedidoData.id}</span>
            		
            	</div>
            </div>
            <div className="w-50 text-right">
	            <span>
            		<span className="h6 text-muted font-italic">Base. </span>
            		<span className="h6 text-arabito">{moneda(pedidoData.base)}</span>
	            </span>
            	<br/>

	            <span>
            		<span className="h6 text-muted font-italic">Venta. </span>
            		<span className="h3 text-success">{moneda(pedidoData.venta)}</span>
	            </span>
            	
            	<br/><span className="h6 text-muted">Items. <b>{pedidoData.items.length}</b></span>
            </div>
          </div>
				:null
			:null}
			<table className="table">
				<thead>
					<tr>
						<th className="cell2">Ct.</th>
						<th className="cell2">Ct Real.</th>
						
						<th className="cell2">Barras</th>
						<th className="cell2">Barras REAl</th>

						<th className="cell2">Alterno</th>
						<th className="cell2">Alterno REAl</th>
						
						<th className="cell4">Descripción <button className="btn btn-sm btn-outline-success" onClick={()=>setshowCantidadCarrito("buscar")}><i className="fa fa-plus"></i></button></th>
						
						
						<th className="cell2">P.U.</th>
						<th className="cell2 text-right">Tot.</th>
					</tr>
				</thead>
				<tbody>

					{pedidoData?
						pedidoData.items?pedidoData.items.map(e=>
							<tr key={e.id} onDoubleClick={setDelCarrito} data-id={e.id} className="pointer">
								<td className="align-middle cell2">
									<span className="text-success fst-italic pointer" onClick={setCtCarrito} data-id={e.id}>{e.cantidad}</span>
								</td>
								<td className="align-middle cell2">
									<span className="text-sinapsis fst-italic pointer" data-id={e.id}>{e.ct_real}</span>
								</td>

								<td className="align-middle cell2">
									<span className="text-success fst-italic pointer" data-id={e.id}>{e.producto.codigo_barras}</span>
								</td>
								<td className="align-middle cell2">
									<span className="text-sinapsis fst-italic pointer" data-id={e.id}>{e.barras_real}</span>
								</td>

								<td className="align-middle cell2">
									<span className="text-success fst-italic pointer" data-id={e.id}>{e.producto.codigo_proveedor}</span>
								</td>
								<td className="align-middle cell2">
									<span className="text-sinapsis fst-italic pointer" data-id={e.id}>{e.alterno_real}</span>
								</td>



								<td className="cell4"> 
									<small>{e.producto.descripcion}</small> 
								</td>
								<td className="align-middle cell2">
									{e.producto.precio}
								</td>
								<td className="align-middle cell2 text-right">
									{moneda(e.monto)}
								</td>

							</tr>
						)
						:null
					:null}	
				</tbody>
			</table>
			<small className="text-muted fst-italic">Doble click al producto para eliminarlo</small>
			<div className="form-group d-flex justify-content-center align-items-center">
				{pedidoData?
					pedidoData.estado!=2?
    				<button className="btn btn-circle btn-sm m-3 btn-outline-danger" onClick={delPedido}><i className="fa fa-times"></i></button>
					:null
				:null}

				<button className="btn btn-circle btn-xl m-3 btn-outline-success" title="Ver reporte sucursal" onClick={showPedidoBarras}><i className="fa fa-print fa-3x"></i></button>
				{pedidoData?
					pedidoData.estado==3?
						<button className="btn fs-3 m-3 btn-outline-success" title="Enviar a sucursal" onClick={aprobarRevisionPedido}>Aprobar Revisión</button>
					:null
				:null}
			</div>
		</div>
	)
}