import logo from "../../images/logo.png"
import icon from "../../images/icon.png"

export default function Header({
	sucursales,
	sucursalSelect,
	setsucursalSelect,
	viewmainPanel,
	setviewmainPanel,
	logout
}) {
	return (
	<header className="container mt-1 mb-1">
		<div className="row">
			<div className="col d-flex justify-content-center align-items-center pointer" onClick={()=>setviewmainPanel("panelgeneral")}>
				
			</div>
			<div className="col d-flex justify-content-center align-items-center">
				<div className="text-center">
					<img src={logo} alt="arabito" className="logo pointer" onClick={()=>setviewmainPanel("panelgeneral")} />
				</div>
			</div>
			<div className="col d-flex justify-content-center align-items-center">
				{
					sucursales.filter(e=>e.id==sucursalSelect).length?
					<button className="btn btn-secondary h2" onClick={()=>setsucursalSelect(null)}>
						{sucursales.filter(e=>e.id==sucursalSelect)[0].nombre}
					</button>
					:
					null
				}

				<i className="fa fa-times text-danger" onClick={()=> logout()}></i>
			</div>
		</div>
		{/* {sucursalSelect===null?
	      <div className="d-flex justify-content-center flex-wrap align-items-center">
	        <div className="p-3">
	        </div>
	      </div>
	    :null}
      {sucursalSelect!==null?
      <div className=" d-flex justify-content-between bg-light">
        <span className="p-1 d-flex align-items-center">
        	<img src={icon} alt="icon" className="icon"/>
        </span>
        {sucursalSelect!=="inventario"?
	        <div className="d-flex justify-content-center align-items-center">
	          <span className={(view=="ventas"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setView("ventas")}><i className="fa fa-shopping-cart"></i></span>
	          <span className={(view=="fallas"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setView("fallas")}>
	          	<i className="fa fa-product-hunt"></i>
	          	<i className="fa fa-exclamation"></i>
	          </span>
	          <span className={(view=="gastos"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setView("gastos")}><i className="fa fa-file"></i></span>
	        </div>
        :null}

        {sucursalSelect==="inventario"&&viewProductos=="salida"?
	        <div className="d-flex justify-content-center align-items-center">
	          <span className={(showCantidadCarrito=="buscar"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setshowCantidadCarrito("buscar")}>
	          	<i className="fa fa-search"></i>
	          </span>
	          <span className={(showCantidadCarrito=="procesar"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setshowCantidadCarrito("procesar")}>
	          	<i className="fa fa-shopping-cart"></i>
	          </span>
	          {pedidoData?
	          		pedidoData.id?
	          		<span className={(showCantidadCarrito=="pedidoSelect"?"btn btn-arabito":null)+(" btn btn-circle pointer")} onClick={()=>setshowCantidadCarrito("pedidoSelect")}>
			          	{pedidoData.sucursal.nombre.substr(0,4)}...{pedidoData.id}
			          </span>
	          		:null
	          :null}
	        </div>
        :null}

        <span className="p-1 bg-light pointer d-flex align-items-center" onClick={()=>setsucursalSelect(null)}>
        	<div className="btn icon">
        		
        		{sucursalSelect.substr(0,2).toUpperCase()}
        	</div>
        </span>
      </div>
      :null} */}

    </header>
	)
}