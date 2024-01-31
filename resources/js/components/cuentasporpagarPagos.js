import { useEffect } from "react";
export default function CuentasporpagarPagos({
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

    
}){

    /* useEffect(()=>{
        getProveedores()
    },[]) */
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
    
    return (
        <div className="container mb-4 p-0">
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
                        <h4 className="text-center"></h4>
                        <div className="form-group">
                            <span className="input-text">Descripción</span>
                            <input type="text" className="form-control" placeholder="Referencia" value={cuentasPagosDescripcion} onChange={e=>setcuentasPagosDescripcion(e.target.value)} />
                        </div>

                        <div className="form-group">
                            <span className="input-text">Monto</span>
                            <input type="text" className="form-control" placeholder="Monto TOTAL de ABONO" value={cuentasPagosMonto} onChange={e=>setcuentasPagosMonto(number(e.target.value))} />
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
                            <input type="text" className="form-control" value={cuentasPagosFecha} onChange={e=>setcuentasPagosFecha(e.target.value)} />
                            
                        </div>
                        <table className="table table-sm">
                            <tbody>
                                <tr>
                                    <th className="text-center">FACT</th>
                                    <th className="text-center">ABONADO</th>
                                </tr>
                                    {selectAbonoFact.map(e=>
                                        <tr key={e.id}>
                                            <td className="text-center align-center">
                                                <span className="btn-sinapsis w-100 btn pointer">
                                                    {e.numfact}
                                                </span> 
                                            </td>
                                            <td className="text-center text-success align-center">{moneda(e.val)}</td>
                                        </tr>
                                    )}
                            </tbody>
                        </table>
                        <div className="form-group w-100">
                        <button className="mt-2 btn btn-outline-success btn-block w-100 btn-sm" type="submit">Guardar</button>
                        </div>
                    </form>
                    <table className="table mt-2">
                        <thead>
                            <tr>
                                <th colSpan={3}><h6>ESPECIFICAR ABONO</h6></th>
                            </tr>
                            <tr>
                                <th colSpan={3}>
                                    <form onSubmit={selectCuentaPorPagarProveedorDetallesFun} className="mb-2">
                                        <div className="input-group">
                                        <input type={
                                            qCampocuentasPorPagarDetalles=="created_at" || 
                                            qCampocuentasPorPagarDetalles=="fechaemision" || 
                                            qCampocuentasPorPagarDetalles=="fecharecepcion" || 
                                            qCampocuentasPorPagarDetalles=="fechavencimiento" ? "date": "text" 
                                        } className="form-control form-control-sm" placeholder={"Buscar por "+qCampocuentasPorPagarDetalles} onChange={e=>setqcuentasPorPagarDetalles(e.target.value)} value={qcuentasPorPagarDetalles} />
                        
                                            
                                            <span className={("btn arabito_")+(OrdercuentasPorPagarDetalles)} onClick={()=>setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")}>
                                                {(<i className={OrdercuentasPorPagarDetalles == "desc" ? "fa fa-arrow-up" : "fa fa-arrow-down"}></i>)}
                                            </span>
                                            <select className="form-control" value={qCampocuentasPorPagarDetalles} onChange={e=>setqCampocuentasPorPagarDetalles(e.target.value)}>
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
                                            <select className="form-control" value={categoriacuentasPorPagarDetalles} onChange={e=>setcategoriacuentasPorPagarDetalles(e.target.value)}>
                                                <option value="">-CATEGORÍA-</option>
                                                <option value="1">COMPRAS</option>
                                                <option value="2">SERVICIOS</option>
                                            </select>

                                            <select className="form-control" value={tipocuentasPorPagarDetalles} onChange={e=>settipocuentasPorPagarDetalles(e.target.value)}>
                                                <option value="">-TIPO-</option>
                                                <option value="DEUDA">CRÉDITOS</option>
                                                <option value="ABONOS">DÉBITOS</option>
                                            </select>
                                            <button type="button" className="btn btn-success" onClick={()=>selectCuentaPorPagarProveedorDetallesFun(id_proveedor)}><i className="fa fa-search"></i></button>
                                        </div>                
                                    </form>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                        {
                            selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                            ? selectCuentaPorPagarId.detalles.filter(e=>e.monto<0).map( (e,i) =>
                            
                                <tr key={e.id}>
                                    <td className="align-middle">
                                        <span className={
                                            (e.condicion=="pagadas"?"btn-success":(e.condicion=="vencidas"?"btn-danger":(e.condicion=="porvencer"?"btn-sinapsis":(e.condicion=="semipagadas"?"btn-primary":null))))+(" w-100 btn pointer btn-sm")
                                        } onClick={()=>selectFacturaSetPago(e.id,e.numfact)}>{e.numfact}</span>
                                    </td>
                                    <td className="align-middle cell3">
                                        <input type="text" className="form-control form-control-sm" onChange={event=>setInputAbonoFact(e.id,event.currentTarget.value)} placeholder={e.numfact} />
                                    </td>
                                    <td className="align-middle text-right">
                                        <div><span className={(e.balance<0? "text-danger": "text-success")+(" ")}>B. {moneda(e.balance)}</span></div>
                                        <div><span className={(e.monto_abonado<0? "text-danger": "text-success")+(" fs-7")}>A. {moneda(e.monto_abonado)}</span></div>
                                        <div><span className={(e.monto<0? "text-danger": "text-success")+(" fs-7")}>D. {moneda(e.monto)}</span></div>
                                    </td>
                                </tr>
                            )
                            : null : null
                            
                        } 
                        </tbody>

                    </table>
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
                        <div className="form-group">
                            <span># FACTURA</span>
                            <input type="text" placeholder="numfact" value={newfactnumfact} onChange={e=>setnewfactnumfact(e.target.value)} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span># CONTROL</span>
                            <input type="text" placeholder="numnota" value={newfactnumnota} onChange={e=>setnewfactnumnota(e.target.value)} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>DESCRIPCIÓN</span>
                            <input type="text" placeholder="descripcion" value={newfactdescripcion} onChange={e=>setnewfactdescripcion(e.target.value)} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>SUBTOTAL</span>
                            <input type="text" placeholder="subtotal" value={newfactsubtotal} onChange={e=>setnewfactsubtotal(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>DESCUENTO</span>
                            <input type="text" placeholder="descuento" value={newfactdescuento} onChange={e=>setnewfactdescuento(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>MONTO EXENTO</span>
                            <input type="text" placeholder="monto_exento" value={newfactmonto_exento} onChange={e=>setnewfactmonto_exento(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>MONTO GRAVABLE</span>
                            <input type="text" placeholder="monto_gravable" value={newfactmonto_gravable} onChange={e=>setnewfactmonto_gravable(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>IVA</span>
                            <input type="text" placeholder="iva" value={newfactiva} onChange={e=>setnewfactiva(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>TOTAL</span>
                            <input type="text" placeholder="monto" value={newfactmonto} onChange={e=>setnewfactmonto(number(e.target.value))} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>EMISIÓN</span>
                            <input type="date" placeholder="fechaemision" value={newfactfechaemision} onChange={e=>setnewfactfechaemision(e.target.value)} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>VENCIMIENTO</span>
                            <input type="date" placeholder="fechavencimiento" value={newfactfechavencimiento} onChange={e=>setnewfactfechavencimiento(e.target.value)} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>RECEPCIÓN</span>
                            <input type="date" placeholder="fecharecepcion" value={newfactfecharecepcion} onChange={e=>setnewfactfecharecepcion(e.target.value)} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>NOTA</span>
                            <input type="text" placeholder="nota" value={newfactnota} onChange={e=>setnewfactnota(e.target.value)} className="form-control" />
                        </div>
                        <div className="form-group">
                            <span>TIPO</span>
                            
                            <select className="form-control" value={newfacttipo} onChange={e=>setnewfacttipo(e.target.value)}>
                                <option value="">-</option>
                                <option value="1">COMPRAS</option>
                                <option value="2">SERVICIOS</option>
                            </select>
                            
                        </div>
                        <div className="form-group">
                            <span>FRECUENCIA</span>
                            <input type="text" placeholder="frecuencia" value={newfactfrecuencia} onChange={e=>setnewfactfrecuencia(e.target.value)} className="form-control" />
                        </div>

                        <div className="div-fijo-inferiorder">
                                <div className="btn-group">
        
                                    {selectFactEdit!==null?
                                        <span className="btn btn-danger" onClick={()=>setselectFactEdit(null)}> <i className="fa fa-times"></i>     </span>
                                        :null
                                    }
                                
                                    {selectFactEdit!==null?
                                    <button className="btn btn-sinapsis" type="submit">Editar</button>
                                    :
                                    <button className="btn btn-success" type="submit">Guardar</button>
                                }
                                </div>
                        </div>
                    </form>
                </>                        
            :null}


            <button className="btn boton-fijo-inferiorizq btn-sinapsis" onClick={()=>setcuentasporpagarDetallesView("cuentas")} type="button">
                <i className="fa fa-list"></i>
            </button>
        </div>
    )
}