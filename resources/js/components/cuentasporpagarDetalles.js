import { useEffect } from "react";
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
    showImageFact,
    cuentaporpagarAprobado,
    setcuentaporpagarAprobado,
    changeAprobarFact,

}){
    
    let proveedorMain = ""
    let id_proveedor = selectProveedorCxp

    if (selectCuentaPorPagarId) {
        if (selectCuentaPorPagarId.detalles) {
            if (selectCuentaPorPagarId.detalles[0]) {
                if (selectCuentaPorPagarId.detalles[0].proveedor) {
                    proveedorMain = selectCuentaPorPagarId.detalles[0].proveedor.descripcion
                }
            }
        }
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
    const setInputsNewFact = () => {
        setselectFactEdit(null)
        setcuentasPagosDescripcion("")
        setcuentasPagosMonto("")
    }

    useEffect(()=>{
        selectCuentaPorPagarProveedorDetallesFun(id_proveedor)
    },[
        categoriacuentasPorPagarDetalles,
        tipocuentasPorPagarDetalles,
        qcuentasPorPagarTipoFact,
        
        qCampocuentasPorPagarDetalles,
        qcuentasPorPagarDetalles,
        OrdercuentasPorPagarDetalles,
        cuentaporpagarAprobado,
    ])
    return (
        <div className="container p-0 mb-4">
            {
            SelectCuentaPorPagarDetalle?
                dataCuenta?
                <>
                    <div className="mb-2">
                        <span className="btn btn-danger boton-fijo-inferiorder btn-sm" onClick={()=>setSelectCuentaPorPagarDetalle(null)}>
                            <i className="fa fa-arrow-left"></i>
                        </span>
                    </div>
                    <table className="table table-borderless table-sm">
                        <tbody>
                            {dataCuenta.monto_abonado && dataCuenta.pagos?
                            <>
                                <tr className="">
                                    <th>
                                        ABONADO                                        
                                    </th>
                                    <td colSpan={2} className="text-success text-right">
                                        {moneda(dataCuenta.monto_abonado)}
                                    </td>
                                </tr>
                                <tr>
                                    <td colSpan={2}>
                                        <table className="table table-borderless table-sm">
                                            <tbody>
                                                {dataCuenta.monto_abonado && dataCuenta.pagos?
                                                <>
                                                    {dataCuenta.pagos.map(e=>
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
                                    <th>
                                        DEUDA                                        
                                    </th>
                                    <td colSpan={2} className="text-danger text-right">
                                        {moneda(dataCuenta.monto)}
                                    </td>
                                </tr>
                                <tr className="">
                                    <th>
                                        BALANCE                                        
                                    </th>
                                    <td colSpan={2} className={((dataCuenta.balance)<0? "text-danger": "text-success")+(" fs-4 text-right")}>
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
                    <hr />
                    
                   
                    <table className="table">
                        {dataCuenta.sucursal?
                            <tbody>
                                <tr>
                                    <td colSpan={2} className="text-center"> <small className="text-muted">{dataCuenta.sucursal.codigo}</small></td>
                                </tr>
                                <tr>
                                    <td>PROVEEDOR</td>
                                    <td>{dataCuenta.proveedor.descripcion} <small className="text-muted">RIF. {dataCuenta.proveedor.rif}</small></td>
                                </tr>
                                <tr>
                                    <td># FACTURA</td>
                                    <td>{dataCuenta.numfact}</td>
                                </tr>
                                <tr>
                                    <td># CONTROL</td>
                                    <td>{dataCuenta.numnota}</td>
                                </tr>
                                <tr>
                                    <td>IMAGEN</td>
                                    <td><button className="btn btn-sinapsis btn-sm" onClick={()=>showImageFact(dataCuenta.descripcion)}>VER</button></td>
                                </tr>
                                <tr>
                                    <th>SUBTOTAL</th>
                                    <td className={dataCuenta.subtotal<0?"text-danger":("text-success")}>{moneda(dataCuenta.subtotal)}</td>
                                </tr>
                                <tr>
                                    <th>DESCUENTO</th>
                                    <td className={dataCuenta.descuento<0?"text-danger":("text-success")}>{moneda(dataCuenta.descuento)}</td>
                                </tr>
                                <tr>
                                    <th>MONTO EXENTO</th>
                                    <td className={dataCuenta.monto_exento<0?"text-danger":("text-success")}>{moneda(dataCuenta.monto_exento)}</td>
                                </tr>
                                <tr>
                                    <th>MONTO GRAVABLE</th>
                                    <td className={dataCuenta.monto_gravable<0?"text-danger":("text-success")}>{moneda(dataCuenta.monto_gravable)}</td>
                                </tr>
                                <tr>
                                    <th>IVA</th>
                                    <td className={dataCuenta.iva<0?"text-danger":("text-success")}>{moneda(dataCuenta.iva)}</td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <td className={(dataCuenta.monto<0?"text-danger":("text-success"))+(" justify-content-between")}>
                                        {moneda(dataCuenta.monto)}
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
                                <tr>
                                    <td colSpan={2}>
                                        <button type="button" className="btn btn-sinapsis boton-fijo-inferiorizq" onClick={()=>modeEditarFact(dataCuenta.id)}>
                                            <i className="fa fa-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        :null}

                    </table>
                </>
                :null
            :<>
                
                <form onSubmit={e=>{e.preventDefault();selectCuentaPorPagarProveedorDetallesFun(id_proveedor)}} className="mb-2">
                    <div className="btn-group w-100 mb-2">
                        <span className={("btn btn-sm ")+(cuentaporpagarAprobado=="0"?"btn-danger":"btn-outline-danger")} onClick={()=>setcuentaporpagarAprobado(cuentaporpagarAprobado=="0"?"":"0")}>PENDIENTES</span>

                        <span className={("btn btn-sm ")+(cuentaporpagarAprobado=="1"?"btn-success":"btn-outline-success")} onClick={()=>setcuentaporpagarAprobado(cuentaporpagarAprobado=="1"?"":"1")}>APROBADAS</span>
                    </div>
                    <div className="btn-group w-100 mb-2">
                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="abonos"?"btn-success":"btn-outline-success")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="abonos"?"":"abonos")}>DÉB</span>

                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="pagadas"?"btn-medsuccess":"btn-outline-medsuccess")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="pagadas"?"":"pagadas")}>PAG</span>

                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="semipagadas"?"btn-primary":"btn-outline-primary")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="semipagadas"?"":"semipagadas")}>ABON</span>
                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="porvencer"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="porvencer"?"":"porvencer")}>POR VENCER</span>
                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="vencidas"?"btn-danger":"btn-outline-danger")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="vencidas"?"":"vencidas")}>VENC</span>
                    </div>
                    <div className="input-group">
                        
                        <input type={
                            qCampocuentasPorPagarDetalles=="created_at" || 
                            qCampocuentasPorPagarDetalles=="fechaemision" || 
                            qCampocuentasPorPagarDetalles=="fecharecepcion" || 
                            qCampocuentasPorPagarDetalles=="fechavencimiento" ? "date": "text" 
                        } className="form-control form-control-sm" placeholder={"Buscar en "+proveedorMain+" por "+qCampocuentasPorPagarDetalles} onChange={e=>setqcuentasPorPagarDetalles(e.target.value)} value={qcuentasPorPagarDetalles} />
                        
                        <span className={("btn btn-sm arabito_")+(OrdercuentasPorPagarDetalles)} 
                        onClick={()=>setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")}>
                            {(<i className={OrdercuentasPorPagarDetalles == "desc" ? "fa fa-arrow-up" : "fa fa-arrow-down"}></i>)}
                        </span>
                        
                        <select className="form-control form-control-sm" value={qCampocuentasPorPagarDetalles} onChange={e=>setqCampocuentasPorPagarDetalles(e.target.value)}>
                            <option value="">-Buscar en-</option>
                            
                            <option value="created_at">Creación</option>
                            <option value="fechaemision">Emisión</option>
                            <option value="fecharecepcion">Recepción</option>
                            <option value="fechavencimiento">Vencimiento</option>


                            <option value="numfact"># Fact</option>
                            <option value="numnota"># Nota</option>
                            <option value="descripcion">Descripción</option>
                            <option value="subtotal">Subtotal</option>
                            <option value="descuento">Descuento</option>
                            <option value="monto_exento">Monto exento</option>
                            <option value="monto_gravable">Monto gravable</option>
                            <option value="iva">IVA</option>
                            <option value="monto">TOTAL</option>
                        </select>
                    </div>


                    <div className="input-group">
                        <select className="form-control form-control-sm" value={categoriacuentasPorPagarDetalles} onChange={e=>setcategoriacuentasPorPagarDetalles(e.target.value)}>
                            <option value="">-CATEGORÍA-</option>
                            <option value="1">COMPRAS</option>
                            <option value="2">SERVICIOS</option>
                        </select>

                        <select className="form-control form-control-sm" value={tipocuentasPorPagarDetalles} onChange={e=>settipocuentasPorPagarDetalles(e.target.value)}>
                            <option value="">-TIPO-</option>
                            <option value="DEUDA">CRÉDITOS</option>
                            <option value="ABONOS">DÉBITOS</option>
                        </select>
                        <button type="button" className="btn btn-success" onClick={()=>selectCuentaPorPagarProveedorDetallesFun(id_proveedor)}><i className="fa fa-search"></i></button>
                    </div>                


                </form>

                { 
                    selectCuentaPorPagarId?
                        selectCuentaPorPagarId.balance? 
                        <div className="d-flex justify-content-between align-items-center">
                            <small className="text-muted">
                                {proveedorMain} <b>({selectCuentaPorPagarId.sum})</b>
                            </small>
                            <span className={(selectCuentaPorPagarId.balance<0? "text-danger": "text-success")+(" fs-2 mb-1 mt-1")}>{moneda(selectCuentaPorPagarId.balance)}</span>
                        </div>
                        :null
                    :null
                }
                {
                    selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                    ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                        <div className={("text-secondary mb-3 pointer shadow p-2 card ")+(e.aprobado==0?"bg-danger-light":"")} key={e.id}>
                            <div className="d-flex justify-content-between fs-7">
                                <span className="fw-bold">{e.proveedor.descripcion}</span>
                                <small className="text-muted">{e.created_at}</small>
                                <span>{e.sucursal.codigo}</span>
                            </div>
                            <div>
                                <div onClick={()=>setSelectCuentaPorPagarDetalle(e.id)} className="">
                                    <span className={
                                        (e.condicion=="pagadas"?"btn-medsuccess":(e.condicion=="vencidas"?"btn-danger":(e.condicion=="porvencer"?"btn-sinapsis":(e.condicion=="semipagadas"?"btn-primary":(e.condicion=="abonos"?"btn-success":null)))))+(" w-100 btn fs-3 pointer")
                                    }>{e.numfact}</span>
                                </div>
                            </div>
                            <div className="d-flex justify-content-between align-items-center">
                                {e.monto_abonado?
                                     <span className={("text-muted fs-6 fw-italic")}>ABONO {moneda(e.monto_abonado)}</span>
                                : <span></span> }
                                <span className={(e.monto<0? "text-danger": "text-success")+(" fs-2 fw-bold")}>{moneda(e.monto)}</span>
                            </div>
                            <div className="d-flex justify-content-between fst-italic fs-7">
                                
                                <span className="text-success">{e.fechaemision}</span>
                                <span className="text-sinapsis ms-1">{e.fecharecepcion}</span>
                                <span className="text-danger ms-1">{e.fechavencimiento}</span>
                            </div>
                        </div>
                    )
                    : null : null
                    
                } 
                {
                    selectCuentaPorPagarId.detalles?!selectCuentaPorPagarId.detalles.length
                    ? <div className="text-center">
                        <span className="text-muted fsw-italic">¡Nada para mostrar!</span>
                    </div>
                    : null : null
                }
                <button className="btn boton-fijo-inferiorder btn-danger btn-sm" onClick={()=>setSelectCuentaPorPagarId(null)}>
                    <i className="fa fa-arrow-left"></i>
                </button>
                <button className="btn boton-fijo-inferiorizq btn-sinapsis" type="button" 
                    onClick={()=>{
                            setcuentasporpagarDetallesView("pagos");
                            setInputsNewFact()
                        }
                    }
                >
                    <i className="fa fa-plus"></i>
                </button>
            </>}          
        </div>
    )
}