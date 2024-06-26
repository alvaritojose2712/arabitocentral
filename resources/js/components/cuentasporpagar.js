import { useState } from "react"

export default function Cuentasporpagar({
    moneda,
    getsucursalDetallesData,
    sucursalDetallesData,

    setqcuentasPorPagar,
    qcuentasPorPagar,
    selectCuentaPorPagarProveedorDetalles,

    setviewmainPanel,
    subViewCuentasxPagar,
    setsubViewCuentasxPagar,

    selectProveedorCxp,
    setselectProveedorCxp,
    selectCuentaPorPagarProveedorDetallesFun,
}){
    return (
        <div className="container">
            {subViewCuentasxPagar=="proveedor"?
                <>
                    <form onSubmit={(event)=>{getsucursalDetallesData();event.preventDefault()}} className="input-group mb-2">
                        <input type="text" className="form-control form-control-lg fs-3" placeholder="Buscar proveedor" onChange={e=>setqcuentasPorPagar(e.target.value)} value={qcuentasPorPagar} />
                        <button className="btn btn-success"><i className="fa fa-search"></i></button>
                    </form>
                    <div className="m-2 d-flex justify-content-between align-items-center">
                        <b>TOTAL</b>
                    {
                        sucursalDetallesData.sum
                        ? <span className={"btn btn-warning fs-5 text-"+(sucursalDetallesData.sum<0?"danger":"success")}>
                            $ {moneda(sucursalDetallesData.sum)}
                        </span>
                        : null 
                    } 
                    </div>

                    {
                        sucursalDetallesData.cuentasporpagar?sucursalDetallesData.cuentasporpagar.length
                        ? sucursalDetallesData.cuentasporpagar.map( (e,i) =>
                            <div 
                            key={e.id}
                            onClick={()=>{setselectProveedorCxp(e.id);selectCuentaPorPagarProveedorDetallesFun("buscar",e.id);setsubViewCuentasxPagar("detallado")}}
                            className={("bg-light")+" text-secondary card mb-3 pointer shadow"}>
                                <div className="card-header flex-row justify-content-between">
                                    <div className="d-flex justify-content-between">
                                        <div className="w-50">
                                            <b>{e.descripcion}</b><br/>
                                            <small className="fst-italic">{e.rif}</small>
                                        </div>
                                        <div className="w-50 text-right">
                                            {
                                                e.balance!=0?
                                                    <>
                                                        <span className={"h3 text-"+(e.balance<0?"danger":"success")}>$ <b>{moneda(e.balance)}</b></span>
                                                    </>
                                                :null
                                            }
                                        </div>

                                    </div>
                                    <div className="text-center">
                                            <span className="fs-4 text-danger me-4">V. <b>{moneda(e.vencido)}</b></span>
                                            <span className="fs-4 text-sinapsis">P/V. <b>{moneda(e.porVencer)}</b></span>
                                    </div>
                                </div>
                            </div>
                        )
                        : null : null
                    } 
                </>
            :null}






            <button className="btn boton-fijo-inferiorizq btn-sinapsis" onClick={()=>setviewmainPanel("proveedores")} type="button">
                <i className="fa fa-plus"></i>
            </button>          
        </div>
    )
}