import { useEffect, useState } from "react";
import  SearchBarFacturas  from "./searchBarFacturas";
import  Trmaincuentasporpagar  from "./trmaincuentasporpagar";


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
    setsubviewAgregarFactPago,
    abonarFactLote,
    changeSucursal,
    returnCondicion,
    colorSucursal,
    dateFormat,
    setdataselectFacts,

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
    const [viewAvanzatedShow, setviewAvanzatedShow] = useState(false)


    return (
        <>
            {
            SelectCuentaPorPagarDetalle?
                dataCuenta?
                <div className="container p-0 mb-6">
                    <span className="btn btn-danger boton-fijo-inferiorder fs-3" onClick={()=>setSelectCuentaPorPagarDetalle(null)}>
                        <i className="fa fa-arrow-left"></i> VOLVER
                    </span>

                    <table className="table table-sm" onDoubleClick={()=>modeEditarFact(dataCuenta.id)}>
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
                                    <th>SUBTOTAL / BRUTO</th>
                                    <td className={dataCuenta.subtotal<0?"text-danger":("text-success")}>
                                        {moneda(dataCuenta.subtotal)} / {moneda(dataCuenta.monto_bruto)} 
                                    </td>
                                </tr>
                                <tr>
                                    <th>DESCUENTO %</th>
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
                                        <table className="table table-striped w-75">
                                            <tbody>
                                                {dataCuenta.monto_abonado && dataCuenta.pagos?
                                                <>
                                                    {dataCuenta.pagos.map(e=>
                                                        <tr key={e.id} className="border-top">
                                                            <td className=" align-middle">
                                                                <span className="text-muted fst-italic">{e.created_at}</span>
                                                            </td>
                                                            <td>
                                                                <span className="btn-success btn pointer w-100">
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
                                            <th colSpan={3} className="text-center">
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
                                                                        <span className="btn-sinapsis btn pointer fs-4 w-100">
                                                                            {e.monto<=0?"FACT ":"ABONO "} 
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
                <div className="container-fluid p-0 mb-6">

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
                                    <th colSpan={3} className="text-right">
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
                                    <th colSpan={2} className="text-right">
                                        { 
                                        selectCuentaPorPagarId?
                                            selectCuentaPorPagarId.balance!=""? 
                                                    <span className={(selectCuentaPorPagarId.balance<0? "text-danger": "text-success")+(" fs-1 mb-1 mt-1 bg-warning p-2")}>{moneda(selectCuentaPorPagarId.balance)}</span>
                                                :null
                                            :null
                                        }
                                    </th>
                                </tr>
                                <tr>
                                    {viewAvanzatedShow?<>
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="created_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("created_at")}} className="pointer  p-3">
                                            CREADA
                                        </th> 
                                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="updated_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("updated_at")}} className="pointer  p-3">
                                            ACTUALIZADA
                                        </th> 
                                    </>:null}
                                    <th>ID</th>

                                    <th className="p-3 text-right">
                                        <i className="fa fa-eye text-success pointer me-1" onClick={()=>setviewAvanzatedShow(!viewAvanzatedShow)}></i>

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
                                    <th className="text-right p-3">
                                        <span className={("fs-7")}>ABONO</span>
                                    </th>
                                    <th className="text-right p-3">
                                        <span className={("fs-6")}>BALANCE</span>
                                        
                                    </th>
                                        
                                </tr>
                            </thead> 
                            {
                                selectCuentaPorPagarId.fasts_no?
                                    selectCuentaPorPagarId.fasts_no.map((fact_no,ii)=>{
                                        let fil = selectCuentaPorPagarId.detalles.filter(ee=>ee.id==fact_no["id"])
                                        let e = []
                                        if (fil.length) {
                                            e = fil[0]
                                        }
                                        return <tbody key={ii}>
                                            {  !fact_no["show"]?
                                                    <tr className="border-top border-top-5 border-dark">
                                                        <td colSpan={5} className="">
                                                        </td>       
                                                        
                                                        <td className="text-right">
                                                            <span className={(" w-100 btn fs-2 pointer fw-bolder text-light btn-secondary ")}> 
                                                                *{fact_no["numfact"]}* NO APARECE
                                                            </span>
                                                        </td>  
                                                        <td colSpan={6} className=" text-right">
                                                            
                                                        </td>
                                                    </tr>
                                                :
                                                
                                                <>
                                                    <Trmaincuentasporpagar
                                                        e={e}
                                                        i={ii}
                                                        selectFactViewDetalles={selectFactViewDetalles}
                                                        dateFormat={dateFormat}
                                                        colorSucursal={colorSucursal}
                                                        moneda={moneda}
                                                        abonarFact={abonarFact}
                                                        showImageFact={showImageFact}
                                                        setSelectCuentaPorPagarDetalle={setSelectCuentaPorPagarDetalle}
                                                        changeSucursal={changeSucursal}
                                                        viewAvanzatedShow={viewAvanzatedShow}
                                                        returnCondicion={returnCondicion}
                                                        dataselectFacts={dataselectFacts}
                                                        setselectFactViewDetalles={setselectFactViewDetalles}
                                                        selectFacts={selectFacts}
                                                    />
                                                </>
                                            }
                                        </tbody>
                                    })
                                : null 
                            }  
                        {
                            selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                            ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                                <tbody key={e.id}>

                                    {!selectCuentaPorPagarId.fasts_no.length?
                                        <Trmaincuentasporpagar
                                            e={e}
                                            i={i}
                                            selectFactViewDetalles={selectFactViewDetalles}
                                            dateFormat={dateFormat}
                                            colorSucursal={colorSucursal}
                                            moneda={moneda}
                                            abonarFact={abonarFact}
                                            showImageFact={showImageFact}
                                            setSelectCuentaPorPagarDetalle={setSelectCuentaPorPagarDetalle}
                                            changeSucursal={changeSucursal}
                                            viewAvanzatedShow={viewAvanzatedShow}
                                            returnCondicion={returnCondicion}
                                            dataselectFacts={dataselectFacts}
                                            setselectFactViewDetalles={setselectFactViewDetalles}
                                            selectFacts={selectFacts}
                                        />
                                        
                                    :null}

                                </tbody>
                            )
                            : null : null
                        } 
                        
                    </table>

                    <div className="boton-fijo-inferiorizq">
                        <div className={("container-fluid shadow card ")+(dataselectFacts.data.length?"p-3":"")}>
                            <div className="row">
                                <div className="col">
                                    {/* {dataselectFacts.data.map(e=>
                                        <div key={e.id} className="btn-group-vertical me-1">
                                            <span onClick={event=>selectFacts(event,e.id)} className={
                                                (e.condicion=="pagadas"?"btn-medsuccess":(e.condicion=="vencidas"?"btn-danger":(e.condicion=="porvencer"?"btn-sinapsis":(e.condicion=="semipagadas"?"btn-primary":(e.condicion=="abonos"?"btn-success":null)))))+(" w-100 btn fs-6 pointer fw-bolder text-light")
                                            }>{e.numfact}</span>
                                            <button className="btn btn-warning">{moneda(e.balance)}</button>
                                        </div>
                                    )} */}
                                    {dataselectFacts.sum?
                                        <button className="btn btn-warning fs-2" onClick={()=>setdataselectFacts({
                                            "sum": 0,
                                            "data": []
                                          })}>{moneda(dataselectFacts.sum)}</button> 
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

                        {dataselectFacts.data.length?
                            <button className="btn btn-sinapsis fs-3" onClick={()=>abonarFactLote()}>
                                <i className=" fa fa-credit-card me-1"></i>
                                PAGAR
                            </button>
                        :<button className="btn btn-sinapsis fs-3" type="button" 
                            onClick={()=>{
                                    setcuentasporpagarDetallesView("pagos");
                                    setsubviewAgregarFactPago("pago")

                                    setInputsNewFact()
                                }
                            }
                        >
                            <i className="fa fa-plus"></i> AGREGAR
                        </button>
                        }
                    </div>
                </div>
            </>}  
        </>
    )
}