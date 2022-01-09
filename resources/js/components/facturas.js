

function Facturas({
  facturas,

  factqBuscar,
  setfactqBuscar,
  factqBuscarDate,
  setfactqBuscarDate,
  factsubView,
  setfactsubView,
  factSelectIndex,
  setfactSelectIndex,
  factOrderBy,
  setfactOrderBy,
  factOrderDescAsc,
  setfactOrderDescAsc,

  setfactInpdescripcion,
  factInpdescripcion,
  factInpid_proveedor,
  setfactInpid_proveedor,
  factInpnumfact,
  setfactInpnumfact,
  factInpmonto,
  setfactInpmonto,
  factInpfechavencimiento,
  setfactInpfechavencimiento,

  setFactura,

  proveedoresList,
  number,

  factInpestatus,
  setfactInpestatus,

  delFactura,
  delItemFact,

  moneda
}) {
  const setfactOrderByFun = val => {
    if (val==factOrderBy) {
      if (factOrderDescAsc=="desc") {
        setfactOrderDescAsc("asc")
      }else{
        setfactOrderDescAsc("desc")

      }
    }else{
      setfactOrderBy(val)
    }
  }
  const setfactSelectIndexFun = (i,view) =>{
    setfactSelectIndex(i)
    setfactsubView(view)

    if (facturas[i]) {
      let obj = facturas[i]
      setfactInpid_proveedor(obj.id_proveedor)
      setfactInpnumfact(obj.numfact)
      setfactInpdescripcion(obj.descripcion)
      setfactInpmonto(obj.monto)
      setfactInpfechavencimiento(obj.fechavencimiento)
      setfactInpestatus(obj.estatus)

    }
  }

  const setfactSelectIndexFunInv = i => {
    setfactSelectIndex(i)

  }
  return (
    <div className="container">
      <div className="btn-group mb-4">
        <button className={("btn ")+(factsubView=="buscar"?"btn-dark":"btn-outline-arabito")} onClick={()=>setfactsubView("buscar")}>Buscar</button>
        <button className={("btn ")+(factsubView=="agregar"?"btn-dark":"btn-outline-arabito")} onClick={()=>setfactsubView("agregar")}>

          {factSelectIndex==null?
            <span>Agregar</span>
          : 
            <>
              Editar
              <span> {facturas[factSelectIndex]?
                  facturas[factSelectIndex].numfact
                :null}
              </span>
              -
              <span>
              {facturas[factSelectIndex]?
                facturas[factSelectIndex].proveedor.descripcion
              :null}
              </span>
            </>
              
          }
        </button>
        <button className={("btn ")+(factsubView=="detalles"?"btn-dark":"btn-outline-arabito")} onClick={()=>setfactsubView("detalles")}>Detalles</button>            
      </div>
      {factsubView=="buscar"?
      <>
        <div className="input-group ">
          <input type="text" 
          className="form-control" 
          placeholder="Buscar..." 
          value={factqBuscar} 
          onChange={e=>setfactqBuscar(e.target.value)}/>

          <input type="date" 
          className="form-control" 
          value={factqBuscarDate} 
          onChange={e=>setfactqBuscarDate(e.target.value)}/>

          <div className="input-group-prepend">
            <button className="btn btn-outline-secondary" type="button"><i className="fa fa-search"></i></button>
          </div>
        </div>
         <div className="btn-group w-100 mb-2">
           <button className="pointer btn btn-sm btn-outline-arabito" onClick={()=>setfactOrderByFun("id")}>ID 
            {factOrderBy=="id"?( <i className={factOrderDescAsc=="desc"?"fa fa-arrow-up":"fa fa-arrow-down"}></i>):null}
           </button>
           <button className="pointer btn btn-sm btn-outline-arabito" onClick={()=>setfactOrderByFun("numfact")}>Num. 
            {factOrderBy=="numfact"?( <i className={factOrderDescAsc=="desc"?"fa fa-arrow-up":"fa fa-arrow-down"}></i>):null}
           </button>
           <button className="pointer btn btn-sm btn-outline-arabito" onClick={()=>setfactOrderByFun("id_proveedor")}>Provee. 
            {factOrderBy=="id_proveedor"?( <i className={factOrderDescAsc=="desc"?"fa fa-arrow-up":"fa fa-arrow-down"}></i>):null}
           </button>
           <button className="pointer btn btn-sm btn-outline-arabito" onClick={()=>setfactOrderByFun("monto")}>Monto 
            {factOrderBy=="monto"?( <i className={factOrderDescAsc=="desc"?"fa fa-arrow-up":"fa fa-arrow-down"}></i>):null}
           </button>
           <button className="pointer btn btn-sm btn-outline-arabito" onClick={()=>setfactOrderByFun("estatus")}>Estado 
            {factOrderBy=="estatus"?( <i className={factOrderDescAsc=="desc"?"fa fa-arrow-up":"fa fa-arrow-down"}></i>):null}
           </button>
           <button className="pointer btn btn-sm btn-outline-arabito" onClick={()=>setfactOrderByFun("created_at")}>Fecha 
            {factOrderBy=="created_at"?( <i className={factOrderDescAsc=="desc"?"fa fa-arrow-up":"fa fa-arrow-down"}></i>):null}
           </button>
         </div>


         <div>
           {facturas.map((e,i)=>
            <div key={e.id} className="card-pedidos">
              <div className="d-flex flex-column w-100">
                <div className="text-muted">{e.created_at}</div>
                <div className="d-flex justify-content-between">
                  <h3 onClick={()=>setfactSelectIndexFunInv(i)} className="pointer"><span className="badge bg-secondary w-100">{e.numfact}</span></h3>
                  <span className="text-muted font-italic text-right">
                    Vence: {e.fechavencimiento}
                  </span>
                </div>
                <div>
                  <p>
                    {e.proveedor.descripcion}
                    <br/>
                    <b>{e.descripcion}</b>
                  </p>
                </div>
                <div className="d-flex justify-content-between">
                  <div>
                     <button className="btn btn-outline-success" onClick={()=>setfactSelectIndexFun(i,"agregar")}><i className="fa fa-pencil"></i></button>
                     <button className="btn btn-outline-success" onClick={()=>setfactSelectIndexFun(i,"detalles")}><i className="fa fa-send"></i></button>
                    
                  </div>
                  <div className="d-flex justify-content-between">
                    <div className="btn-group">
                      <button className={(e.estatus=="1"?"btn-success":"btn-danger")+(" btn")}>{moneda(e.monto)}</button>
                      <button className={(e.estatus=="1"?"btn-success":"btn-danger")+(" btn")}>
                        <i className={(e.estatus=="1"?"fa fa-check":"fa fa-times")+(" text-light")}></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
           )}
         </div>
      </>
      :null}
      {factsubView=="agregar"?
        <form onSubmit={setFactura}>

            {factSelectIndex==null?
              <h3>Registrar Factura</h3>
            : <>
              <h3>Editar Factura <button className="btn btn-outline-danger" title="Cancelar" onClick={()=>setfactSelectIndex(null)}><i className="fa fa-times"></i></button></h3>
              <h1 className="text-right">{facturas[factSelectIndex]?
                  facturas[factSelectIndex].numfact
                :null}</h1>
              <h1 className="text-right">{facturas[factSelectIndex]?
                facturas[factSelectIndex].proveedor.descripcion
              :null}</h1>
            </>
            }

            <div className="form-group">
              <label htmlFor="">
                Descripción
              </label> 
                <input type="text" 
                value={factInpdescripcion} 
                onChange={e=>setfactInpdescripcion(e.target.value)} 
                className="form-control"/>
            </div>

            <div className="form-group">
              Proveedor
              <select className="form-control" onChange={e=>setfactInpid_proveedor(e.target.value)} value={factInpid_proveedor}>
                <option value="">----</option>
                {proveedoresList.map(e=><option value={e.id} key={e.id}>{e.descripcion}</option>)}
              </select>
            </div>

            <div className="form-group">
              <label htmlFor="">
                Número de Factura
              </label> 
                <input type="text" 
                value={factInpnumfact} 
                onChange={e=>setfactInpnumfact(e.target.value)} 
                className="form-control"/>
            </div>

            <div className="form-group">
              <label htmlFor="">
                Monto
              </label> 
                <input type="text" 
                value={factInpmonto} 
                onChange={e=>setfactInpmonto(number(e.target.value))} 
                className="form-control"/>
            </div>

            <div className="form-group">
              <label htmlFor="">
                Fecha de Vencimiento
              </label> 
                <input type="date" 
                value={factInpfechavencimiento} 
                onChange={e=>setfactInpfechavencimiento(e.target.value)} 
                className="form-control"/>
            </div>

            <div className="form-group">
              Estatus
              <select className="form-control" onChange={e=>setfactInpestatus(e.target.value)} value={factInpestatus}>
                <option value="0">No</option>
                <option value="1">Sí</option>
              </select>
            </div>


            
            <div className="form-group">
            {factSelectIndex==null?
              <button className="btn btn-outline-success btn-block" type="submit">Guardar</button>
            : 
              <div className="btn-group">
                <button className="btn btn-arabito btn-block" type="submit">Editar</button>
                <button className="btn btn-outline-danger btn-block" onClick={delFactura} type="button"><i className="fa fa-times"></i></button>
                
              </div>
            }
            </div>
          </form>
      :null}

      {factsubView=="detalles"?
        facturas[factSelectIndex]?<>
            {facturas[factSelectIndex].items.map(e=>
              <div key={e.id} className="card-pedidos">
                <div className="d-flex flex-column w-100">
                  <div>
                    <div className="mb-1">
                      <i className="fa fa-times text-danger pull-right" data-id={e.id} onClick={delItemFact}></i>
                      Alterno: {e.producto.codigo_proveedor} | Barras: {e.producto.codigo_barras}
                      <br/>
                      <b>{e.producto.descripcion}</b>
                    </div>
                  </div>
                  <div className="d-flex justify-content-between">
                    <button className={"btn btn-arabito"}>Ct. {e.cantidad}</button>

                    <div className="btn-group">
                      <button className={"btn btn-arabito"}>{e.producto.precio_base}</button>
                      <button className={"btn btn-success"}>{e.producto.precio}</button>
                    </div>
                  </div>
                </div>
              </div>
            )}
        </>:null
      :null}
    </div>
  )
}
export default Facturas