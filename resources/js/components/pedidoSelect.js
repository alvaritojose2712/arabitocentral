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
	revolverNovedadItemTrans,
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
						<th>ID ITEM</th>
						<th>VINCULO</th>
						<th className="">BARRAS</th>
						<th className="">ALTERNO</th>
						<th className="">DESCRIPCIÓN</th>
						
						
						<th className="">CT</th>
						<th className="">BASE</th>
						<th className="">VENTA</th>
						<th className=" text-right">SUBTOTAL</th>
					</tr>
				</thead>

					{pedidoData?
						pedidoData.items?pedidoData.items.map(e=>
							<tbody key={e.id} className="pointer">
								<tr >
									<td>{e.id}</td>
									<td className="align-middle">
										<div className="card flex-row p-1">
											<b className="m-1">{e.idinsucursal_vinculo}</b>
											{e.idinsucursal_producto?
												<>
													<b className="m-1">{e.idinsucursal_producto.codigo_barras}</b> 
													<i className="m-1">{e.idinsucursal_producto.codigo_proveedor}</i> 
													<i className="m-1">{e.idinsucursal_producto.descripcion}</i> 
												</>
											:null}
										</div>
									</td>
									<td className="">
										{e.producto?e.producto.codigo_barras:null}
									</td>
									<td className="">
										{e.producto?e.producto.codigo_proveedor:null}
									</td>
									<td className=""> 
										{e.producto?e.producto.descripcion:null}
									</td>
									<td className="bg-ct">
										{e.cantidad}
									</td>
									<td className="bg-base">
										{e.producto?e.producto.precio_base:null}
									</td>

									<td className="bg-venta">
										{e.producto?e.producto.precio:null}
									</td>

									<td className="">
										{moneda(e.monto)}
									</td>

								</tr>
								{

									e.barras_real||e.alterno_real||e.descripcion_real||e.ct_real||e.vinculo_real ?
									<tr>
										<td></td>

										<td className={("align-middle ")+(e.vinculo_real && (e.vinculo_real!=e.idinsucursal_vinculo)?"bg-sinapsis-light":"")}> 
											{e.vinculo_real && (e.vinculo_real!=e.idinsucursal_vinculo) ?
												<div className="card flex-row p-1 align-items-center">
													<i className="fa fa-check text-success m-1" onClick={()=>revolverNovedadItemTrans(e.id,"vinculo_real","aprobar")}></i> 
													<b className="m-1">{e.vinculo_real}</b>
													{e.idinsucursal_producto_sugerido?
														<>
															<b className="m-1">{e.idinsucursal_producto_sugerido.codigo_barras}</b> 
															<i className="m-1">{e.idinsucursal_producto_sugerido.codigo_proveedor}</i> 
															<i className="m-1">{e.idinsucursal_producto_sugerido.descripcion}</i> 
														</>
													:null}
													<i className="fa fa-times text-danger m-1" onClick={()=>revolverNovedadItemTrans(e.id,"vinculo_real","rechazar")}></i> 
												</div>
											:null} 
										</td>

										<td className={("align-middle ")+(e.barras_real && (e.barras_real!=e.producto.codigo_barras)?"bg-sinapsis-light":"")}> 
											{e.barras_real && (e.barras_real!=e.producto.codigo_barras) ?
												<> 
													<div className="card flex-row p-1 align-items-center">
														<i className="fa fa-check text-success m-2" onClick={()=>revolverNovedadItemTrans(e.id,"barras_real","aprobar")}></i> 
														{e.barras_real} 
														<i className="fa fa-times text-danger m-2" onClick={()=>revolverNovedadItemTrans(e.id,"barras_real","rechazar")}></i>  
													</div>
												</>
											:null} 
										</td>
										<td className={("align-middle ")+(e.alterno_real && (e.alterno_real!=e.producto.codigo_proveedor)?"bg-sinapsis-light":"")}> 
											{e.alterno_real && (e.alterno_real!=e.producto.codigo_proveedor) ?
												<> 
													<div className="card flex-row p-1 align-items-center">
														<i className="fa fa-check text-success m-2" onClick={()=>revolverNovedadItemTrans(e.id,"alterno_real","aprobar")}></i> 
														{e.alterno_real} 
														<i className="fa fa-times text-danger m-2" onClick={()=>revolverNovedadItemTrans(e.id,"alterno_real","rechazar")}></i>  
													</div>
												</>
											:null} 
										</td>
										<td className={("align-middle ")+(e.descripcion_real && (e.descripcion_real!=e.descripcion)?"bg-sinapsis-light":"")}> 
											{e.descripcion_real && (e.descripcion_real!=e.descripcion) ?
												<> 
													<div className="card flex-row p-1 align-items-center">
														<i className="fa fa-check text-success m-2" onClick={()=>revolverNovedadItemTrans(e.id,"descripcion_real","aprobar")}></i> 
														{e.descripcion_real} 
														<i className="fa fa-times text-danger m-2" onClick={()=>revolverNovedadItemTrans(e.id,"descripcion_real","rechazar")}></i>  
													</div>
												</>
											:null} 
										</td>
										<td className={("align-middle ")+(e.ct_real && (e.ct_real!=e.cantidad)?"bg-sinapsis-light":"")}> 
											{e.ct_real && (e.ct_real!=e.cantidad) ?
												<> 
													<i className="fa fa-check text-success m-2" onClick={()=>revolverNovedadItemTrans(e.id,"ct_real","aprobar")}></i> 
													{e.ct_real} 
													<i className="fa fa-times text-danger m-2" onClick={()=>revolverNovedadItemTrans(e.id,"ct_real","rechazar")}></i>  
												</>
											:null} 
										</td>

										<td></td>
										<td></td>
										<td></td>
									</tr>
									:null
								}
							</tbody>

						)
						:null
					:null}	
			</table>
			<div className="form-group d-flex justify-content-center align-items-center">
				{pedidoData?
					pedidoData.estado==1?
    					<button className="btn m-3 btn-danger" onClick={delPedido}><i className="fa fa-times"></i></button>
					:null
				:null}

				<button className="btn m-3 btn-success" title="Ver reporte sucursal" onClick={showPedidoBarras}><i className="fa fa-print fa-3x"></i></button>
				{pedidoData?
					pedidoData.estado==3||pedidoData.estado==4?
						<>
							<button className="btn fs-3 m-3 btn-outline-success" title="Enviar a sucursal" onClick={()=>aprobarRevisionPedido(4)}>Aprobar Revisión</button>
							<button className="btn fs-3 m-3 btn-outline-danger" title="Rechazar REVISION" onClick={()=>aprobarRevisionPedido(1)}>RECHAZAR</button>
						</>
					:null
				:null}
			</div>
		</div>
	)
}