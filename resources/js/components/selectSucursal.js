export default function SelectSucursal({
	setsucursalSelect,
	sucursalSelect,
	sucursales,
	viewProductos,
	setviewProductos,
}){
	const setsucursalSelectFun = e => {
		let codigo = e.currentTarget.attributes["data-sucursal"].value

		if (codigo==="inventario") {
			let tipo = e.currentTarget.attributes["data-tipo"].value
			setviewProductos(tipo)
		}
		setsucursalSelect(codigo)
	}
	return (
		<>
			<ul className="list-group mb-1">
			  <li className="pointer list-group-item d-flex justify-content-between align-items-center">
			    Centro de Acopio
			    <span className="badge bg-arabito badge-pill"><i className="fa fa-product-hunt"></i></span>
			  </li>
			  <li onClick={setsucursalSelectFun} data-tipo="salida" data-sucursal="inventario" className="pointer list-group-item d-flex justify-content-center align-items-center">
			    Salida
			    <span className="btn btn-outline-arabito m-1"><i className="fa fa-arrow-up"></i></span>
			  </li>
			  <li onClick={setsucursalSelectFun} data-tipo="entrada" data-sucursal="inventario" className="pointer list-group-item d-flex justify-content-center align-items-center">
			    Entrada
			    <span className="btn btn-outline-success m-1"><i className="fa fa-arrow-down"></i></span>
			  </li>
			</ul>
			<ul className="list-group">
				{sucursales.map(e=>
				  <li key={e.id} onClick={setsucursalSelectFun} data-sucursal={e.char} className="pointer list-group-item d-flex justify-content-between align-items-center">
				    {e.nombre}
				    <span className="badge bg-arabito badge-pill">{e.char}</span>
				  </li>
				)}
			</ul>
		</>
	)
}