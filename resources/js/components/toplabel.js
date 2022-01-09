import logo from "../../images/logo.png"


export default function Toplabel({sucursales,sucursalSelect}) {
	let name = () => {
		if (sucursales.filter(e=>e.char==sucursalSelect).length) {
			return sucursales.filter(e=>e.char==sucursalSelect)[0].nombre

		}
		if (sucursalSelect=="inventario") {
			return "Centro de Acopio"

		}
		return ""
	}
	return(
		<div className="bg-light toplabel d-flex justify-content-center align-items-center p-2">
			{name()?
	    	<>
	    		<img src={logo} alt="arabito" className="logo-header" /> <span className="h3 m-1">{name()}</span>
	    	</>
	    :null
			}
		</div>
	)
}