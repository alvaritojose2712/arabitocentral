export default function serarchBar({
    selectCuentaPorPagarProveedorDetallesFun,
    cuentaporpagarAprobado,
    setcuentaporpagarAprobado,
    setqcuentasPorPagarDetalles,
    qcuentasPorPagarDetalles,
    setselectProveedorCxp,
    selectProveedorCxp,
    proveedoresList,
    sucursalcuentasPorPagarDetalles,
    setsucursalcuentasPorPagarDetalles,
    sucursales,
    categoriacuentasPorPagarDetalles,
    setcategoriacuentasPorPagarDetalles,
}){
    return(
    <form onSubmit={e=>{e.preventDefault();selectCuentaPorPagarProveedorDetallesFun()}} className="mb-3 card p-3 shadow">
        <div className="input-group">
            <button className={("btn btn-lg btn-"+(cuentaporpagarAprobado==0?"sinapsis":""))} type="button" onClick={e=>setcuentaporpagarAprobado(0)}><i className="fa fa-clock-o"></i></button>
            <button className={("btn btn-lg btn-"+(cuentaporpagarAprobado==1?"success":""))} type="button" onClick={e=>setcuentaporpagarAprobado(1)}><i className="fa fa-check"></i></button>
            
            <input type="text" className="form-control form-control-lg fs-3" placeholder={"Buscar en..."} onChange={e=>setqcuentasPorPagarDetalles(e.target.value)} value={qcuentasPorPagarDetalles} />
            <select className="form-control" value={selectProveedorCxp} onChange={e=>{setselectProveedorCxp(e.target.value);selectCuentaPorPagarProveedorDetallesFun("buscar",e.target.value)}}>
                <option value="">-TODOS LOS PROVEEDORES-</option>
                {proveedoresList.map(e=>
                    <option key={e.id} value={e.id}>{e.descripcion}-{e.rif}</option>
                )}
            </select>
            <select className="form-control form-control-lg" value={sucursalcuentasPorPagarDetalles} onChange={e=>setsucursalcuentasPorPagarDetalles(e.target.value)}>
                <option value="">-SUCURSAL-</option>
                {sucursales.map(e=>
                    <option key={e.id} value={e.id}>{e.codigo}</option>
                )}
            </select>
            <button type="button" className="btn btn-warning btn-lg" onClick={()=>selectCuentaPorPagarProveedorDetallesFun("reporte")}><i className="fa fa-print"></i></button>
            <button type="submit" className="btn btn-success btn-lg"><i className="fa fa-search"></i></button>
        </div>

        {/*  <div className="input-group">
            <select className="form-control form-control-sm" value={categoriacuentasPorPagarDetalles} onChange={e=>setcategoriacuentasPorPagarDetalles(e.target.value)}>
                <option value="">-CATEGORÍA-</option>
                <option value="1">COMPRAS</option>
                <option value="2">SERVICIOS</option>
            </select>
        </div>   */}              
    </form>
    )
}