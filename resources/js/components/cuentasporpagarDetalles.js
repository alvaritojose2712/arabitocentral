export default function CuentasporpagarDetalles({
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
}){
    let proveedorMain = ""

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
    return (
        <div>
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
                                <td>{dataCuenta.subtotal}</td>
                            </tr>
                            <tr>
                                <th>DESCUENTO</th>
                                <td>{dataCuenta.descuento}</td>
                            </tr>
                            <tr>
                                <th>MONTO EXENTO</th>
                                <td>{dataCuenta.monto_exento}</td>
                            </tr>
                            <tr>
                                <th>MONTO GRAVABLE</th>
                                <td>{dataCuenta.monto_gravable}</td>
                            </tr>
                            <tr>
                                <th>IVA</th>
                                <td>{dataCuenta.iva}</td>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <td>{dataCuenta.monto}</td>
                            </tr>
                            <tr>
                                <th>BALANCE</th>
                                <td>{dataCuenta.balance}</td>
                            </tr>
                            <tr>
                                <th>EMISIÓN</th>
                                <td>{dataCuenta.fechaemision}</td>
                            </tr>
                            <tr>
                                <th>VENCIMIENTO</th>
                                <td>{dataCuenta.fechavencimiento}</td>
                            </tr>
                            <tr>
                                <th>RECEPCIÓN</th>
                                <td>{dataCuenta.fecharecepcion}</td>
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
                <form onSubmit={selectCuentaPorPagarProveedorDetallesFun} className="mb-2">
                    <div className="input-group">
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
                        <span className={("btn arabito_")+(OrdercuentasPorPagarDetalles)} onClick={()=>setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")}>
                        {(<i className={OrdercuentasPorPagarDetalles == "desc" ? "fa fa-arrow-up" : "fa fa-arrow-down"}></i>)}
                        </span>
                        <input type="text" className="form-control" placeholder={"Buscar en "+proveedorMain+" por "+qCampocuentasPorPagarDetalles} onChange={e=>setqcuentasPorPagarDetalles(e.target.value)} value={qcuentasPorPagarDetalles} />
                    </div>

                    <div className="input-group">
                        <select className="form-control" value={qFechaCampocuentasPorPagarDetalles} onChange={e=>setqFechaCampocuentasPorPagarDetalles(e.target.value)}>
                            <option value="fechavencimiento">Vencimiento</option>
                            <option value="fechaemision">Emisión</option>
                            <option value="fecharecepcion">Recepción</option>
                        </select>
                        <span className={("btn arabito_")+(OrderFechacuentasPorPagarDetalles)} onClick={()=>setOrderFechacuentasPorPagarDetalles(OrderFechacuentasPorPagarDetalles==="desc"?"asc":"desc")}>
                        {(<i className={OrderFechacuentasPorPagarDetalles == "desc" ? "fa fa-arrow-up" : "fa fa-arrow-down"}></i>)}
                        </span>
                        <input type="date" className="form-control" value={fechacuentasPorPagarDetalles} onChange={e=>setfechacuentasPorPagarDetalles(e.target.value)} />
                    </div>


                    <div className="input-group">
                        <select className="form-control" value={categoriacuentasPorPagarDetalles} onChange={e=>setcategoriacuentasPorPagarDetalles(e.target.value)}>
                            <option value="">-CATEGORÍA-</option>
                            <option value="1">COMPRAS</option>
                            <option value="2">SERVICIOS</option>
                        </select>

                        <select className="form-control" value={tipocuentasPorPagarDetalles} onChange={e=>settipocuentasPorPagarDetalles(e.target.value)}>
                            <option value="">-TIPO-</option>
                            <option value="DEUDA">DEUDA</option>
                            <option value="ABONOS">ABONOS</option>
                        </select>
                        <button type="button" className="btn btn-success" onClick={selectCuentaPorPagarProveedorDetallesFun}><i className="fa fa-search"></i></button>
                    </div>                


                </form>
                {
                    selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                    ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                        <div className="text-secondary mb-3 pointer shadow p-2 card" key={e.id}>
                            <div className="d-flex justify-content-between mb-1">
                                <small className="text-muted">{e.created_at}</small>
                                <span className={((e.estatus==0?"btn-danger":e.estatus==1?"btn-warning":e.estatus==2?"btn-success":""))+(" btn-sm btn pointer")}>
                                    {e.estatus==0?"CREADA":""}
                                    {e.estatus==1?"ENVIADA":""}
                                    {e.estatus==2?"PROCESADA":""}
                                </span>
                            </div>
                            <div>
                                <div onClick={()=>setSelectCuentaPorPagarDetalle(e.id)} className="">
                                    <span className={(i==factSelectIndex?"btn-success":"btn-sinapsis")+(" w-100 btn fs-3 pointer")}>{e.numfact}</span>
                                </div>
                            </div>
                            <p>
                                {e.proveedor.descripcion}
                            </p>
                            <div className="d-flex justify-content-between">
                                
                                <div><span className="text-success fs-3">{moneda(e.monto)}</span></div>
                            </div>
                        </div>
                    )
                    : null : null
                    
                } 
            </>}          
        </div>
    )
}