export default function Ventas({ventas}){
	return(
		<div>
			{ventas.map(e=>
				<div className="card-pedidos" key={e.id}>
					<div className="w-100">
						
					  <div className="d-flex justify-content-between">
						  <h3 className="">
						  	<b>
		              Ventas: {e.num_ventas}
		            </b>
						  </h3>
		          <span className="text-success">{e.fecha}</span>
					  </div>
				  	<div>
					  	<ul className="list-group">
							  <li className="list-group-item d-flex justify-content-between align-items-center">
							    Efectivo
							    <span className="badge bg-arabito badge-pill">{e.efectivo}</span>
							  </li>
							  <li className="list-group-item d-flex justify-content-between align-items-center">
							    Débito
							    <span className="badge bg-arabito badge-pill">{e.debito}</span>
							  </li>
							  <li className="list-group-item d-flex justify-content-between align-items-center">
							    Transferencia
							    <span className="badge bg-arabito badge-pill">{e.transferencia}</span>
							  </li>
							</ul>
				  	</div>
					  <div>
							<h4>Tasa: <b>{e.tasa}</b></h4>
					  </div>
					</div>

				</div>
			)}
				
		</div>
	)
}