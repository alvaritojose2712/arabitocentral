import { useEffect, useState } from "react";
import  SearchBarFacturas  from "./searchBarFacturas";

export default function Comprascargarfactsdigitales({
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
    qCampocuentasPorPagarDetalles,
    setOrdercuentasPorPagarDetalles,
    setqCampocuentasPorPagarDetalles,
    selectCuentaPorPagarId,
    qcuentasPorPagarTipoFact,
    dateFormat,
    returnCondicion,
    colorSucursal,
    moneda,
}){
    return <div className="container-fluid">
        <SearchBarFacturas
            selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
            cuentaporpagarAprobado={cuentaporpagarAprobado}
            setcuentaporpagarAprobado={setcuentaporpagarAprobado}
            setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
            qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
            setselectProveedorCxp={setselectProveedorCxp}
            selectProveedorCxp={selectProveedorCxp}
            proveedoresList={proveedoresList}
            sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
            setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}
            sucursales={sucursales}
            categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
            setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
        />

        <table className="table table-borderless table-striped mb-500">
                <thead className="">
                    <tr className="align-middle">
                        <th colSpan={6}>
                            <div className="btn-group">
                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="abonos"?"btn-success":"btn-outline-success")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="abonos"?"":"abonos")}>PAGOS</span>

                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="pagadas"?"btn-medsuccess":"btn-outline-medsuccess")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="pagadas"?"":"pagadas")}>PAGADAS</span>

                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="semipagadas"?"btn-primary":"btn-outline-primary")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="semipagadas"?"":"semipagadas")}>ABONADAS</span>
                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="porvencer"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="porvencer"?"":"porvencer")}>POR VENCER</span>
                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="vencidas"?"btn-danger":"btn-outline-danger")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="vencidas"?"":"vencidas")}>VENCIDAS</span>
                            </div>

                        </th>
                        <th colSpan={6} className="text-right">
                        { 
                            selectCuentaPorPagarId?
                                selectCuentaPorPagarId.sum!=""? 
                                    <>
                                        Resultados
                                        <span className="text-muted fs-2 ms-2">
                                            <b>({selectCuentaPorPagarId.sum})</b>
                                        </span>
                                    </>
                                :null
                            :null
                        }
                        </th>
                        {/* <th colSpan={2} className="text-right">
                            { 
                            selectCuentaPorPagarId?
                                selectCuentaPorPagarId.balance!=""? 
                                        <span className={(selectCuentaPorPagarId.balance<0? "text-danger": "text-success")+(" fs-1 mb-1 mt-1 bg-warning p-2")}>{moneda(selectCuentaPorPagarId.balance)}</span>
                                    :null
                                :null
                            } 
                        </th> */}
                    </tr>
                    <tr>
                       
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="created_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("created_at")}} className="pointer  p-3">
                            CREADA
                        </th> 
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="updated_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("updated_at")}} className="pointer  p-3">
                            ACTUALIZADA
                        </th> 
                        
                        <th>ID</th>

                        <th className="p-3 text-right">
                            <span className="pointer" onClick={()=>{if(qCampocuentasPorPagarDetalles=="fechaemision"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("fechaemision")}}>
                                EMISIÓN
                            </span>
                        </th>       
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="fechavencimiento"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("fechavencimiento")}} className="pointer  p-3 text-right">
                            VENCE
                        </th>  

                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_proveedor"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_proveedor")}} className="pointer p-3 text-right">
                            PROVEEDOR
                        </th>  
                        <th>

                        </th>
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="numfact"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("numfact")}} className="pointer  p-3 text-center">
                            NúMERO DE FACTURA
                        </th>  
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_sucursal"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_sucursal")}} className="pointer  p-3 text-right">
                            ORIGEN
                        </th>  
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer  p-3 text-right">
                            MONTO BRUTO
                        </th>

                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer  p-3 text-right">
                            DESCUENTO
                        </th>

                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer  p-3 text-right">
                            MONTO NETO
                        </th>
                            
                    </tr>
                </thead> 
                    
            {
                selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                    <tbody key={e.id}>
                        <tr className={(" pointer border-top border-top-5 border-dark")}>
                            <td className="">
                                <small className="text-muted">{e.created_at}</small>
                            </td> 
                            <td className="">
                                <small className="text-muted">{e.updated_at}</small>
                            </td>
                            <td>
                                {i+1}
                            </td>
                            <td className="text-right fs-4">
                                <span className="text-successfuerte">{dateFormat(e.fechaemision,"dd-MM-yyyy")}</span>
                            </td>       
                            <td className="text-right fs-4">
                                <span className="text-danger ms-1">{dateFormat(e.fechavencimiento,"dd-MM-yyyy")} <br />
                                    <span className={(e.dias<0? "text-danger": "text-success")+(" ")}>({e.dias} días)</span>
                                </span>
                            </td>  
                            <td className="text-right">
                                <span className="fw-bold fs-4">{e.proveedor?e.proveedor.descripcion:null}</span>
                            </td>  
                            <td>
                                <span className="m-2">
                                    {e.aprobado==0?<i className="fa-2x fa fa-clock-o text-sinapsis"></i>:<i className="fa fa-check text-success"></i>} 
                                </span>
                            </td>
                            <td className="text-right">
                                
                                {/* <input type="checkbox" className="form-check-input me-1 fs-2" onMouseEnter={event=>selectFacts(event,e.id,"leave")} onChange={event=>selectFacts(event,e.id)} checked={dataselectFacts.data.filter(selefil =>selefil.id==e.id).length?
                                    true
                                :false} /> */}
                                <span className={(returnCondicion(e.condicion))+(" w-100 btn fs-2 pointer fw-bolder text-light ")}> 
                                    {e.numfact}
                                </span>
                            </td>  
                            <td className=" text-right">
                                <button className={"btn w-100 fw-bolder fs-3"} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>
                                    {e.sucursal.codigo}
                                </button>
                            </td>
                            <td className=" text-right">
                                <span className="text-muted fs-6">{moneda(e.monto_bruto)}</span>
                            </td>
                            <td className=" text-right">
                                {
                                    e.monto_descuento!="" && e.monto_descuento!="0"?
                                        <span className="text-muted fst-italic fs-6">{moneda(e.monto_descuento)} <br /> ({e.descuento}%)</span>
                                    :null
                                }
                            </td>

                            <td className=" text-right">
                                <span className={(e.monto<0? "text-danger": "text-success")+(" fs-3 fw-bold ")}>{moneda(e.monto)}</span>
                            </td>
                        </tr>
                    </tbody>
                )
                : null : null
            } 
            
        </table>
    </div>

}