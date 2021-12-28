export default function Gastos({
	gastos,
	setselectgastos,
	selectgastos,
	setfechaGastos,
	fechaGastos,
	tipogasto,
	settipogasto
}) {
	const catReturn = cat => {
		switch(cat){
			case 1: 
				return "Vueltos" 
			break;
	    case 2: 
	    	return "Nómina" 
	    break;
	    case 3: 
	    	return "Funcionamiento" 
	    break;
	    case 4: 
	    	return "Pago a proveedores"
	    break;
	    case 5: 
	    	return "Otros" 
	    break;
	    case 6: 
	    	return "Devolución" 
	    break;
		}
	}

	let gastoFilter = gastos.filter(e=>e.tipo==tipogasto).filter(e=>selectgastos=="*"?true:(e.categoria==selectgastos))
	return(
		<div>
			<h2>Movimientos y gastos</h2>
			<div className="input-group input-group-sm mb-3">
			  <div className="input-group-prepend">
					<div className="btn-group">
						<button className={("btn btn-outline-")+(tipogasto==1?"arabito":"dark")} onClick={()=>settipogasto(1)}>Entregado</button>
						<button className={("btn btn-outline-")+(tipogasto==0?"arabito":"dark")} onClick={()=>settipogasto(0)}>Pendiente</button>
					</div>
			  </div>
			  	<select className="form-control" onChange={e=>setselectgastos(e.target.value)} value={selectgastos}>
			  		<option value="*">Todos</option>
			  		{gastos.map(e=>e.categoria).filter((e, i, self)=>self.indexOf(e) === i).map(e=>
			  			<option key={e} value={e}>{catReturn(e)}</option>
			  		)}
			  	</select>
				<input type="date" className="form-control" onChange={e=>setfechaGastos(e.target.value)} value={fechaGastos}/>
			</div>

			{gastoFilter.map(e=>
				<div className="card-pedidos d-flex justify-content-between" key={e.id}>
				  <div>
					  <h3>
					  	<b>
	              {catReturn(e.categoria)}
	            </b>
					  </h3>
					  <h5>
							{e.descripcion}
					  </h5>
				  </div>
				  <div>
				  	<button className={("btn ")+(!e.tipo?"btn-outline-danger":"btn-outline-success")}>{e.monto}</button>
				  </div>

				</div>
			)}

			<div className="card-pedidos d-flex justify-content-between">
			  <div>
				  
			  </div>
			  <div>
			  	<span className="h2">
			  		Total. 
			  		<b>
			  			{gastoFilter.map(e=>e.monto).reduce((partial_sum, a) => partial_sum + a, 0)}
			  		</b>
			  	</span>
			  	
			  </div>
			</div>
		</div>
	)
}