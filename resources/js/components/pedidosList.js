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

  qestadopedido,
	setqestadopedido,

	getPedidos,
	delPedido,
	selectPedido,

	moneda,

	setshowCantidadCarrito,

}) {
	return(
		<>
			<div className="text-center"><i className="text-danger fa fa-times" onClick={()=>setshowCantidadCarrito("buscar")}></i></div>

			<div className="form-group">
				<div className="input-group">
	        <input type="date" value={qpedidoDateFrom} onChange={e=>setqpedidoDateFrom(e.target.value)} className="form-control" />
	        <input type="date" value={qpedidoDateTo} onChange={e=>setqpedidoDateTo(e.target.value)} className="form-control" />
				</div>
			</div>

			<div className="form-group mb-3">
				<div className="input-group">
        	<input className="form-control" placeholder="Buscar... #Pedido" value={qpedido} onChange={e=>setqpedido(e.target.value)} autoComplete="off" />
					<div className="radios d-flex">
						<div className={" m-1 pointer "+(qestadopedido=="0"?"select-fact bg-info":"select-fact")} onClick={()=>setqestadopedido("0")}>
							Pend. <i className="fa fa-exclamation"></i>
						</div>
						<div className={" m-1 pointer " + (qestadopedido=="1"?"select-fact bg-warning":"select-fact")} onClick={()=>setqestadopedido("1")}>
							Procs. <i className="fa fa-clock-o"></i> 
						</div>
						<div className={" m-1 pointer " + (qestadopedido=="2"?"select-fact bg-success":"select-fact")} onClick={()=>setqestadopedido("2")}>
							Aprob. <i className="fa fa-check"></i> 
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
						  <h3>
						  	<b>
								 {e.sucursal.nombre}
		            </b>
						  	<span className="btn btn-secondary m-2">{e.id}</span>
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