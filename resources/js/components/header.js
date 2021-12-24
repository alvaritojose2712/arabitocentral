import logo from "../../images/logo.png"
import icon from "../../images/icon.png"

export default function Header({
	view,
	setView,
	sucursalSelect,
	setsucursalSelect
}) {
	return (
		<header className={(sucursalSelect===null?"":"nav-bar-online")}>
			{sucursalSelect===null?
	      <div className="d-flex justify-content-center flex-wrap align-items-center">
	        <div className="p-3">
	          <img src={logo} alt="arabito" className="logo" />
	        </div>
	      </div>
	      :null}
      {sucursalSelect!==null?
      <div className=" d-flex justify-content-between bg-light">
        <span className="p-1 d-flex align-items-center">
        	<img src={icon} alt="icon" className="icon"/>
        </span>
        
        <div className="d-flex justify-content-center">
          <span className={(view=="inventario"?"btn btn-arabito":null)+(" p-1 d-flex align-items-center pointer")} onClick={()=>setView("inventario")}>Inventario</span>
          <span className={(view=="fallas"?"btn btn-arabito":null)+(" p-1 d-flex align-items-center pointer")} onClick={()=>setView("fallas")}>Fallas</span>
          <span className={(view=="ventas"?"btn btn-arabito":null)+(" p-1 d-flex align-items-center pointer")} onClick={()=>setView("ventas")}>Reportes</span>
          <span className={(view=="gastos"?"btn btn-arabito":null)+(" p-1 d-flex align-items-center pointer")} onClick={()=>setView("gastos")}>Gastos</span>
        </div>

        <span className="p-1 bg-light pointer d-flex align-items-center" onClick={()=>setsucursalSelect(null)}>
        	<div className="btn icon">
        		
        		{sucursalSelect}
        	</div>
        </span>
      </div>
      :null}

    </header>
	)
}