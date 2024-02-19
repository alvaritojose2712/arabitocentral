import { useEffect } from "react";
import Proveedores from "./proveedores";
import SearchBarFacturas from "./searchBarFacturas";

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
    delItemSelectAbonoFact,

    cuentaporpagarAprobado,
    setcuentaporpagarAprobado,
    setselectProveedorCxp,
    selectProveedorCxp,
    sucursalcuentasPorPagarDetalles,
    sucursales,

    
}){

    /* useEffect(()=>{
        setqcuentasPorPagarDetalles("")
        setqCampocuentasPorPagarDetalles("numfact")
        setcategoriacuentasPorPagarDetalles("")
        settipocuentasPorPagarDetalles("")
        setqcuentasPorPagarTipoFact("")
        setsucursalcuentasPorPagarDetalles("")
    },[])  */

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
                    <form onSubmit={sendPagoCuentaPorPagar} className="border p-2 card shadow mt-3 mb-3">
                        <div className="row mb-2">
                            <div className="col">
                                <span className="form-label">Monto</span>
                                <input type="text" className="form-control fs-3 text-success" placeholder="Monto TOTAL de ABONO" value={cuentasPagosMonto} onChange={e=>setcuentasPagosMonto(number(e.target.value))} required />
                            </div>

                            <div className="col">
                                <span className="form-label">Descripción</span>
                                <input type="text" className="form-control fs-3" placeholder="Referencia" value={cuentasPagosDescripcion} onChange={e=>setcuentasPagosDescripcion(e.target.value)} required/>
                            </div>
                            <div className="col">
                                <span className="form-label">Proveedor</span>
                                <select className="form-control fs-3" value={selectProveedorCxp} onChange={e=>setselectProveedorCxp(e.target.value)} required>
                                    <option value="">-PROVEEDOR-</option>
                                    {proveedoresList.map(e=>
                                        <option key={e.id} value={e.id}>{e.descripcion}-{e.rif}</option>
                                        )}
                                </select>
                            </div>

                        </div>
                            
                        <div className="input-group mb-2">
                            <select className="form-control fs-3" 
                            value={cuentasPagosMetodo} 
                            onChange={e=>setcuentasPagosMetodo(e.target.value)} required>
                                <option value="">-Método-</option>
                                {opcionesMetodosPago.map(e=>
                                    <option value={e.codigo} key={e.codigo}>{e.descripcion}</option>
                                )}
                            </select>
                            <input type="date" className="form-control fs-3" value={cuentasPagosFecha} onChange={e=>setcuentasPagosFecha(e.target.value)} required />
                            
                        </div>

                        <div className="form-group text-center">
                            {selectFactPagoid==null?
                                <button className="btn btn-success" type="submit">GUARDAR <i className="fa fa-save"></i></button>
                                :
                                <button className="btn btn-sinapsis" type="submit">EDITAR <i className="fa fa-pencil"></i></button>
                            }
                        </div>
                    </form>

                    
                    <table className="table mt-2 table-sm">
                        <thead>
                            <tr>
                                <th colSpan={6} className="fs-5">ESPECIFICAR ABONO</th>
                            </tr>
                            <tr>
                                <th colSpan={6}>
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

                                </th>
                            </tr>
                        </thead>
                        <tbody>

                        {
                            
                            (selectAbonoFact)
                            .concat((selectCuentaPorPagarId?(selectCuentaPorPagarId.detalles? selectCuentaPorPagarId.detalles.filter(e=>!selectAbonoFact.map(ee=>ee.id).includes(e.id)): ([])) : ([])) )
                            .filter(e=>e.monto<0&&e.condicion!="pagadas")
                            .sort((a,b)=>b.balance-a.balance)
                            .map( (e,i) =>
                            
                                <tr className={("shadow border-top border-top-5 border-dark")+ (e.guardado===true?" bg-success-light":"")} key={i}>
                                    
                                    <td className="p-3 align-bottom">
                                        <span className="fws-italic">{e.sucursal.codigo}</span>
                                        <br />   
                                        <span className="fw-bold">{e.proveedor.descripcion}</span>
                                    </td>  
                                    <td className="p-3 align-bottom text-right">
                                        <span className="text-success">E: {e.fechaemision}</span> <br />
                                        <span className="text-danger ms-1">V: {e.fechavencimiento}</span>
                                    </td>       
                                    <td className="p-3 align-bottom text-right">
                                        <span className="text-muted fs-4">{moneda(e.monto_bruto)}</span>
                                        <br />
                                        <span className="text-muted fst-italic">{moneda(e.monto_descuento)} ({e.descuento}%)</span>
                                    </td>
                                    <td className="p-3 align-bottom text-right">
                                        <span className="m-2">
                                            {e.aprobado==0?<i className="fa-2x fa fa-clock-o text-sinapsis"></i>:<i className="fa-2x fa fa-check text-success"></i>} 

                                        </span>
                                        <span 
                                        onClick={()=>setInputAbonoFact(e.id, (cuentasPagosMonto!="" && (sumSelectAboFact+0.1)<cuentasPagosMonto ?(e.balance*-1>restaAbono*-1?restaAbono*-1:e.balance*-1):""))}
                                        className={
                                            (e.condicion=="pagadas"?"btn-medsuccess":(e.condicion=="vencidas"?"btn-danger":(e.condicion=="porvencer"?"btn-sinapsis":(e.condicion=="semipagadas"?"btn-primary":(e.condicion=="abonos"?"btn-success":null)))))+(" w-75 btn fs-6 pointer")
                                        }> 
                                        {e.monto<0?"FACT ":"ABONO "} 
                                        {e.numfact}
                                        </span>
                                    </td>  
                                    <td className="p-3 align-bottom text-right">
                                        {e.guardado==true?
                                            <input type="text" size={8} className="ms-5 fs-4 text-success" 
                                            value={e.val}
                                            onChange={event=>{
                                                let val = number(event.currentTarget.value)
                                                setInputAbonoFact(e.id, val)
                                                event.target.value = (val)
                                            }} placeholder="Abono" />
                                        :
                                            <input type="text" size={8} className="ms-5" 
                                            value={""}
                                            onChange={event=>{
                                                let val = event.currentTarget.value
                                                setInputAbonoFact(e.id, val)
                                                event.target.value = val
                                            }} placeholder="Abono" />
                                        }
                                    </td>
                                    <td className="p-3 align-middle text-right">
                                        
                                        <div className="mb-1">
                                            <span className={(e.monto<0? "text-danger": "text-success")+(" fs-7")}>DEUDA</span>
                                            <span className={(e.monto<0? "text-danger": "text-success")+(" fs-5 fw-bold ")}>{moneda(e.monto)}</span>
                                        </div>
                                        
                                        {e.monto_abonado?
                                            <div className="">
                                                <span className={(e.monto_abonado<0? "text-danger": "text-success")+(" fs-7")}>ABONADO</span>
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
                                        
                                    </td>
                                </tr>
                            )
                            
                        } 
                        </tbody>

                    </table>

                    <table className="table table-sm mt-5">
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
                                                    FACT {e.numfact}
                                                </button> 

                                            </div>
                                        </td>
                                        <td className="text-center align-middle">
                                            <span className="text-success">{moneda(e.val)}</span> / <span className="text-danger">{moneda(e.balance)}</span> 
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
               </>  
            :null}

            {subviewAgregarFactPago=="factura"?
                <>
                    <form onSubmit={saveNewFact}>
                        
                        <div className="form-group">
                            <span>PROVEEDOR</span>
                            
                            <select className="form-control" value={selectProveedorCxp} onChange={e=>setselectProveedorCxp(e.target.value)}>
                                <option value="">-</option>
                                {proveedoresList.map(e=>
                                    <option key={e.id} value={e.id}>{e.rif}-{e.descripcion}</option>
                                )}
                            </select>
                            
                        </div> 
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