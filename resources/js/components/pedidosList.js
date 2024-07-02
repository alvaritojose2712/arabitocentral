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

			<div>
				{pedidos.map(e=>
					<div
					onClick={selectPedido}
					data-id={e.id} 
					className="card-pedidos d-flex justify-content-between pointer" 
					key={e.id}
					>
						<div>
							{e.estado==1? <button className="btn btn-danger">PENDIENTE</button>:null}
							{e.estado==3? <button className="btn btn-warning">EN REVISIÓN</button>:null}
							{e.estado==4? <button className="btn btn-info">REVISADO</button>:null}
							{e.estado==2? <button className="btn btn-success">PROCESADO</button>:null}
							<h3>
								<b>{e.sucursal.nombre}</b>
								<span className="btn btn-secondary m-2">{e.id}</span> - <span className="btn btn-secondary m-2">{e.idinsucursal}</span>
							</h3>
							<small className="text-muted">{e.created_at}</small>
						</div>
						<div className="d-flex flex-column justify-content-between">
							<small className="text-muted">
								Prods. {e.items.length}
							</small>
							<div>
								<button className={"btn btn-outline-arabito"}>{moneda(e.base)}</button>
								<button className={"btn btn-outline-success"}>{moneda(e.venta)}</button>
							</div>
						</div>
					</div>
				)}
			</div>
		</>
	)
}