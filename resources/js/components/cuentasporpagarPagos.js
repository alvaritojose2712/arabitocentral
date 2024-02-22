import { useEffect, useState } from "react";
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
    newfactsucursal,
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
    qcuentasPorPagarTipoFact,

    showImageFact,

    setmontobs1PagoFact,
    montobs1PagoFact,
    settasabs1PagoFact,
    tasabs1PagoFact,
    setmetodobs1PagoFact,
    metodobs1PagoFact,
    setmontobs2PagoFact,
    montobs2PagoFact,
    settasabs2PagoFact,
    tasabs2PagoFact,
    setmetodobs2PagoFact,
    metodobs2PagoFact,
    setmontobs3PagoFact,
    montobs3PagoFact,
    settasabs3PagoFact,
    tasabs3PagoFact,
    setmetodobs3PagoFact,
    metodobs3PagoFact,
    setmontobs4PagoFact,
    montobs4PagoFact,
    settasabs4PagoFact,
    tasabs4PagoFact,
    setmetodobs4PagoFact,
    metodobs4PagoFact,
    setmontobs5PagoFact,
    montobs5PagoFact,
    settasabs5PagoFact,
    tasabs5PagoFact,
    setmetodobs5PagoFact,
    metodobs5PagoFact,
}){
    const [viewMultiplesPagos, setviewMultiplesPagos] = useState(0)
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

    let sumBsMonto1 = 0
    let sumBsMonto2 = 0
    let sumBsMonto3 = 0
    let sumBsMonto4 = 0
    let sumBsMonto5 = 0

    useEffect(()=>{
        sumBsMonto1 = parseFloat((parseFloat(montobs1PagoFact?montobs1PagoFact:0)/parseFloat(tasabs1PagoFact?tasabs1PagoFact:0)).toFixed(2)) 
        sumBsMonto2 = parseFloat((parseFloat(montobs2PagoFact?montobs2PagoFact:0)/parseFloat(tasabs2PagoFact?tasabs2PagoFact:0)).toFixed(2)) 
        sumBsMonto3 = parseFloat((parseFloat(montobs3PagoFact?montobs3PagoFact:0)/parseFloat(tasabs3PagoFact?tasabs3PagoFact:0)).toFixed(2)) 
        sumBsMonto4 = parseFloat((parseFloat(montobs4PagoFact?montobs4PagoFact:0)/parseFloat(tasabs4PagoFact?tasabs4PagoFact:0)).toFixed(2)) 
        sumBsMonto5 = parseFloat((parseFloat(montobs5PagoFact?montobs5PagoFact:0)/parseFloat(tasabs5PagoFact?tasabs5PagoFact:0)).toFixed(2)) 
        let sum =(sumBsMonto1?sumBsMonto1:0)+(sumBsMonto2?sumBsMonto2:0)+(sumBsMonto3?sumBsMonto3:0)+(sumBsMonto4?sumBsMonto4:0)+(sumBsMonto5?sumBsMonto5:0)
        if (sum) {
            setcuentasPagosMonto(sum)
        }
    },[
        montobs1PagoFact,
        tasabs1PagoFact,
        montobs2PagoFact,
        tasabs2PagoFact,
        montobs3PagoFact,
        tasabs3PagoFact,
        montobs4PagoFact,
        tasabs4PagoFact,
        montobs5PagoFact,
        tasabs5PagoFact,
    ])
   

    
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
                                <span className="form-label">Monto en $</span>
                                <div className="input-group">
                                    <input type="text" className="form-control fs-3 text-success" placeholder="Monto TOTAL de ABONO $" value={cuentasPagosMonto} onChange={e=>setcuentasPagosMonto(number(e.target.value))} required />
                                    <button className="btn btn-success" type="button" onClick={()=>setviewMultiplesPagos(viewMultiplesPagos==5?0:viewMultiplesPagos+1)}>
                                        {viewMultiplesPagos} <i className="fa fa-plus"></i>
                                    </button>

                                </div>
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
                        {
                            viewMultiplesPagos!=0 || montobs1PagoFact?
                                <div className="row mb-2">
                                    <div className="col-md-auto text-sinapsis">
                                        <span className="form-label">Monto Bs 1</span>
                                        <input type="text" className="form-control fs-6 text-sinapsis" placeholder="Monto Bs 1" value={montobs1PagoFact} onChange={e=>setmontobs1PagoFact(number(e.target.value))} />
                                    </div>

                                    <div className="col-md-auto">
                                        <span className="form-label">Tasa</span>
                                        <input type="text" className="form-control fs-6" placeholder="Tasa 1" size={4} value={tasabs1PagoFact} onChange={e=>settasabs1PagoFact(number(e.target.value))}/>
                                    </div>
                                    <div className="col-md-auto">
                                        <span className="form-label">Método 1</span>
                                        <select className="form-control fs-6" 
                                        value={metodobs1PagoFact} 
                                        onChange={e=>setmetodobs1PagoFact(e.target.value)} >
                                            <option value="">-Método 1-</option>
                                            {opcionesMetodosPago.map(e=>
                                                <option value={e.codigo} key={e.codigo}>{e.descripcion}</option>
                                                )}
                                        </select>    
                                    </div>
                                    
                                </div>
                            :null
                        }
                        {
                            ([2,3,4,5].indexOf(viewMultiplesPagos)!=-1 && viewMultiplesPagos!=0) || montobs2PagoFact?
                                <div className="row mb-2">
                                    <div className="col-md-auto text-sinapsis">
                                        <span className="form-label">Monto Bs 2</span>
                                        <input type="text" className="form-control fs-6 text-sinapsis" placeholder="Monto Bs 2" value={montobs2PagoFact} onChange={e=>setmontobs2PagoFact(number(e.target.value))} />
                                    </div>

                                    <div className="col-md-auto">
                                        <span className="form-label">Tasa</span>
                                        <input type="text" className="form-control fs-6" placeholder="Tasa 2" size={4} value={tasabs2PagoFact} onChange={e=>settasabs2PagoFact(number(e.target.value))}/>
                                    </div>
                                    <div className="col-md-auto">
                                        <span className="form-label">Método 2</span>
                                        <select className="form-control fs-6" 
                                        value={metodobs2PagoFact} 
                                        onChange={e=>setmetodobs2PagoFact(e.target.value)} >
                                            <option value="">-Método 2-</option>
                                            {opcionesMetodosPago.map(e=>
                                                <option value={e.codigo} key={e.codigo}>{e.descripcion}</option>
                                            )}
                                        </select>    
                                    </div>
                                    
                                </div>
                            :null
                        }
                        {
                            ([3,4,5].indexOf(viewMultiplesPagos)!=-1 && viewMultiplesPagos!=0) || montobs3PagoFact?
                                <div className="row mb-2">
                                    <div className="col-md-auto text-sinapsis">
                                        <span className="form-label">Monto Bs 3</span>
                                        <input type="text" className="form-control fs-6 text-sinapsis" placeholder="Monto Bs 3" value={montobs3PagoFact} onChange={e=>setmontobs3PagoFact(number(e.target.value))} />
                                    </div>

                                    <div className="col-md-auto">
                                        <span className="form-label">Tasa</span>
                                        <input type="text" className="form-control fs-6" placeholder="Tasa 3" size={4} value={tasabs3PagoFact} onChange={e=>settasabs3PagoFact(number(e.target.value))}/>
                                    </div>
                                    <div className="col-md-auto">
                                        <span className="form-label">Método 3</span>
                                        <select className="form-control fs-6" 
                                        value={metodobs3PagoFact} 
                                        onChange={e=>setmetodobs3PagoFact(e.target.value)} >
                                            <option value="">-Método 3-</option>
                                            {opcionesMetodosPago.map(e=>
                                                <option value={e.codigo} key={e.codigo}>{e.descripcion}</option>
                                            )}
                                        </select>    
                                    </div>
                                    
                                </div>
                            :null
                        }
                        {
                            ([4,5].indexOf(viewMultiplesPagos)!=-1 && viewMultiplesPagos!=0) || montobs4PagoFact?
                                <div className="row mb-2">
                                    <div className="col-md-auto text-sinapsis">
                                        <span className="form-label">Monto Bs 4</span>
                                        <input type="text" className="form-control fs-6 text-sinapsis" placeholder="Monto Bs 4" value={montobs4PagoFact} onChange={e=>setmontobs4PagoFact(number(e.target.value))} />
                                    </div>

                                    <div className="col-md-auto">
                                        <span className="form-label">Tasa</span>
                                        <input type="text" className="form-control fs-6" placeholder="Tasa 4" size={4} value={tasabs4PagoFact} onChange={e=>settasabs4PagoFact(number(e.target.value))}/>
                                    </div>
                                    <div className="col-md-auto">
                                        <span className="form-label">Método 4</span>
                                        <select className="form-control fs-6" 
                                        value={metodobs4PagoFact} 
                                        onChange={e=>setmetodobs4PagoFact(e.target.value)} >
                                            <option value="">-Método 4-</option>
                                            {opcionesMetodosPago.map(e=>
                                                <option value={e.codigo} key={e.codigo}>{e.descripcion}</option>
                                            )}
                                        </select>    
                                    </div>
                                    
                                </div>
                            :null
                        }
                        {
                            ([5].indexOf(viewMultiplesPagos)!=-1 && viewMultiplesPagos!=0) || montobs5PagoFact?
                                <div className="row mb-2">
                                    <div className="col-md-auto text-sinapsis">
                                        <span className="form-label">Monto Bs 5</span>
                                        <input type="text" className="form-control fs-6 text-sinapsis" placeholder="Monto Bs 5" value={montobs5PagoFact} onChange={e=>setmontobs5PagoFact(number(e.target.value))} />
                                    </div>

                                    <div className="col-md-auto">
                                        <span className="form-label">Tasa</span>
                                        <input type="text" className="form-control fs-6" placeholder="Tasa 5" size={4} value={tasabs5PagoFact} onChange={e=>settasabs5PagoFact(number(e.target.value))}/>
                                    </div>
                                    <div className="col-md-auto">
                                        <span className="form-label">Método 5</span>
                                        <select className="form-control fs-6" 
                                        value={metodobs5PagoFact} 
                                        onChange={e=>setmetodobs5PagoFact(e.target.value)} >
                                            <option value="">-Método 5-</option>
                                            {opcionesMetodosPago.map(e=>
                                                <option value={e.codigo} key={e.codigo}>{e.descripcion}</option>
                                            )}
                                        </select>    
                                    </div>
                                    
                                </div>
                         :null
                        }   
                            
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
                                    <div className="btn-group">
                                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="abonos"?"btn-success":"btn-outline-success")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="abonos"?"":"abonos")}>PAGOS</span>

                                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="pagadas"?"btn-medsuccess":"btn-outline-medsuccess")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="pagadas"?"":"pagadas")}>PAGADAS</span>

                                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="semipagadas"?"btn-primary":"btn-outline-primary")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="semipagadas"?"":"semipagadas")}>ABONADAS</span>
                                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="porvencer"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="porvencer"?"":"porvencer")}>POR VENCER</span>
                                        <span className={("btn btn-sm ")+(qcuentasPorPagarTipoFact=="vencidas"?"btn-danger":"btn-outline-danger")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="vencidas"?"":"vencidas")}>VENCIDAS</span>
                                    </div>
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

                        <table className="table table-sm">
                            <tbody>
                                
                                <tr>
                                    <td>ENVIADO POR</td>
                                    <td>
                                        {newfactsucursal}
                                    </td>
                                </tr>
                                <tr>
                                    <td>PROVEEDOR</td>
                                    <td>
                                        <select className="form-control" value={selectProveedorCxp} onChange={e=>setselectProveedorCxp(e.target.value)}>
                                            <option value="">-</option>
                                            {proveedoresList.map(e=>
                                                <option key={e.id} value={e.id}>{e.descripcion}-{e.rif}</option>
                                            )}
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {newfactnumfact>0?"# PAGO":"# FACTURA"}
                                    </td>
                                    <td>
                                        <input type="text" placeholder="numfact" value={newfactnumfact} onChange={e=>setnewfactnumfact(e.target.value)} className="form-control" />
                                    </td>
                                </tr>
                                <tr>
                                    <td># CONTROL</td>
                                    <td><input type="text" placeholder="numnota" value={newfactnumnota} onChange={e=>setnewfactnumnota(e.target.value)} className="form-control" /></td>
                                </tr>
                                <tr>
                                    <td>DESCRIPCIÓN</td>
                                    <td>
                                        <button 
                                        className={"btn btn-info"}
                                        onClick={()=>showImageFact(newfactdescripcion)}
                                        type="button">{newfactdescripcion}</button>

                                    </td>
                                </tr>
                                <tr>
                                    <th>SUBTOTAL</th>
                                    <td><input type="text" placeholder="subtotal" value={newfactsubtotal} onChange={e=>setnewfactsubtotal(number(e.target.value))} className="form-control" /></td>
                                </tr>
                                <tr>
                                    <th>DESCUENTO %</th>
                                    <td><input type="text" placeholder="descuento" value={newfactdescuento} onChange={e=>setnewfactdescuento(number(e.target.value))} className="form-control" /></td>
                                </tr>
                                <tr>
                                    <th>MONTO EXENTO</th>
                                    <td><input type="text" placeholder="monto_exento" value={newfactmonto_exento} onChange={e=>setnewfactmonto_exento(number(e.target.value))} className="form-control" /></td>
                                </tr>
                                <tr>
                                    <th>MONTO GRAVABLE</th>
                                    <td><input type="text" placeholder="monto_gravable" value={newfactmonto_gravable} onChange={e=>setnewfactmonto_gravable(number(e.target.value))} className="form-control" /></td>
                                </tr>
                                <tr>
                                    <th>IVA</th>
                                    <td><input type="text" placeholder="iva" value={newfactiva} onChange={e=>setnewfactiva(number(e.target.value))} className="form-control" /></td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <td><input type="text" placeholder="monto" value={newfactmonto} onChange={e=>setnewfactmonto(number(e.target.value))} className="form-control fs-3 text-danger" /></td>
                                </tr>
                                <tr>
                                    <td>EMISIÓN</td>
                                    <td><input type="date" placeholder="fechaemision" value={newfactfechaemision} onChange={e=>setnewfactfechaemision(e.target.value)} className="form-control text-success" /></td>
                                </tr>
                                <tr>
                                    <td>RECEPCIÓN</td>
                                    <td><input type="date" placeholder="fecharecepcion" value={newfactfecharecepcion} onChange={e=>setnewfactfecharecepcion(e.target.value)} className="form-control text-sinapsis" /></td>
                                </tr>
                                <tr>
                                    <td>VENCIMIENTO</td>
                                    <td><input type="date" placeholder="fechavencimiento" value={newfactfechavencimiento} onChange={e=>setnewfactfechavencimiento(e.target.value)} className="form-control text-danger" /></td>
                                </tr>
                                <tr>
                                    <td>NOTA</td>
                                    <td><input type="text" placeholder="nota" value={newfactnota} onChange={e=>setnewfactnota(e.target.value)} className="form-control" /></td>
                                </tr>
                                <tr>
                                    <td>TIPO</td>
                                    <td>
                                        <select className="form-control" value={newfacttipo} onChange={e=>setnewfacttipo(e.target.value)}>
                                            <option value="">-</option>
                                            <option value="1">COMPRAS</option>
                                            <option value="2">SERVICIOS</option>
                                        </select>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td>FRECUENCIA</td>
                                    <td><input type="text" placeholder="frecuencia" value={newfactfrecuencia} onChange={e=>setnewfactfrecuencia(e.target.value)} className="form-control" /></td>
                                </tr>
                                
                                
                            </tbody>
                        </table>
                        

                        <div className="boton-fijo-inferiorizq">
                                <div className="btn-group">
        
                                    {selectFactEdit!==null?
                                    <button className="btn btn-sinapsis fs-3" type="submit">GUARDAR CAMBIOS <i className="fa fa-pencil"></i></button>
                                    :
                                    <button className="btn btn-success fs-3" type="submit">GUARDAR <i className="fa fa-save"></i></button>

                                }
                                </div>
                        </div>
                    </form>
                </>                        
            :null}


            <button className="btn div-fijo-inferiorder btn-danger fs-3" onClick={()=>setcuentasporpagarDetallesView("cuentas")} type="button">
                <i className="fa fa-arrow-left"></i> VOLVER
            </button>
        </div>
    )
}