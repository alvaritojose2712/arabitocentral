export default function Ventas({
	ventas,
	selectfechaventa,
	setselectfechaventa,
	moneda,
}){
	return(
		<div>
			<h2>Reportes de venta</h2>
			<div className="form-group">
				<input type="date" className="form-control" value={selectfechaventa} onChange={e=>setselectfechaventa(e.target.value)}/>
			</div>
			{ventas.map(e=>
				<div className="card-pedidos" key={e.id}>
					<div className="w-100">
				  	<div className="container-fluid">
				  		<div className="row">
				  			<div className="col">
				  				<div className="d-flex justify-content-center align-items-center">
					  				<div className="rounded border border-success text-success h3 m-1 p-2">
					  					{moneda(e.transferencia+e.efectivo+e.debito)}
					  				</div>
				  				</div>
				  			</div>
				  		</div>
				  		<div className="row">
				  			<div className="col p-0 text-center align-items-center">
				  				<button className="btn btn-outline-arabito">E {moneda(e.efectivo)}</button>
				  			</div>

				  			<div className="col p-0 text-center align-items-center">
				  				<button className="btn btn-outline-arabito">D {moneda(e.debito)}</button>
				  			</div>

				  			<div className="col p-0 text-center align-items-center">
				  				<button className="btn btn-outline-arabito">T {moneda(e.transferencia)}</button>
				  			</div>
				  		</div>
				  		<div className="row">
							  <div className="col p-0">
							  	<button className="btn m-1"><i className="fa fa-users"></i></button>
									{e.num_ventas}
							  	
							  </div>
							  <div className="col p-0 d-flex align-items-center">
			          	<span className="text-muted h5">{e.fecha}</span>
							  </div>
							  <div className="col p-0 text-right">
									{e.tasa}
						  		<button className="btn m-1"><i className="fa fa-exchange"></i></button>
							  	
							  </div>
				  		</div>
				  	</div>
					</div>
				</div>
			)}
				
		</div>
	)
}