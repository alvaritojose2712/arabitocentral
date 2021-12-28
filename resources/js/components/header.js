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
        
        <div className="d-flex justify-content-center align-items-center">
          <span className={(view=="ventas"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setView("ventas")}><i className="fa fa-shopping-cart"></i></span>
          <span className={(view=="inventario"?"btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setView("inventario")}>
          	<i className="fa fa-product-hunt"></i>
          </span>
          <span className={(view=="fallas"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setView("fallas")}>
          	<i className="fa fa-product-hunt"></i>
          	<i className="fa fa-exclamation"></i>
          	
          </span>
          <span className={(view=="gastos"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setView("gastos")}><i className="fa fa-file"></i></span>
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