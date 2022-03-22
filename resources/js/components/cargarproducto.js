
function Cargarproducto({
  setporcenganancia,
  productosInventario,
  qBuscarInventario,
  setQBuscarInventario,

  setIndexSelectInventario,
  indexSelectInventario,
  inputBuscarInventario,

  inpInvbarras,
  setinpInvbarras,
  inpInvcantidad,
  setinpInvcantidad,
  inpInvalterno,
  setinpInvalterno,
  inpInvunidad,
  setinpInvunidad,
  inpInvcategoria,
  setinpInvcategoria,
  inpInvdescripcion,
  setinpInvdescripcion,
  inpInvbase,
  setinpInvbase,
  inpInvventa,
  setinpInvventa,
  inpInviva,
  setinpInviva,

  number,
  guardarNuevoProducto,


  proveedoresList,

  delProducto,

  inpInvid_proveedor,
  setinpInvid_proveedor,

  inpInvid_marca,
  setinpInvid_marca,

  inpInvid_deposito,
  setinpInvid_deposito,
  
  inpInvLotes,
  Invnum,
  setInvnum,
  InvorderColumn,
  setInvorderColumn,
  InvorderBy,
  setInvorderBy,

  addNewLote,
  changeModLote,
  type,
  categorias,

}) {

  const setIndexSelectInventarioFun = e => {
    let index = e.currentTarget.attributes["data-index"].value
    if (index==indexSelectInventario) {
      setIndexSelectInventario(null)
    }else{
      setIndexSelectInventario(index)
    }
  }
  

 
  return (
    <>
      <div className="container">
        
        <div className="row">
          <div className="col">
              <div className="input-group ">
                <input type="text" 
                ref={inputBuscarInventario}
                className="form-control" 
                placeholder="Buscar... Presiona (ESC)" 
                value={qBuscarInventario} 
                onChange={e=>setQBuscarInventario(e.target.value)}/>
                <select value={Invnum} onChange={e=>setInvnum(e.target.value)}>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                  <option value="500">500</option>
                  <option value="2000">2000</option>
                </select>
                <select value={InvorderColumn} onChange={e=>setInvorderColumn(e.target.value)}>
                  <option value="id">id</option>
                  <option value="descripcion">descripcion</option>
                  <option value="precio">precio</option>
                  <option value="cantidad">cantidad</option>
                  <option value="codigo">codigo</option>
                </select>
                <select value={InvorderBy} onChange={e=>setInvorderBy(e.target.value)}>
                  <option value="asc">Asc</option>
                  <option value="desc">Desc</option>
                </select>
                <div className="input-group-prepend">
                  <button className="btn btn-outline-secondary" type="button"><i className="fa fa-search"></i></button>
                </div>
              </div>
              
              { 
                productosInventario.length
                ? productosInventario.map( (e,i) =>
                  <div 
                  onClick={setIndexSelectInventarioFun} 
                  data-index={i}
                  key={i}
                  className={(indexSelectInventario==i?"bg-sinapsis":"bg-light text-secondary")+" card mt-2 pointer"}>
                    <div className="card-header flex-row justify-content-between">
                      <div>
                        <small>ID.{e.id}</small>
                      </div>
                      <div className="d-flex justify-content-between">
                        <div><span>{e.codigo_barras}</span></div>
                        <div><span className="h3">{e.precio}</span></div>
                      </div>
                    </div>
                    <div className="card-body d-flex justify-content-between">
                      <div className="">
                        <h5 
                        className="card-title"
                        ><b>{e.descripcion}</b></h5>
                      </div> 
                      <p className="card-text p-1">
                        Ct. <b>{e.cantidad}</b>
                      </p>
                    </div>
                  </div>
                 )
                : <div className='h3 text-center text-dark mt-2'><i>¡Sin resultados!</i></div>
              }
          </div>
          <div className="col">        
            <form className="container-fluid" onSubmit={guardarNuevoProducto}>
              <h3>Producto</h3>

              <div className="row">
                <div className="col text-center">Bar. <input required className="m-3 h2" 
                type="text" 
                value={inpInvbarras} 
                onChange={e=>setinpInvbarras(e.target.value)} 
                placeholder="Barras."/></div>
              </div>
              <div className="row">
                <div className="col text-center">
                  Ct. <input required className="h1 m-5 input-ct" 
                  type="text"
                  value={inpInvcantidad} 
                  onChange={e=>setinpInvcantidad(number(e.target.value))}
                  placeholder="Ct."/>
                </div>
              </div>
              <div className="row">
                <div className="col"><input className="form-control" 
                type="text" 
                value={inpInvalterno} 
                onChange={e=>setinpInvalterno(e.target.value)}
                placeholder="Alterno."/></div>
                <div className="col">
                  <select className="form-control"
                  value={inpInvunidad} 
                  onChange={e=>setinpInvunidad(e.target.value)}
                  >
                    <option value="UND">UND</option>
                    <option value="PAR">PAR</option>
                    <option value="JUEGO">JUEGO</option>
                    <option value="PQT">PQT</option>
                    <option value="MTR">MTR</option>
                    <option value="KG">KG</option>
                    <option value="GRS">GRS</option>
                    <option value="LTR">LTR</option>
                    <option value="ML">ML</option>
                  </select>
                </div>
                <div className="col">
                <select className="form-control"
                  value={inpInvcategoria} 
                  onChange={e=>setinpInvcategoria(e.target.value)}
                  >
                    {categorias.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                  </select>
                </div>
              </div>
              <div className="row">
                <div className="col">
                  Proveedor
                  <select className="form-control" onChange={e=>setinpInvid_proveedor(e.target.value)} value={inpInvid_proveedor}>
                    <option value="">----</option>
                    {proveedoresList.map(e=><option value={e.id} key={e.id}>{e.descripcion}</option>)}
                  </select>
                </div>

                <div className="col">
                  Depósito
                  <input className="form-control" onChange={e=>setinpInvid_deposito(e.target.value)} value={inpInvid_deposito}/>
                </div>

                <div className="col">
                  Marca
                  <input className="form-control" onChange={e=>setinpInvid_marca(e.target.value)} value={inpInvid_marca}/>
                </div>
                


              </div>
              <div className="row">
                <div className="col text-center">
                  <textarea required className="m-5 h3" cols="40" rows="3"
                  value={inpInvdescripcion} 
                  onChange={e=>setinpInvdescripcion(e.target.value)}
                  placeholder="Descripción"></textarea>
                </div>
              </div>
              <div className="row">
                <div className="col text-right">
                  <div className="bg-sinapsis p-2">
                    Base. <input required className="h1 input-ct" 
                    type="text"
                    value={inpInvbase} 
                    onChange={e=>setinpInvbase(number(e.target.value))}
                    placeholder="Base."/>
                  </div>
                </div>
                <div className="col">
                  <div className="bg-success-light p-2">
                    <input required className="h1 input-ct" 
                    type="text"
                    value={inpInvventa} 
                    onChange={e=>setinpInvventa(number(e.target.value))}
                    placeholder="Venta."/> Venta <span className="btn pointer" onClick={()=>setporcenganancia("unique")}>%</span>.
                  </div>
                </div>
              </div>
              <div className="row">
                <div className="col text-center">
                  <div className="mt-2">
                    <label htmlFor="">
                    IVA % <input className="input-ct" 
                    type="text"
                    value={inpInviva} 
                    onChange={e=>setinpInviva(number(e.target.value,2))}
                    placeholder="Iva."/>
                    </label>
                  </div>
                </div>
              </div>

              <div className="row">
                <div className="col">
                  <table className="table">
                    <thead>
                      <tr>
                        <th className="">Num. Lote</th>
                        <th className="">Fabricado</th>
                        <th className="">Vence</th>
                        <th className="">
                          Ct.
                        </th>
                        <th>
                         <span className="btn-sm btn btn-success" onClick={addNewLote}><i className="fa fa-plus"></i></span>

                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      {inpInvLotes.map((e,i)=><tr key={i}>
                        <td colSpan={4}>
                          <div key={e.id} onDoubleClick={() => changeModLote(null, i, e.id, "update")} className="p-1 mb-2 d-flex prod shadow">
                            <div className="d-flex flex-row m-1 justify-content-between">
                              <div className=''>
                                <input type="text"
                                  disabled={type(e.type)} className="form-control form-control-sm"
                                  value={e.lote}
                                  onChange={e => changeModLote((e.target.value), i, e.id, "changeInput", "lote")}
                                  placeholder="Lote..." />
                              </div>
                              <div className=''>
                                <input type="date"
                                  disabled={type(e.type)} 
                                  className="form-control form-control-sm"
                                  value={e.creacion}
                                  onChange={e => changeModLote((e.target.value), i, e.id, "changeInput", "creacion")}
                                  />
                              </div>
                              <div className=''>
                                <input type="date"
                                  disabled={type(e.type)} 
                                  className="form-control form-control-sm"
                                  value={e.vence}
                                  onChange={e => changeModLote((e.target.value), i, e.id, "changeInput", "vence")}
                                  />
                              </div>
                              <div className=''>
                                <input type="text"
                                  disabled={type(e.type)}
                                  className="form-control form-control-sm"
                                  value={e.cantidad}
                                  placeholder="Ct..."
                                  onChange={e => changeModLote((e.target.value), i, e.id, "changeInput", "cantidad")}
                                />
                              </div>
                            

                            </div>
                            
                            
                          </div>
                        </td>
                        <td>
                          <div className='d-flex justify-content-between'>
                            {!e.type ?
                              <>
                                <button type="button" className="btn btn-danger" onClick={() => changeModLote(null, i, e.id, "delMode")}><i className="fa fa-trash"></i></button>
                                <button type="button" className="btn btn-warning" onClick={() => changeModLote(null, i, e.id, "update")}><i className="fa fa-pencil"></i></button>
                              </>
                              : null}
                            {e.type === "new" ?
                              <button type="button" className="btn btn-danger" onClick={() => changeModLote(null, i, e.id, "delNew")}><i className="fa fa-times"></i></button>
                              : null}
                            {e.type === "update" ?
                              <button type="button" className="btn btn-warning" onClick={() => changeModLote(null, i, e.id, "delModeUpdateDelete")}><i className="fa fa-times"></i></button>
                              : null}
                            {e.type === "delete" ?
                              <button type="button" className="btn btn-danger" onClick={() => changeModLote(null, i, e.id, "delModeUpdateDelete")}><i className="fa fa-arrow-left"></i></button>
                              : null}
                          </div>
                        </td>
                      </tr>)}
                    </tbody>
                  </table>
                </div>
              </div>
              <div className="row">
                <div className="col text-center">
                  {indexSelectInventario==null?
                    <button className="btn btn-outline-success btn-block" type="submit">Guardar</button>
                  :
                    <div className="btn-group">
                      <button className="btn btn-sinapsis btn-block" type="submit">Editar</button>
                      <button className="btn btn-outline-danger btn-block" onClick={delProducto} type="button"><i className="fa fa-times"></i></button>
                    </div>
                  }
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </>
  )
}
export default Cargarproducto