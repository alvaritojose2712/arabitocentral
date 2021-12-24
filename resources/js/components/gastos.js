export default function Gastos({gastos}) {
	return(
		<div>
			{gastos.map(e=>
				<div className="card-pedidos d-flex justify-content-between" key={e.id}>
				  <div>
					  <h3>
					  	<b>
	              {e.categoria==1?" Vueltos":null}
	              {e.categoria==2?" Nómina":null}
	              {e.categoria==3?" Funcionamiento":null}
	              {e.categoria==4?" Pago a proveedores":null}
	              {e.categoria==5?" Otros":null}
	              {e.categoria==6?" Devolución":null}
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
		</div>
	)
}