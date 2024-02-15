import { useEffect } from "react";
export default function CuentasporpagarPagos({
    getMetodosPago,
    cuentasporpagarDetallesView,
    setcuentasporpagarDetallesView,
    cuentasPagosDescripcion,
    setcuentasPagosDescripcion,
    cuentasPagosMonto,
    setcuentasPagosMonto,
    cuentasPagosMetodo,
    setcuentasPagosMetodo,
    cuentasPagosFecha,
    setcuentasPagosFecha, 
    opcionesMetodosPago,
    number,
    sendPagoCuentaPorPagar,

    selectCuentaPorPagarProveedorDetallesFun,
    setqCampocuentasPorPagarDetalles,
    setsucursalcuentasPorPagarDetalles,
    OrdercuentasPorPagarDetalles,
    setOrdercuentasPorPagarDetalles,
    qCampocuentasPorPagarDetalles,
    qFechaCampocuentasPorPagarDetalles,
    setqFechaCampocuentasPorPagarDetalles,
    OrderFechacuentasPorPagarDetalles,
    setOrderFechacuentasPorPagarDetalles,
    fechacuentasPorPagarDetalles,
    setfechacuentasPorPagarDetalles,
    categoriacuentasPorPagarDetalles,
    setcategoriacuentasPorPagarDetalles,
    tipocuentasPorPagarDetalles,
    settipocuentasPorPagarDetalles,
    selectCuentaPorPagarId,

    setqcuentasPorPagarDetalles,
    qcuentasPorPagarDetalles,
    selectFacturaSetPago,
    selectFactPagoArr,
    setselectFactPagoArr,
    moneda,
    setInputAbonoFact,
    selectAbonoFact,
    setselectAbonoFact,

    setsubviewAgregarFactPago,
    subviewAgregarFactPago,

    setnewfactid_proveedor,
    newfactid_proveedor,
    setnewfactnumfact,
    newfactnumfact,
    setnewfactnumnota,
    newfactnumnota,
    setnewfactdescripcion,
    newfactdescripcion,
    setnewfactsubtotal,
    newfactsubtotal,
    setnewfactdescuento,
    newfactdescuento,
    setnewfactmonto_exento,
    newfactmonto_exento,
    setnewfactmonto_gravable,
    newfactmonto_gravable,
    setnewfactiva,
    newfactiva,
    setnewfactmonto,
    newfactmonto,
    setnewfactfechaemision,
    newfactfechaemision,
    setnewfactfechavencimiento,
    newfactfechavencimiento,
    setnewfactfecharecepcion,
    newfactfecharecepcion,
    setnewfactnota,
    newfactnota,
    setnewfacttipo,
    newfacttipo,
    setnewfactfrecuencia,
    newfactfrecuencia,

    setselectFactEdit,
    selectFactEdit,
    saveNewFact,
    proveedoresList,
    getProveedores,
    selectFactPagoid,
    setqcuentasPorPagarTipoFact ,
    delItemSelectAbonoFact

    
}){

    useEffect(()=>{
        setqcuentasPorPagarDetalles("")
        setqCampocuentasPorPagarDetalles("numfact")
        setcategoriacuentasPorPagarDetalles("")
        settipocuentasPorPagarDetalles("")
        setqcuentasPorPagarTipoFact("")
        setsucursalcuentasPorPagarDetalles("")
        getMetodosPago()
    },[]) 
    let id_proveedor = null

    if (selectCuentaPorPagarId) {
        if (selectCuentaPorPagarId.detalles) {
            if (selectCuentaPorPagarId.detalles[0]) {
                if (selectCuentaPorPagarId.detalles[0].proveedor) {
                    id_proveedor = selectCuentaPorPagarId.detalles[0].proveedor.id
                }
            }
        }
    }

    let sumSelectAboFact = selectAbonoFact.map(e=>e.val==""?0:parseFloat(e.val)).reduce((partial_sum, a) => partial_sum + a, 0)
    let restaAbono = sumSelectAboFact-parseFloat(cuentasPagosMonto)

    
    return (
        <div className="container mb-6 p-0">
            <div className="btn-group mb-1 mt-1 w-100">
                <span className={("btn ")+(subviewAgregarFactPago=="pago"?"btn-sinapsis":"")} onClick={()=>setsubviewAgregarFactPago("pago")}>
                    Agregar Pago
                </span>

                <span className={("btn ")+(subviewAgregarFactPago=="factura"?"btn-sinapsis":"")} onClick={()=>setsubviewAgregarFactPago("factura")}>
                    Agregar Factura
                </span>
            </div>

            {subviewAgregarFactPago=="pago"?
               <>
                    <form onSubmit={sendPagoCuentaPorPagar}>
                        <div className="boton-fijo-inferiorizq">
                            <div className="form-group">
                                {selectFactPagoid==null?
                                    <button className="btn btn-success fs-3" type="submit">GUARDAR <i className="fa fa-save"></i></button>
                                    :
                                    <button className="btn btn-sinapsis fs-3" type="submit">EDITAR <i className="fa fa-pencil"></i></button>
                                }
                            </div>
                        </div>

                        <div className="form-group">
                            <div className="input-group">
                                <span className="input-group-text cell3">Descripción</span>
                                <input type="text" className="form-control" placeholder="Referencia" value={cuentasPagosDescripcion} onChange={e=>setcuentasPagosDescripcion(e.target.value)} />
                            </div>
                        </div>

                        <div className="form-group">
                            <div className="input-group">
                                <span className="input-group-text cell3">Monto</span>
                                <input type="text" className="form-control" placeholder="Monto TOTAL de ABONO" value={cuentasPagosMonto} onChange={e=>setcuentasPagosMonto(number(e.target.value))} />
                            </div>
                        </div>

                        <div className="input-group">
                            <select className="form-control" 
                            value={cuentasPagosMetodo} 
                            
                            onChange={e=>setcuentasPagosMetodo(e.target.value)}>
                                <option value="">-Método-</option>
                                {opcionesMetodosPago.map(e=>
                                    <option value={e.codigo} key={e.codigo}>{e.descripcion}</option>
                                )}
                            </select>
                            <input type="date" className="form-control" value={cuentasPagosFecha} onChange={e=>setcuentasPagosFecha(e.target.value)} />
                            
                        </div>
                        <table className="table table-sm">
                            <tbody>
                                <tr>
                                    <th className="text-center">FACTURAS ASOCIADAS</th>
                                    <th className="text-center">ABONADO</th>
                                </tr>
                                    {selectAbonoFact.map(e=>
                                        <tr key={e.id}>
                                            <td className="text-center align-middle">
                                                <div className="btn-group w-100">
                                                    <button className="btn-danger btn" onClick={()=>delItemSelectAbonoFact(e.id)} type="button">
                                                        <i className="fa fa-times"></i>
                                                    </button>
                                                    <button className="btn-sinapsis w-100 btn pointer btn-sm" type="button">
                                                        {e.numfact}
                                                    </button> 

                                                </div>
                                            </td>
                                            <td className="text-center align-middle">
                                                <span className="text-sinapsis">{moneda(e.val)}</span> / <span className="text-success">{moneda(e.valfact)}</span> 
                                            </td>
                                        </tr>
                                    )}

                                    <tr>
                                        <th className="text-center align-middle">
                                            SUM
                                        </th>
                                        <th className="text-center text-sinapsis     align-middle">{moneda(sumSelectAboFact)}</th>
                                    </tr>
                                    <tr>
                                        <th className="text-center align-middle">
                                            RESTA
                                        </th>
                                        <th className={(restaAbono<0? "text-danger": "text-sinapsis ")+(" text-center align-middle")}>{moneda(restaAbono)}</th>
                                    </tr>

                            </tbody>
                        </table>
                    </form>

                    <form onSubmit={event=>{
                        selectCuentaPorPagarProveedorDetallesFun(id_proveedor)
                        event.preventDefault()
                    }}>
                        <table className="table mt-2 table-sm">
                            <thead>
                                <tr>
                                    <th colSpan={3} className="fs-5">ESPECIFICAR ABONO</th>
                                </tr>
                                <tr>
                                    <th colSpan={3}>
                                        <div className="input-group">
                                        <input type={
                                            qCampocuentasPorPagarDetalles=="created_at" || 
                                            qCampocuentasPorPagarDetalles=="fechaemision" || 
                                            qCampocuentasPorPagarDetalles=="fecharecepcion" || 
                                            qCampocuentasPorPagarDetalles=="fechavencimiento" ? "date": "text" 
                                        } className="form-control form-control-sm" placeholder={"Buscar por "+qCampocuentasPorPagarDetalles} onChange={e=>setqcuentasPorPagarDetalles(e.target.value)} value={qcuentasPorPagarDetalles} />
                                            
                                            
                                            
                                            <button type="button" className="btn btn-success" onClick={()=>selectCuentaPorPagarProveedorDetallesFun(id_proveedor)}><i className="fa fa-search"></i></button>
                                        </div>

                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                            {
                                selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                                ? selectCuentaPorPagarId.detalles.filter(e=>e.monto<0&&e.condicion!="pagadas").map( (e,i) =>
                                
                                    <tr key={e.id}>
                                        <td className="align-middle">
                                            <span className={
                                                (e.condicion=="pagadas"?"btn-success":(e.condicion=="vencidas"?"btn-danger":(e.condicion=="porvencer"?"btn-sinapsis":(e.condicion=="semipagadas"?"btn-primary":null))))+(" w-100 btn pointer btn-sm")
                                            } onDoubleClick={()=>setInputAbonoFact(e.id,(e.balance*-1))}>{e.numfact}</span>
                                        </td>
                                        <td className="align-middle cell3">
                                            <input type="text" className="form-control form-control-sm" onChange={event=>{
                                                let val = parseFloat(event.currentTarget.value)>(e.balance*-1)?"":event.currentTarget.value
                                                setInputAbonoFact(e.id, val)
                                                event.target.value = val
                                            }} placeholder={e.numfact} />
                                        </td>
                                        <td className="align-middle text-right">
                                            <div><span className={(e.balance<0? "text-danger": "text-success")+(" ")}>BALANCE {moneda(e.balance)}</span></div>
                                            <div><span className={(e.monto_abonado<0? "text-danger": "text-success")+(" fs-7")}>ABONO {moneda(e.monto_abonado)}</span></div>
                                            <div><span className={(e.monto<0? "text-danger": "text-success")+(" fs-7")}>DEUDA {moneda(e.monto)}</span></div>
                                        </td>
                                    </tr>
                                )
                                : null : null
                                
                            } 
                            </tbody>

                        </table>
                    </form>
               </>  
            :null}

            {subviewAgregarFactPago=="factura"?
                <>
                    <form onSubmit={saveNewFact}>
                        
                        {/* <div className="form-group">
                            <span>PROVEEDOR</span>
                            
                            <select className="form-control" value={newfactid_proveedor} onChange={e=>setnewfactid_proveedor(e.target.value)}>
                                <option value="">-</option>
                                {proveedoresList.map(e=>
                                    <option key={e.id} value={e.id}>{e.rif}-{e.descripcion}</option>
                                )}
                            </select>
                            
                        </div> */}
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4"># FACTURA</span>
                            <input type="text" placeholder="numfact" value={newfactnumfact} onChange={e=>setnewfactnumfact(e.target.value)} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4"># CONTROL</span>
                            <input type="text" placeholder="numnota" value={newfactnumnota} onChange={e=>setnewfactnumnota(e.target.value)} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">DESCRIPCIÓN</span>
                            <input type="text" placeholder="descripcion" value={newfactdescripcion} onChange={e=>setnewfactdescripcion(e.target.value)} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">SUBTOTAL</span>
                            <input type="text" placeholder="subtotal" value={newfactsubtotal} onChange={e=>setnewfactsubtotal(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">DESCUENTO</span>
                            <input type="text" placeholder="descuento" value={newfactdescuento} onChange={e=>setnewfactdescuento(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">MONTO EXENTO</span>
                            <input type="text" placeholder="monto_exento" value={newfactmonto_exento} onChange={e=>setnewfactmonto_exento(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">MONTO GRAVABLE</span>
                            <input type="text" placeholder="monto_gravable" value={newfactmonto_gravable} onChange={e=>setnewfactmonto_gravable(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">IVA</span>
                            <input type="text" placeholder="iva" value={newfactiva} onChange={e=>setnewfactiva(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <div className="input-group-text text-right cell4">
                                <b className="fs-5 text-success">TOTAL</b>
                            </div>
                            <input type="text" placeholder="monto" value={newfactmonto} onChange={e=>setnewfactmonto(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">EMISIÓN</span>
                            <input type="date" placeholder="fechaemision" value={newfactfechaemision} onChange={e=>setnewfactfechaemision(e.target.value)} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">VENCIMIENTO</span>
                            <input type="date" placeholder="fechavencimiento" value={newfactfechavencimiento} onChange={e=>setnewfactfechavencimiento(e.target.value)} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">RECEPCIÓN</span>
                            <input type="date" placeholder="fecharecepcion" value={newfactfecharecepcion} onChange={e=>setnewfactfecharecepcion(e.target.value)} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">NOTA</span>
                            <input type="text" placeholder="nota" value={newfactnota} onChange={e=>setnewfactnota(e.target.value)} className="form-control" />
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">TIPO</span>
                            
                            <select className="form-control" value={newfacttipo} onChange={e=>setnewfacttipo(e.target.value)}>
                                <option value="">-</option>
                                <option value="1">COMPRAS</option>
                                <option value="2">SERVICIOS</option>
                            </select>
                            
                        </div>
                        <div className="input-group m-1">
                            <span className="input-group-text text-right cell4">FRECUENCIA</span>
                            <input type="text" placeholder="frecuencia" value={newfactfrecuencia} onChange={e=>setnewfactfrecuencia(e.target.value)} className="form-control" />
                        </div>

                        <div className="boton-fijo-inferiorizq">
                                <div className="btn-group">
        
                                    {selectFactEdit!==null?
                                    <button className="btn btn-sinapsis fs-3" type="submit">EDITAR <i className="fa fa-pencil"></i></button>

                                    :
                                    <button className="btn btn-success fs-3" type="submit">GUARDAR <i className="fa fa-save"></i></button>

                                }
                                </div>
                        </div>
                    </form>
                </>                        
            :null}


            <button className="btn div-fijo-inferiorder  btn-sinapsis" onClick={()=>setcuentasporpagarDetallesView("cuentas")} type="button">
                <i className="fa fa-list"></i>
            </button>
        </div>
    )
}