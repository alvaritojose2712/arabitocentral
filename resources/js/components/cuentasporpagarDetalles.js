export default function CuentasporpagarDetalles({
    selectCuentaPorPagarId,
    setSelectCuentaPorPagarId,
    setqcuentasPorPagarDetalles,
    qcuentasPorPagarDetalles,
    selectCuentaPorPagarProveedorDetallesFun
}){
    return (
        <div>
            <form onSubmit={selectCuentaPorPagarProveedorDetallesFun} className="input-group mb-2">
                <input type="text" className="form-control " onChange={e=>setqcuentasPorPagarDetalles(e.target.value)} value={qcuentasPorPagarDetalles} />
                <button className="btn btn-success"><i className="fa fa-search"></i></button>
            </form>
            {
                selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                    <div className="text-secondary mb-3 pointer shadow p-2 card" key={e.id}>
                        <div className="d-flex justify-content-between mb-1">
                            <small className="text-muted">{e.created_at}</small>
                            <span className={((e.estatus==0?"btn-danger":e.estatus==1?"btn-warning":e.estatus==2?"btn-success":""))+(" btn-sm btn pointer")}>
                                {e.estatus==0?"CREADA":""}
                                {e.estatus==1?"ENVIADA":""}
                                {e.estatus==2?"PROCESADA":""}
                            </span>
                        </div>
                        <div>
                            <div onClick={()=>setfactSelectIndexFunInv(i)} className="">
                                <span className={(i==factSelectIndex?"btn-success":"btn-sinapsis")+(" w-100 btn fs-3 pointer")}>{e.numfact}</span>
                            </div>
                        </div>
                        <p>
                            {e.proveedor.descripcion}
                        </p>
                        <div className="d-flex justify-content-between">
                            <div className="btn-group">
                                
                                <button className="btn btn fs-3 btn-sinapsis" onClick={()=>setfactSelectIndexFun(i,"agregar")}><i className="fa fa-pencil"></i></button>
                                
                                {e.estatus==0?<button className="btn btn fs-3 btn-success" onClick={()=>sendFacturaCentral(e.id)}><i className="fa fa-send"></i></button>:""}

                                {e.estatus==1?<button className="btn btn fs-3 btn-success" onClick={()=>{
                                setfactSelectIndexFunInv(i);
                                setView("inventario")
                                setsubViewInventario("inventario")
                                }}><i className="fa fa-hand-pointer-o"></i></button>:""}
                            </div>
                            <div><span className="text-success fs-3">{moneda(e.monto)}</span></div>
                        </div>
                    </div>
                )
                : null : null
                
            }           
        </div>
    )
}