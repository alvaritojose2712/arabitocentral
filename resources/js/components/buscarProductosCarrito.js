export default function BuscarProductosCarrito({
	inputBuscarInventario,
	qBuscarInventario,
	setQBuscarInventario,
	Invnum,
	setInvnum,
	InvorderColumn,
	setInvorderColumn,
	InvorderBy,
	setInvorderBy,
	productosInventario,

	indexSelectCarrito,
	setindexSelectCarrito,
	setshowCantidadCarrito,
	showCantidadCarrito,

  pedidoData,

  setProdCarritoInterno,

}){
	return(
		<>
      <div className="mb-3">
        <div className="input-group w-100 ">
          <input type="text"
          required={true} 
          ref={inputBuscarInventario}
          className="form-control" 
          placeholder="Buscar..." 
          value={qBuscarInventario} 
          onChange={e=>setQBuscarInventario(e.target.value)}/>
        </div>
        <div className="input-group w-100">
          <select value={Invnum} className="form-control" onChange={e=>setInvnum(e.target.value)}>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="500">500</option>
            <option value="2000">2000</option>
          </select>
          <select value={InvorderColumn} className="form-control" onChange={e=>setInvorderColumn(e.target.value)}>
            <option value="id">id</option>
            <option value="descripcion">descripcion</option>
            <option value="precio">precio</option>
            <option value="cantidad">cantidad</option>
            <option value="codigo">codigo</option>
          </select>
          <select value={InvorderBy} className="form-control" onChange={e=>setInvorderBy(e.target.value)}>
            <option value="asc">Asc</option>
            <option value="desc">Desc</option>
          </select>
          <div className="input-group-prepend">
            <button className="btn btn-outline-secondary" type="button"><i className="fa fa-search"></i></button>
          </div>
        </div>
      </div>
      { 
        productosInventario.length
        ? productosInventario.map( (e,i) =>
          <div 
          key={e.id}
          onClick={()=>setindexSelectCarrito(i)}
          className={(indexSelectCarrito==i?"bg-arabito-light":"bg-light")+" text-secondary card mb-3 pointer shadow"}>
            <div className="card-header flex-row justify-content-between">
              <div className="d-flex justify-content-between">
                <div className="w-50">
                	<small className="fst-italic">{e.codigo_barras}</small><br/>
                	<small className="fst-italic">{e.codigo_proveedor}</small><br/>

                	{indexSelectCarrito==i?
                		<span>{e.descripcion}</span>
                	:null}
                </div>
                <div className="w-50 text-right">

                	<span className="h6 text-muted font-italic">Bs. {e.bs}</span>
                	<br/>
                	<span className="h6 text-muted font-italic">COP. {e.cop}</span>
                	<br/>
                	<span className="h3 text-success">{e.precio}</span>
                	
                	{indexSelectCarrito==i?<><br/><span className="h6 text-muted">Ct. <b>{e.cantidad}</b></span></>:null}

                </div>
              </div>
            </div>
            {indexSelectCarrito==i?
              <div className="d-flex justify-content-center">
                <button className={("btn btn-circle btn-sm m-3 btn-outline-arabito")} onClick={()=>setshowCantidadCarrito("carrito")}><i className="fa fa-plus fa-2x"></i></button>
                {pedidoData?
                  pedidoData.id?
              	   <button className={("btn m-3 btn-sm btn-outline-success")} onClick={setProdCarritoInterno}><i className="fa fa-plus"></i> {pedidoData.sucursal.nombre.substr(0,4)}...{pedidoData.id}</button>
                      
                  :null
                :null}
              </div>
            :
            <div className="card-body d-flex justify-content-between">
              <div className="">
                <h5 
                className="card-title"
                ><b>{e.descripcion}</b></h5>
              </div> 
              <p className="card-text p-1">
                Ct. <b className="h3">{e.cantidad}</b>
              </p>
            </div>

          }
          </div>
         )
        : <div className='h3 text-center text-dark mt-2'><i>¡Sin resultados!</i></div>
      }
		</>
	)
}