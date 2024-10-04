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
		<div className="container-fluid p-3">
			{/* <div className="text-center"><i className="text-danger fa fa-times" onClick={()=>setshowCantidadCarrito("procesar")}></i></div> */}
			{pedidoData?
				pedidoData.sucursal?
					<div className="card p-3">

						<table className="table">
							<thead>
								<tr>
									<th>FECHA</th>
									<th>CXP</th>
									<th>ESTADO</th>
									<th>ORIGEN</th>
									<th>DESTINO</th>
									<th>ID PED</th>
									<th>ID PED IN SUC</th>
									<th># ITEMS</th>
									<th className="text-right">BASE</th>
									<th className="text-right">VENTA</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<small className="text-muted">{pedidoData.created_at}</small>
									</td>
									<td>
										{pedidoData.cxp?
										<>
											{pedidoData.cxp.numfact}
											<br />
											<b>{pedidoData.cxp.proveedor.descripcion}</b>
										</>
										:null}
									</td>
									<td className={(pedidoData.estado==1?"bg-danger":"")+(pedidoData.estado==3?"bg-warning":"")+(pedidoData.estado==4?"bg-info":"")+(pedidoData.estado==2?"bg-success":"")}>
										{pedidoData.estado==1?"PENDIENTE":null}
										{pedidoData.estado==3?"EN REVISIÓN":null}
										{pedidoData.estado==4?"REVISADO":null}
										{pedidoData.estado==2?"PROCESADO":null}
									</td>
									<td style={{backgroundColor:pedidoData.origen.background}}>
										{pedidoData.origen.codigo}  
									</td>
									<td style={{backgroundColor:pedidoData.destino.background}}>
										{pedidoData.destino.codigo}
									</td>
									<td>
										{pedidoData.id}
									</td>
									<td>
										{pedidoData.idinsucursal}
									</td>
									<td>
										{pedidoData.items.length}
									</td>
									<th className="bg-base text-right">
										{moneda(pedidoData.base)}
									</th>
									<th className="bg-venta text-right">
										{moneda(pedidoData.venta)}
									</th>
								</tr>
							</tbody>
						</table>
					</div>
				:null
			:null}
			<table className="table">
				<thead>
					<tr>
						<th className="">Ct.</th>
						<th className="">Ct Real.</th>
						
						<th className="">Barras</th>
						<th className="">Barras REAl</th>

						<th className="">Alterno</th>
						<th className="">Alterno REAl</th>
						
						<th className="">Descripción</th>
						
						
						<th className="">P.U.</th>
						<th className=" text-right">Tot.</th>
					</tr>
				</thead>
				<tbody>

					{pedidoData?
						pedidoData.items?pedidoData.items.map(e=>
							<tr key={e.id} className="pointer">
								<td className="">
									<span className="pointer" onClick={setCtCarrito} data-id={e.id}>{e.cantidad}</span>
								</td>
								<td className="">
									<span className="fst-italic pointer" data-id={e.id}>{e.ct_real}</span>
								</td>

								<td className="">
									<span className="pointer" data-id={e.id}>{e.producto?e.producto.codigo_barras:null}</span>
								</td>
								<td className="">
									<span className="fst-italic pointer" data-id={e.id}>{e.barras_real}</span>
								</td>

								<td className="">
									<span className="pointer" data-id={e.id}>{e.producto?e.producto.codigo_proveedor:null}</span>
								</td>
								<td className="">
									<span className="fst-italic pointer" data-id={e.id}>{e.alterno_real}</span>
								</td>

								<td className=""> 
									<small>{e.producto?e.producto.descripcion:null}</small> 
								</td>
								<td className="">
									{e.producto?e.producto.precio:null}
								</td>
								<td className="">
									{moneda(e.monto)}
								</td>

							</tr>
						)
						:null
					:null}	
				</tbody>
			</table>
			<div className="form-group d-flex justify-content-center align-items-center">
				{pedidoData?
					pedidoData.estado==1?
    					<button className="btn m-3 btn-danger" onClick={delPedido}><i className="fa fa-times"></i></button>
					:null
				:null}

				<button className="btn m-3 btn-success" title="Ver reporte sucursal" onClick={showPedidoBarras}><i className="fa fa-print fa-3x"></i></button>
				{pedidoData?
					pedidoData.estado==3?
						<button className="btn fs-3 m-3 btn-outline-success" title="Enviar a sucursal" onClick={aprobarRevisionPedido}>Aprobar Revisión</button>
					:null
				:null}
			</div>
		</div>
	)
}