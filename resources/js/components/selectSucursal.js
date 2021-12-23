export default function SelectSucursal({
	setsucursalSelect,
	sucursalSelect,
	sucursales,
}){
	const setsucursalSelectFun = e => {
		let codigo = e.currentTarget.attributes["data-sucursal"].value
		setsucursalSelect(codigo)
	}
	return (
		<ul className="list-group">
			{sucursales.map(e=>
			  <li key={e.id} onClick={setsucursalSelectFun} data-sucursal={e.char} className="pointer list-group-item d-flex justify-content-between align-items-center">
			    {e.nombre}
			    <span className="badge bg-arabito badge-pill">{e.char}</span>
			  </li>
			)}
		</ul>
	)
}