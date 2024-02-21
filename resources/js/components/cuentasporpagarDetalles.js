import { useEffect, useState } from "react";
import  SearchBarFacturas  from "./searchBarFacturas";

export default function CuentasporpagarDetalles({
    modeEditarFact,
    selectCuentaPorPagarId,
    setSelectCuentaPorPagarId,
    setqcuentasPorPagarDetalles,
    qcuentasPorPagarDetalles,
    selectCuentaPorPagarProveedorDetallesFun,
    factSelectIndex,
    moneda,


    qCampocuentasPorPagarDetalles,
    setqCampocuentasPorPagarDetalles,
    qFechaCampocuentasPorPagarDetalles,
    setqFechaCampocuentasPorPagarDetalles,
    setfechacuentasPorPagarDetalles,
    fechacuentasPorPagarDetalles,
    categoriacuentasPorPagarDetalles,
    setcategoriacuentasPorPagarDetalles,
    sucursalcuentasPorPagarDetalles,
    setsucursalcuentasPorPagarDetalles,
    tipocuentasPorPagarDetalles,
    settipocuentasPorPagarDetalles,

    OrdercuentasPorPagarDetalles,
    setOrdercuentasPorPagarDetalles,

    OrderFechacuentasPorPagarDetalles,
    setOrderFechacuentasPorPagarDetalles,

    setSelectCuentaPorPagarDetalle,
    SelectCuentaPorPagarDetalle,
    cuentasporpagarDetallesView,
    setcuentasporpagarDetallesView,
    setselectFactEdit,

    qcuentasPorPagarTipoFact,
    setqcuentasPorPagarTipoFact,

    selectProveedorCxp,
    setselectProveedorCxp,

    setcuentasPagosDescripcion,
    setcuentasPagosMonto,
    setselectFactPagoid,
    setselectFactPagoid_sucursal,
    setcuentasPagosMetodo,
    setcuentasPagosFecha,
    setselectAbonoFact,
    showImageFact,
    cuentaporpagarAprobado,
    setcuentaporpagarAprobado,
    changeAprobarFact,
    delCuentaPorPagar,
    getSucursales,
    sucursales,
    subViewCuentasxPagar,
    setsubViewCuentasxPagar,
    selectFacts,
    dataselectFacts,

    descuentoGeneralFats,
    setdescuentoGeneralFats,
    sendDescuentoGeneralFats,
    abonarFact,
    proveedoresList,
    

}){
    
    
    const setInputsNewFact = () => {
        setselectFactEdit(null)
        setcuentasPagosDescripcion("")
        setcuentasPagosMonto("")
        setcuentasPagosMetodo("")
        setcuentasPagosFecha("")
        setselectFactPagoid(null)
        setselectFactPagoid_sucursal(null)
        setselectAbonoFact([])
    }

    let dataCuenta = {}
    if (SelectCuentaPorPagarDetalle) {
        if (selectCuentaPorPagarId) {
            if (selectCuentaPorPagarId.detalles) {
                let f = selectCuentaPorPagarId.detalles.filter(e=>e.id==SelectCuentaPorPagarDetalle)
                if (f.length) {
                    dataCuenta = f[0]
                }
            }
        }
    }

    

    const [selectFactViewDetalles, setselectFactViewDetalles] = useState(null)
    return (
        <div className="container-fluid p-0 mb-6">
            <div className="row">
                {
                SelectCuentaPorPagarDetalle?
                    dataCuenta?
                    <div>
                        <span className="btn btn-danger boton-fijo-inferiorder btn-sm" onClick={()=>setSelectCuentaPorPagarDetalle(null)}>
                            <i className="fa fa-arrow-left"></i>
                        </span>
                        <table className="table table-borderless table-sm">
                            <tbody>
                                {dataCuenta.monto_abonado && dataCuenta.pagos?
                                <>
                                    <tr className="">
                                        <th className="align-middle">
                                            ABONADO                                        
                                        </th>
                                        <td colSpan={2} className="text-success text-right align-middle">
                                            {moneda(dataCuenta.monto_abonado)}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colSpan={2} className="align-middle">
                                            <table className="table table-borderless table-sm">
                                                <tbody>
                                                    {dataCuenta.monto_abonado && dataCuenta.pagos?
                                                    <>
                                                        {dataCuenta.pagos.map(e=>
                                                            <tr key={e.id} className="border-top">
                                                                <td className=" align-middle">
                                                                    <span className="text-muted fst-italic fs-7">{e.created_at}</span>
                                                                    <br />
                                                                    <span className="btn-sinapsis btn pointer btn-sm w-100 fs-7">
                                                                        {e.numfact}
                                                                    </span> 
                                                                </td>
                                                                <td className="text-right align-bottom">
                                                                    <span className="text-sinapsis">{moneda(e.monto)}</span> / <span className="text-success">{moneda(e.pivot.monto)}</span>
                                                                </td>
                                                            </tr>
                                                        )}
                                                    </>
                                                    :null}
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr className="">
                                        <th className="align-middle">
                                            DEUDA                                        
                                        </th>
                                        <td colSpan={2} className="text-danger text-right align-middle">
                                            {moneda(dataCuenta.monto)}
                                        </td>
                                    </tr>
                                    <tr className="">
                                        <th className="align-middle">
                                            BALANCE                                        
                                        </th>
                                        <td colSpan={2} className={((dataCuenta.balance)<0? "text-danger": "text-success")+(" fs-4 text-right align-middle")}>
                                            {moneda(dataCuenta.balance)}
                                        </td>
                                    </tr>
                                    
                                </>
                                :null}

                                {dataCuenta.facturas ?
                                    dataCuenta.facturas.length?
                                        <>
                                            <tr className="">
                                                <th colSpan={3}>
                                                    FACTURAS ASOCIADAS                                        
                                                </th>
                                            </tr>
                                            <tr>
                                                <td colSpan={2}>
                                                    <table className="table table-borderless table-sm">
                                                        <tbody>
                                                            {dataCuenta && dataCuenta.facturas?
                                                            <>
                                                                {dataCuenta.facturas.map(e=>
                                                                    <tr key={e.id} className="border-top">
                                                                        <td className=" align-middle text-muted fst-italic fs-7">
                                                                            {e.created_at}
                                                                        </td>
                                                                        <td className=" align-middle">
                                                                            <span className="btn-sinapsis btn pointer btn-sm">
                                                                                {e.numfact}
                                                                            </span> 
                                                                        </td>
                                                                        <td className="text-right align-middle">
                                                                            <span className="text-sinapsis">{moneda(e.pivot.monto)}</span> / <span className="text-success">{moneda(e.monto)}</span>
                                                                        </td>
                                                                    </tr>
                                                                )}
                                                            </>
                                                            :null}
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </>
                                    :null
                                :null}
                            </tbody>
                        </table>
                        
                    
                        <table className="table table-sm">
                            {dataCuenta.sucursal?
                                <tbody>
                                    
                                    <tr>
                                        <td>ENVIADO POR</td>
                                        <td><small className="text-muted">{dataCuenta.sucursal.codigo}</small></td>
                                    </tr>
                                    <tr>
                                        <td>PROVEEDOR</td>
                                        <td>{dataCuenta.proveedor.descripcion} <br /> <small className="text-muted">RIF. {dataCuenta.proveedor.rif}</small></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {dataCuenta.monto>0?"# PAGO":"# FACTURA"}
                                        </td>
                                        <td>
                                            <button 
                                            className={
                                                (dataCuenta.condicion=="pagadas"?"btn-medsuccess":(dataCuenta.condicion=="vencidas"?"btn-danger":(dataCuenta.condicion=="porvencer"?"btn-sinapsis":(dataCuenta.condicion=="semipagadas"?"btn-primary":(dataCuenta.condicion=="abonos"?"btn-success":null)))))+(" btn-sm btn")
                                            }
                                            onClick={()=>showImageFact(dataCuenta.descripcion)}
                                            type="button">{dataCuenta.numfact}</button>
                                        </td>
                                    </tr>
                                    {dataCuenta.numnota?
                                        <tr>
                                            <td># CONTROL</td>
                                            <td>{dataCuenta.numnota}</td>
                                        </tr>
                                    :null}
                                    
                                    <tr>
                                        <th>SUBTOTAL</th>
                                        <td className={dataCuenta.subtotal<0?"text-danger":("text-success")}>{moneda(dataCuenta.subtotal)}</td>
                                    </tr>
                                    <tr>
                                        <th>DESCUENTO</th>
                                        <td className={dataCuenta.descuento<0?"text-danger":("text-success")}>{moneda(dataCuenta.descuento)}</td>
                                    </tr>
                                    <tr>
                                        <th>EXENTO</th>
                                        <td className={dataCuenta.monto_exento<0?"text-danger":("text-success")}>{moneda(dataCuenta.monto_exento)}</td>
                                    </tr>
                                    <tr>
                                        <th>GRAVABLE</th>
                                        <td className={dataCuenta.monto_gravable<0?"text-danger":("text-success")}>{moneda(dataCuenta.monto_gravable)}</td>
                                    </tr>
                                    <tr>
                                        <th>IVA</th>
                                        <td className={dataCuenta.iva<0?"text-danger":("text-success")}>{moneda(dataCuenta.iva)}</td>
                                    </tr>
                                    <tr>
                                        <th>TOTAL</th>
                                        <td className={(dataCuenta.monto<0?"text-danger":("text-success"))+(" justify-content-between d-flex")}>
                                            <span className="fs-3">{moneda(dataCuenta.monto)}</span>
                                            <div>
                                                {dataCuenta.aprobado==0?
                                                    <button className="btn btn-success btn-sm" onClick={()=>changeAprobarFact(dataCuenta.id, dataCuenta.proveedor.id)}>APROBAR <i className="fa fa-check"></i></button>
                                                    :
                                                    <button className="btn btn-danger btn-sm" onClick={()=>changeAprobarFact(dataCuenta.id, dataCuenta.proveedor.id)}>DESAPROBAR <i className="fa fa-times"></i></button>
                                                }
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>EMISIÓN</td>
                                        <td className="text-success">{dataCuenta.fechaemision}</td>
                                    </tr>
                                    <tr>
                                        <td>RECEPCIÓN</td>
                                        <td className="text-sinapsis">{dataCuenta.fecharecepcion}</td>
                                    </tr>
                                    <tr>
                                        <td>VENCIMIENTO</td>
                                        <td className="text-danger">{dataCuenta.fechavencimiento}</td>
                                    </tr>
                                    <tr>
                                        <td>NOTA</td>
                                        <td>{dataCuenta.nota}</td>
                                    </tr>
                                    <tr>
                                        <td>TIPO</td>
                                        <td>{dataCuenta.tipo==1?"COMPRA":"SERVICIO"}</td>
                                    </tr>
                                    <tr>
                                        <td>FRECUENCIA</td>
                                        <td>{dataCuenta.frecuencia}</td>
                                    </tr>
                                    
                                </tbody>
                            :null}

                        </table>
                                                
                        <div className="boton-fijo-inferiorizq">

                            <div className="btn-group">
                                <button className="btn btn-danger fs-3" data-numfact={dataCuenta.numfact} onClick={event=>delCuentaPorPagar(event, dataCuenta.id, dataCuenta.proveedor.id)}>
                                    <i className="fa fa-trash"></i> ELIMINAR
                                </button>
                                <button type="button" className="btn btn-sinapsis shadow fs-3" onClick={()=>modeEditarFact(dataCuenta.id)}>
                                    <i className="fa fa-pencil"></i> EDITAR
                                </button>
                            </div>
                        </div>
                    </div>
                    :null
                :<>
                    <div className="col">

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
                       


                        <table className="table table-borderless table-striped">
                                <thead className="">
                                            <tr className="align-middle">
                                                <th colSpan={8}>
                                                    <div className="btn-group">
                                                        <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="abonos"?"btn-success":"btn-outline-success")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="abonos"?"":"abonos")}>PAGOS</span>

                                                        <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="pagadas"?"btn-medsuccess":"btn-outline-medsuccess")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="pagadas"?"":"pagadas")}>PAGADAS</span>

                                                        <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="semipagadas"?"btn-primary":"btn-outline-primary")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="semipagadas"?"":"semipagadas")}>ABONADAS</span>
                                                        <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="porvencer"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="porvencer"?"":"porvencer")}>POR VENCER</span>
                                                        <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="vencidas"?"btn-danger":"btn-outline-danger")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="vencidas"?"":"vencidas")}>VENCIDAS</span>
                                                    </div>

                                                </th>
                                                <th>
                                                { 
                                                    selectCuentaPorPagarId?
                                                        selectCuentaPorPagarId.balance? 
                                                            <span className="text-muted fs-4">
                                                                Resultados <b>({selectCuentaPorPagarId.sum})</b>
                                                            </span>
                                                        :null
                                                    :null
                                                }
                                                </th>
                                                <th>
                                                    { 
                                                    selectCuentaPorPagarId?
                                                        selectCuentaPorPagarId.balance? 
                                                                <span className={(selectCuentaPorPagarId.balance<0? "text-danger": "text-success")+(" fs-1 mb-1 mt-1 bg-warning p-2")}>{moneda(selectCuentaPorPagarId.balance)}</span>
                                                            :null
                                                        :null
                                                    }
                                                </th>
                                            </tr>
                                    <tr>
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="created_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("created_at")}} className="pointer cell1 p-3">
                                            CREADA
                                        </th> 
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="updated_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("updated_at")}} className="pointer cell1 p-3">
                                            ACTUALIZADA
                                        </th> 

                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_proveedor"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_proveedor")}} className="pointer cell1 p-3">
                                            PROVEEDOR
                                        </th>  
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="numfact"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("numfact")}} className="pointer cell2 p-3 text-right">
                                            NUM
                                        </th>  
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="fechaemision"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("fechaemision")}} className="pointer cell1 p-3 text-right">
                                            EMISIÓN
                                        </th>       
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="fechavencimiento"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("fechavencimiento")}} className="pointer cell1 p-3 text-right">
                                            VENCE
                                        </th>  
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_sucursal"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_sucursal")}} className="pointer cell1 p-3 text-right">
                                            ORIGEN
                                        </th>  
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer cell1 p-3 text-right">
                                            MONTO BRUTO
                                        </th>

                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer cell1 p-3 text-right">
                                            DESCUENTO
                                        </th>

                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer cell2 p-3 text-right">
                                            MONTO NETO
                                        </th>
                                            
                                    </tr>
                                </thead> 
                            {
                                selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                                ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                                    <tbody key={e.id}>
                                        <tr className={(selectFactViewDetalles==e.id?"bg-success-light":null)+(" pointer border-top border-top-5 border-dark")} key={e.id} onClick={()=>setselectFactViewDetalles(selectFactViewDetalles==e.id?null:e.id)}>
                                            
                                                
                                                <td className="">
                                                    <small className="text-muted">{e.created_at}</small>
                                                </td> 
                                                <td className="">
                                                    <small className="text-muted">{e.updated_at}</small>
                                                </td> 

                                                <td className="">
                                                    <span className="fw-bold">{e.proveedor.descripcion}</span>
                                                </td>  
                                                <td className=" text-right">
                                                    
                                                    {/* <input type="checkbox" className="form-check-input me-1 fs-2" onMouseEnter={event=>selectFacts(event,e.id,"leave")} onChange={event=>selectFacts(event,e.id)} checked={dataselectFacts.data.filter(selefil =>selefil.id==e.id).length?
                                                        true
                                                    :false} /> */}
                                                    <span className="m-2">
                                                        {e.aprobado==0?<i className="fa-2x fa fa-clock-o text-sinapsis"></i>:<i className="fa fa-check text-success"></i>} 

                                                    </span>
                                                    <span className={
                                                        (e.condicion=="pagadas"?"btn-medsuccess":(e.condicion=="vencidas"?"btn-danger":(e.condicion=="porvencer"?"btn-sinapsis":(e.condicion=="semipagadas"?"btn-primary":(e.condicion=="abonos"?"btn-success":null)))))+(" w-75 btn fs-6 pointer")
                                                    }> 
                                                    {e.monto<0?"FACT ":"ABONO "} 
                                                    {e.numfact}
                                                    </span>
                                                </td>  
                                                <td className=" text-right">
                                                    <span className="text-success">{e.fechaemision}</span>
                                                </td>       
                                                <td className=" text-right">
                                                    <span className="text-danger ms-1">{e.fechavencimiento} <br />
                                                        <span className={(e.dias<0? "text-danger": "text-success")+(" ")}>({e.dias} días)</span>
                                                    </span>
                                                </td>  
                                                <td className=" text-right">
                                                    <span>{e.sucursal.codigo}</span>   
                                                </td>
                                                <td className=" text-right">
                                                    <span className="text-muted fs-4">{moneda(e.monto_bruto)}</span>
                                                </td>
                                                <td className=" text-right">
                                                    <span className="text-muted fst-italic">{moneda(e.monto_descuento)} ({e.descuento}%)</span>
                                                </td>

                                                <td className=" text-right">
                                                    {selectFactViewDetalles!=e.id || !e.pagos.length?
                                                    <>
                                                        <div className="mb-3">
                                                            <span className={(e.monto<0? "text-danger": "text-success")+(" fs-3 fw-bold ")}>{moneda(e.monto)}</span>
                                                        </div>
                                                        
                                                        {e.monto_abonado?
                                                            <div className="">
                                                                <span className={(e.monto_abonado<0? "text-danger": "text-success")+(" fs-7")}>ABONO</span>
                                                                <span className={(e.monto_abonado<0? "text-danger": "text-success")+(" fs-5")}> {moneda(e.monto_abonado)}</span>
                                                                <hr className="m-0"/>
                                                            </div>
                                                            : null }
                                                        {e.monto<0?
                                                            <div>
                                                                <span className={(e.balance<0? "text-danger": "text-success")+(" fs-6")}>BALANCE</span>
                                                                <span className={(e.balance<0? "text-danger": "text-success")+(" fs-4")}> {moneda(e.balance)}</span>
                                                            </div>
                                                        :null}
                                                    </>:null}
                                                </td>
                                            

                                        </tr>
                                        {selectFactViewDetalles==e.id?
                                            <>
                                                
                                                <tr className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5"}>
                                                    <th colSpan={10} className="text-center">
                                                        <div className="btn-group">
                                                            {e.condicion!="pagadas" && e.condicion!="abonos"?<button className="btn btn-outline-success" onClick={()=>abonarFact(e.id_proveedor,e.id)}>
                                                                <i className=" fa fa-credit-card"></i>
                                                                PAGAR 
                                                            </button>:null}
                                                            <button className="btn btn-outline-info" onClick={()=>showImageFact(e.descripcion)}> <i className="fa fa-eye"></i> VER </button>
                                                            <button className="btn btn-outline-sinapsis" onClick={()=>setSelectCuentaPorPagarDetalle(e.id)}> <i className="fa fa-pencil"></i> EDITAR </button>
                                                        </div>
                                                    </th>

                                                </tr>

                                                {e.pagos.length?
                                                    <>
                                                        {e.monto_abonado && e.pagos?
                                                        <>
                                                            {e.pagos.map(pago=>
                                                                    <tr key={pago.id} className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5 align-middle"}>
                                                                        <th colSpan={1}></th>
                                                                        <td className=" text-right" colSpan={3}>
                                                                            <span className="text-muted fst-italic">{pago.created_at}</span>
                                                                        </td>
                                                                        <td className="" colSpan={2}>
                                                                            PAGO REALIZADO <i className="fa fa-check text-success"></i>
                                                                        </td>
                                                                        <td className="align-middle" colSpan={3}>
                                                                            <span className="btn-success btn pointer btn-sm w-100 fs-5">
                                                                                {pago.numfact}
                                                                            </span> 
                                                                        </td>
                                                                        <td className="   text-right fs-5">
                                                                            <span className="text-sinapsis">{moneda(pago.monto)}</span> / <span className="text-success">{moneda(pago.pivot.monto)}</span>
                                                                        </td>
                                                                    </tr>
                                                                )}
                                                        </>
                                                        :null}
                                                        <tr className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5"}>
                                                            <th colSpan={5}></th>

                                                            <th className="align-middle" colSpan={3}>
                                                                ABONADO                                        
                                                            </th>
                                                            <td colSpan={2} className="text-success text-right align-middle fs-3">
                                                                {moneda(e.monto_abonado)}
                                                            </td>
                                                        </tr>
                                                        <tr className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5"}>
                                                            <th colSpan={5}></th>

                                                            <th className="align-middle" colSpan={3}>
                                                                DEUDA                                        
                                                            </th>
                                                            <td colSpan={2} className="text-danger text-right align-middle fs-3">
                                                                {moneda(e.monto)}
                                                            </td>
                                                        </tr>
                                                        <tr className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5"}>
                                                            <th colSpan={5}></th>

                                                            <th className="align-middle" colSpan={3}>
                                                                BALANCE                                        
                                                            </th>
                                                            <td colSpan={2} className={((e.balance)<0? "text-danger": "text-success")+(" fs-2 text-right align-middle bg-warning-light")}>
                                                                {e.condicion!="pagadas" && e.condicion!="abonos"?<button className="btn btn-outline-success m-2" onClick={()=>abonarFact(e.id_proveedor,e.id)}>
                                                                    <i className=" fa fa-credit-card"></i>
                                                                    PAGAR 
                                                                </button>:null}
                                                                {moneda(e.balance)}
                                                            </td>
                                                        </tr>
                                                    </> 
                                                :null}
                                                {e.facturas ?
                                                    e.facturas.length?
                                                        e.facturas.map(fact=>
                                                            <tr key={fact.id} className="border-top">
                                                                <td colSpan={4}></td>
                                                                <td className=" align-middle text-muted fst-italic">
                                                                    {fact.created_at}
                                                                </td>
                                                                <td className=" align-middle text-muted fst-italic text-right" colSpan={2}>
                                                                    FACTURA ASOCIADA <i className="fa fa-check text-sinapsis"></i>
                                                                </td>
                                                                <td className=" align-middle">
                                                                    <span className="btn-sinapsis btn pointer w-100">
                                                                        FACT {fact.numfact}
                                                                    </span> 
                                                                </td>
                                                                <td className="text-right align-middle" colSpan={2}>
                                                                    <span className="text-sinapsis">{moneda(fact.pivot.monto)}</span> / <span className="text-success">{moneda(fact.monto)}</span>
                                                                </td>
                                                            </tr>
                                                        )
                                                    :null
                                                :null}
                                                
                                            </>
                                        :null}
                                    </tbody>
                                )
                                : null : null
                            } 
                        </table>

                        
                        {
                            selectCuentaPorPagarId?selectCuentaPorPagarId.detalles?!selectCuentaPorPagarId.detalles.length
                            ? <div className="text-center">
                                <span className="text-muted fsw-italic">¡Nada para mostrar!</span>
                            </div>
                            : null : null : null
                        }
                        <div className="boton-fijo-inferiorizq">
                            <div className="container-fluid">
                                <div className="row">
                                    <div className="col">
                                        {dataselectFacts.data.map(e=>
                                            <div key={e.id} className="btn-group-vertical me-1">
                                                <span className={
                                                    (e.condicion=="pagadas"?"btn-medsuccess":(e.condicion=="vencidas"?"btn-danger":(e.condicion=="porvencer"?"btn-sinapsis":(e.condicion=="semipagadas"?"btn-primary":(e.condicion=="abonos"?"btn-success":null)))))+(" w-100 btn fs-6 pointer")
                                                }>{e.numfact}</span>
                                                <button className="btn bg-danger-light">{moneda(e.monto)}</button>
                                            </div>
                                        )}
                                        {dataselectFacts.sum?
                                            <button className="btn btn-warning fs-2">{moneda(dataselectFacts.sum)}</button> 
                                        :null}
                                        {dataselectFacts.sum?
                                            <div className="input-group mt-1">
                                                <div className="input-group-text">
                                                    DESCUENTO
                                                </div>
                                                <input type="text" placeholder="%" className="form-control" value={descuentoGeneralFats} onChange={event=>setdescuentoGeneralFats(event.target.value)} />
                                                <button className="btn btn-secondary" type="button" onClick={sendDescuentoGeneralFats}><i className="fa fa-send"></i></button>
                                            </div>
                                        :null}
                                    </div>
                                </div>
                            </div>

                            <button className="btn btn-sinapsis fs-3" type="button" 
                                onClick={()=>{
                                        setcuentasporpagarDetallesView("pagos");
                                        setInputsNewFact()
                                    }
                                }
                            >
                                <i className="fa fa-plus"></i> AGREGAR
                            </button>
                        </div>

                        
                    </div>
                </>}  
            </div>
        </div>
    )
}