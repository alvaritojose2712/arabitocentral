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
				<div className="input-group">
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
					<div className="radios d-flex">
						{/* <div className={" m-1 pointer "+(qestadopedido=="0"?"select-fact bg-info":"select-fact")} onClick={()=>setqestadopedido("0")}>
							Pend. <i className="fa fa-exclamation"></i>
						</div> */}
						<div className={" m-1 pointer " + (qestadopedido==""?"select-fact":"select-fact")} onClick={()=>setqestadopedido("")}>
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
				</div>
			</div>
			

			<table className="table">
				{pedidos.map(e=>
					<tr
					onClick={selectPedido}
					data-id={e.id} 
					className="card-pedidos d-flex justify-content-between pointer" 
					key={e.id}
					>
						<td>
							<small className="text-muted">{e.created_at}</small>

						</td>
						<td>
							{e.estado==1? <div className="h2 bg-danger">PENDIENTE</div>:null}
							{e.estado==3? <div className="h2 bg-warning">EN REVISIÓN</div>:null}
							{e.estado==4? <div className="h2 bg-info">REVISADO</div>:null}
							{e.estado==2? <div className="h2 bg-success">PROCESADO</div>:null}
						</td>
						<td>
								<b>ORIGEN: {e.origen.codigo}  (#{e.idinsucursal})</b> <br />

						</td>
						<td>
								<b>DESTINO: {e.destino.codigo}</b>

						</td>
						<td>
							<h3>
								<span className="btn btn-secondary m-2">{e.id}</span> - <span className="btn btn-secondary m-2">{e.idinsucursal}</span>
							</h3>
						</td>
						<td>
							<small className="text-muted">
								Prods. {e.items.length}
							</small>

						</td>
						<th className="bg-arabito">
							<span className={""}>{moneda(e.base)}</span>
						</th>
						<th className="bg-success">
							<span className={""}>{moneda(e.venta)}</span>
						</th>
					</tr>
				)}
			</table>
		</>
	)
}