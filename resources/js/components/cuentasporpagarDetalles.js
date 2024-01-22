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
}){
    let proveedorMain = ""
    let id_proveedor = null

    if (selectCuentaPorPagarId) {
        if (selectCuentaPorPagarId.detalles) {
            if (selectCuentaPorPagarId.detalles[0]) {
                if (selectCuentaPorPagarId.detalles[0].proveedor) {
                    proveedorMain = selectCuentaPorPagarId.detalles[0].proveedor.descripcion
                    id_proveedor = selectCuentaPorPagarId.detalles[0].proveedor.id
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
    }
    return (
        <div className="container mb-4">
            {
            SelectCuentaPorPagarDetalle?
                dataCuenta?
                <>
                    <div className="mb-2">
                        <button className="btn btn-danger" onClick={()=>setSelectCuentaPorPagarDetalle(null)}>
                            <i className="fa fa-times"></i>
                        </button>
                    </div>
                    <table className="table">
                        <tbody>
                            {dataCuenta.monto_abonado && dataCuenta.cuenta?
                                <tr className="d-flex justify-content-center align-items-center">
                                    <td>
                                        ABONADO A FACT <span className="text-success">{moneda(dataCuenta.monto_abonado)}</span>                                        
                                    </td>
                                    <td>
                                        {dataCuenta.cuenta.descripcion} <span className="btn btn-success">{moneda(dataCuenta.cuenta.monto)}</span>
                                    </td>
                                </tr>
                            :null}
                            <tr>
                                <th>PROVEEDOR</th>
                                <td>{dataCuenta.proveedor.descripcion} <small className="text-muted">RIF. {dataCuenta.proveedor.rif}</small></td>
                            </tr>
                            <tr>
                                <th># FACTURA</th>
                                <td>{dataCuenta.numfact}</td>
                            </tr>
                            <tr>
                                <th># CONTROL</th>
                                <td>{dataCuenta.numnota}</td>
                            </tr>
                            <tr>
                                <th>DESCRIPCIÓN</th>
                                <td>{dataCuenta.descripcion}</td>
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
                                <td className={dataCuenta.monto<0?"text-danger":("text-success")}>{moneda(dataCuenta.monto)}</td>
                            </tr>
                            
                            <tr>
                                <th>EMISIÓN</th>
                                <td className="text-success">{dataCuenta.fechaemision}</td>
                            </tr>
                            <tr>
                                <th>RECEPCIÓN</th>
                                <td className="text-sinapsis">{dataCuenta.fecharecepcion}</td>
                            </tr>
                            <tr>
                                <th>VENCIMIENTO</th>
                                <td className="text-danger">{dataCuenta.fechavencimiento}</td>
                            </tr>
                            <tr>
                                <th>NOTA</th>
                                <td>{dataCuenta.nota}</td>
                            </tr>
                            <tr>
                                <th>TIPO</th>
                                <td>{dataCuenta.tipo==1?"COMPRA":"SERVICIO"}</td>
                            </tr>
                            <tr>
                                <th>FRECUENCIA</th>
                                <td>{dataCuenta.frecuencia}</td>
                            </tr>
                            <tr>
                                <td colSpan={2}>
                                    <span className="btn btn-sinapsis" onClick={()=>modeEditarFact(dataCuenta.id)}>
                                        <i className="fa fa-pencil"></i>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </>
                :null
            :<>
                <div className="mb-2 d-flex justify-content-between">
                    <button className="btn btn-danger" onClick={()=>setSelectCuentaPorPagarId(null)}>
                        <i className="fa fa-times"></i>
                    </button>

                    <small className="text-muted">
                        {proveedorMain}
                    </small>
                </div>
                <form onSubmit={e=>{e.preventDefault();selectCuentaPorPagarProveedorDetallesFun(id_proveedor)}} className="mb-2">
                    <div className="input-group">
                        <input type="text" className="form-control" placeholder={"Buscar en "+proveedorMain+" por "+qCampocuentasPorPagarDetalles} onChange={e=>setqcuentasPorPagarDetalles(e.target.value)} value={qcuentasPorPagarDetalles} />
                        <span className={("btn arabito_")+(OrdercuentasPorPagarDetalles)} onClick={()=>setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")}>
                        {(<i className={OrdercuentasPorPagarDetalles == "desc" ? "fa fa-arrow-up" : "fa fa-arrow-down"}></i>)}
                        </span>
                        <select className="form-control" value={qCampocuentasPorPagarDetalles} onChange={e=>setqCampocuentasPorPagarDetalles(e.target.value)}>
                            <option value="">-Buscar en-</option>
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
                        <input type="date" className="form-control" value={fechacuentasPorPagarDetalles} onChange={e=>setfechacuentasPorPagarDetalles(e.target.value)} />
                        <span className={("btn arabito_")+(OrderFechacuentasPorPagarDetalles)} onClick={()=>setOrderFechacuentasPorPagarDetalles(OrderFechacuentasPorPagarDetalles==="desc"?"asc":"desc")}>
                        {(<i className={OrderFechacuentasPorPagarDetalles == "desc" ? "fa fa-arrow-up" : "fa fa-arrow-down"}></i>)}
                        </span>
                        <select className="form-control" value={qFechaCampocuentasPorPagarDetalles} onChange={e=>setqFechaCampocuentasPorPagarDetalles(e.target.value)}>
                            <option value="fechavencimiento">Vencimiento</option>
                            <option value="fechaemision">Emisión</option>
                            <option value="fecharecepcion">Recepción</option>
                            <option value="created_at">Creación</option>

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

                { 
                    selectCuentaPorPagarId?
                        selectCuentaPorPagarId.balance? 
                        <div className="text-right">
                            <span className={(selectCuentaPorPagarId.balance<0? "text-danger": "text-success")+(" fs-2 mb-1 mt-1")}>{moneda(selectCuentaPorPagarId.balance)}</span>
                        </div>
                        :null
                    :null
                }
                {
                    selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                    ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                        <div className="text-secondary mb-3 pointer shadow p-2 card" key={e.id}>
                            <div className="d-flex justify-content-between">
                                <span className="fw-bold">{e.proveedor.descripcion}</span>
                                <small className="text-muted">{e.created_at}</small>
                                <span>{e.sucursal.codigo}</span>
                            </div>
                            <div>
                                <div onClick={()=>setSelectCuentaPorPagarDetalle(e.id)} className="">
                                    <span className={(e.monto>0?"btn-success":"btn-sinapsis")+(" w-100 btn fs-3 pointer")}>{e.numfact}</span>
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