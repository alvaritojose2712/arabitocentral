export default function PedidosList({
	qpedido,
	setqpedido,
	qpedidoDateFrom,
	setqpedidoDateFrom,
	qpedidoDateTo,
	setqpedidoDateTo,
	qpedidoOrderBy,
	setqpedidoOrderBy,
	qpedidoOrderByDescAsc,
	setqpedidoOrderByDescAsc,
	pedidos,
	setpedidos,
	qpedidosucursal,
	setqpedidosucursal,

	qpedidosucursaldestino,
	setqpedidosucursaldestino,
	qestadopedido,
	setqestadopedido,
	getPedidos,
	delPedido,
	selectPedido,
	moneda,
	setshowCantidadCarrito,
	sucursales,
}) {
	return(
		<>
			<div className="form-group mb-3">
				<form className="input-group" onSubmit={event=>{event.preventDefault();getPedidos()}}>
        			<input className="form-control" placeholder="Buscar... #Pedido" value={qpedido} onChange={e=>setqpedido(e.target.value)} autoComplete="off" />
					<select className="form-control form-control-lg" value={qpedidosucursal} onChange={e=>setqpedidosucursal(e.target.value)}>
						<option value="">-SUCURSAL-</option>
						{sucursales.map(e=>
							<option key={e.id} value={e.id}>{e.codigo}</option>
						)}
					</select>

					<select className="form-control form-control-lg" value={qpedidosucursaldestino} onChange={e=>setqpedidosucursaldestino(e.target.value)}>
						<option value="">-SUCURSAL-</option>
						{sucursales.map(e=>
							<option key={e.id} value={e.id}>{e.codigo}</option>
						)}
					</select>
					
					<input type="date" value={qpedidoDateFrom} onChange={e=>setqpedidoDateFrom(e.target.value)} className="form-control" />
					<input type="date" value={qpedidoDateTo} onChange={e=>setqpedidoDateTo(e.target.value)} className="form-control" />
					<button className="btn btn-success"><i className="fa fa-search"></i></button>
					<div className="radios d-flex">
						{/* <div className={" m-1 pointer "+(qestadopedido=="0"?"select-fact bg-info":"select-fact")} onClick={()=>setqestadopedido("0")}>
							Pend. <i className="fa fa-exclamation"></i>
						</div> */}
						<div className={" m-1 pointer " + (qestadopedido==""?"select-fact bg-primary":"select-fact")} onClick={()=>setqestadopedido("")}>
							Todos. <i className="fa fa-clock-o"></i> 
						</div>
						<div className={" m-1 pointer " + (qestadopedido=="1"?"select-fact bg-danger":"select-fact")} onClick={()=>setqestadopedido("1")}>
							Pend. <i className="fa fa-clock-o"></i> 
						</div>
						<div className={" m-1 pointer " + (qestadopedido=="3"?"select-fact bg-warning":"select-fact")} onClick={()=>setqestadopedido("3")}>
							En Revi. <i className="fa fa-check"></i> 
						</div>
						<div className={" m-1 pointer " + (qestadopedido=="4"?"select-fact bg-info":"select-fact")} onClick={()=>setqestadopedido("4")}>
							Revisado. <i className="fa fa-check"></i> 
						</div>
						<div className={" m-1 pointer " + (qestadopedido=="2"?"select-fact bg-success":"select-fact")} onClick={()=>setqestadopedido("2")}>
							Procesado. <i className="fa fa-check"></i> 
						</div>
					</div>
				</form>
			</div>
			

			<table className="table">
				<thead>
					<tr>
						<th>ESTADO</th>
						<th>FECHA</th>
						<th>CXP</th>
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
					{pedidos.map(e=>
						<tr onClick={()=>selectPedido(e.id)} data-id={e.id}  className="pointer" key={e.id}>
							<td className={(e.estado==1?"bg-danger":"")+(e.estado==3?"bg-warning":"")+(e.estado==4?"bg-info":"")+(e.estado==2?"bg-success":"")}>
								{e.estado==1?"PENDIENTE":null}
								{e.estado==3?"EN REVISIÓN":null}
								{e.estado==4?"REVISADO":null}
								{e.estado==2?"PROCESADO":null}
							</td>
							<td>
								<small className="text-muted">{e.created_at}</small>
							</td>
							<td>
								{e.cxp?
								<>
									{e.cxp.numfact}
									<br />
									<b>{e.cxp.proveedor.descripcion}</b>
								</>
								:null}
							</td>
							<td style={{backgroundColor:e.origen.background}}>
								{e.origen.codigo}  
							</td>
							<td style={{backgroundColor:e.destino.background}}>
								{e.destino.codigo}
							</td>
							<td>
								{e.id}
							</td>
							<td>
								{e.idinsucursal}
							</td>
							<td>
								{e.items.length}
							</td>
							<th className="bg-base text-right">
								{moneda(e.base)}
							</th>
							<th className="bg-venta text-right">
								{moneda(e.venta)}
							</th>
						</tr>
					)}
				</tbody>

			</table>
		</>
	)
}