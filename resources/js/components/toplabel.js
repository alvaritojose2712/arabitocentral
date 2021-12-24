export default function Toplabel({sucursales,sucursalSelect}) {
	let name = () => {
		if (sucursales.filter(e=>e.char==sucursalSelect).length) {
			return sucursales.filter(e=>e.char==sucursalSelect)[0].nombre

		}
		return ""
	}
	return(
		<div className="bg-light toplabel d-flex justify-content-center">
			<span className="h3 m-1">{name()}</span>
		</div>
	)
}