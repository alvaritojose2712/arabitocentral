import { useState, useEffect } from "react";

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

    numcuentasPorPagarDetalles,
    setnumcuentasPorPagarDetalles,
    setqcampoBusquedacuentasPorPagarDetalles,
    qcampoBusquedacuentasPorPagarDetalles,
    setqinvertircuentasPorPagarDetalles,
    qinvertircuentasPorPagarDetalles,
    isonlyestatus=false
}){
    const [buscadorProveedor, setbuscadorProveedor] = useState("")

    useEffect(()=>{
        let fil = proveedoresList.find(e=>e.descripcion.toLowerCase().indexOf(buscadorProveedor.toLowerCase())!=-1)
        if (fil) {
            if (fil && buscadorProveedor!="") {
                let id = fil.id
                setselectProveedorCxp(id)
                selectCuentaPorPagarProveedorDetallesFun("buscar",id)
            }
        }
    },[buscadorProveedor])

    useEffect(()=>{
        if (isonlyestatus!==false) {
            if (isonlyestatus===1) {
                setcuentaporpagarAprobado(1)
            }
            if (isonlyestatus===0) {
                setcuentaporpagarAprobado(0)
            }
        }
    },[])


    return(
    <form onSubmit={e=>{e.preventDefault();selectCuentaPorPagarProveedorDetallesFun()}} className="mb-3 card p-3 shadow">
        <div className="input-group">
            {isonlyestatus===false||isonlyestatus===0?<button className={("btn btn-lg btn-"+(cuentaporpagarAprobado==0?"sinapsis":""))} type="button" onClick={e=>setcuentaporpagarAprobado(0)}><i className="fa fa-clock-o"></i></button>:null}
            {isonlyestatus===false||isonlyestatus===1?<button className={("btn btn-lg btn-"+(cuentaporpagarAprobado==1?"success":""))} type="button" onClick={e=>setcuentaporpagarAprobado(1)}><i className="fa fa-check"></i></button>:null}
            
            <input type="text" className="form-control form-control-lg fs-3" placeholder={"Buscar factura..."} onChange={e=>setqcuentasPorPagarDetalles(e.target.value)} value={qcuentasPorPagarDetalles} />
            <select className="form-control" onChange={e=>setqcampoBusquedacuentasPorPagarDetalles(e.target.value)} value={qcampoBusquedacuentasPorPagarDetalles}>
                <option value="numfact">NUMFACT</option>
                <option value="monto">MONTO</option>
                <option value="descripcion">DESCRIPCION</option>
                <option value="fechaemision">FECHA EMISION</option>
                <option value="fechavencimiento">FECHA VENCIMIENTO</option>
                <option value="fecharecepcion">FECHA RECEPCION</option>
                <option value="nota">NOTA</option>
            </select>
            <button type="button" className={("btn ")+(qinvertircuentasPorPagarDetalles==1?"btn-success":"btn-danger")} onClick={()=>setqinvertircuentasPorPagarDetalles(qinvertircuentasPorPagarDetalles==1?0:1)}>
                Invertir Busq. {qinvertircuentasPorPagarDetalles==1?<i className="fa fa-exclamation-triangle"></i>:null}
            </button>

            <input type="text" value={buscadorProveedor} onChange={e=>setbuscadorProveedor(e.target.value)} className="form-control" placeholder="Buscar proveedor..." />
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
            <select className="form-control" value={numcuentasPorPagarDetalles} onChange={e=>setnumcuentasPorPagarDetalles(e.target.value)}>
                <option value="20">Resultados: 20 (Carga Rápida)</option>
                <option value="50">Resultados: 50</option>
                <option value="100">Resultados: 100</option>
                <option value="">Resultados: Todos (Carga Lenta)</option>
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