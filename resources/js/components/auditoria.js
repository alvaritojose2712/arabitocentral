import { useEffect, useState } from "react";
export default function Auditoria({
    opcionesMetodosPago,

    fechaSelectAuditoria,
    setfechaSelectAuditoria,
    
    fechaHastaSelectAuditoria,
    setfechaHastaSelectAuditoria,
    
    bancoSelectAuditoria,
    setbancoSelectAuditoria,
    qdescripcionbancosdata,
    setqdescripcionbancosdata,

    sucursales,

    sucursalSelectAuditoria,
    setsucursalSelectAuditoria,
    
    getMetodosPago,
    getBancosData,
    getCatGeneralFun,
    getCatCajas,

    bancosdata,

    cuentasPagosDescripcion,
    setcuentasPagosDescripcion,
    cuentasPagosMonto,
    setcuentasPagosMonto,
    cuentasPagosMetodo,
    setcuentasPagosMetodo,
    cuentasPagosFecha,
    setcuentasPagosFecha,
    sendMovimientoBanco,

    cuentasPagoTipo,
    setcuentasPagosTipo,
    cuentasPagosCategoria,
    setcuentasPagosCategoria,
    categoriaMovBanco,
    number,
    moneda,
}){
    useEffect(()=>{
        getMetodosPago()
        getBancosData()
        getCatGeneralFun()
        getCatCajas()
    },[])

    useEffect(()=>{
        getBancosData()
    },[
        qdescripcionbancosdata,
        fechaSelectAuditoria,
        fechaHastaSelectAuditoria,
        bancoSelectAuditoria,
        sucursalSelectAuditoria, 
    ])

    const [subviewAuditoria, setsubviewAuditoria] = useState("cuadre")

    return (
       <div className="container-fluid">
            <div className="d-flex justify-content-center">
                <div className="btn-group m-2">
                    <button className={("btn btn-sm ")+(subviewAuditoria=="cargar"?"btn-sinapsis":"")} onClick={()=>setsubviewAuditoria("cargar")}>Cargar</button>
                    <button className={("btn btn-sm ")+(subviewAuditoria=="cuadre"?"btn-sinapsis":"")} onClick={()=>setsubviewAuditoria("cuadre")}>Cuadre</button>
                </div>
            </div>

            {
                subviewAuditoria=="cargar"? 
                    <form onSubmit={sendMovimientoBanco}>
                        <div className="form-group">
                            <div className="input-group">
                                <span className="input-group-text cell3">Descripción</span>
                                <input type="text" className="form-control" placeholder="Referencia" value={cuentasPagosDescripcion} onChange={e=>setcuentasPagosDescripcion(e.target.value)} />
                            </div>
                        </div>

                        <div className="form-group">
                            <div className="input-group">
                                <span className="input-group-text cell3">Monto</span>
                                <input type="text" className="form-control" placeholder="Monto" value={cuentasPagosMonto} onChange={e=>setcuentasPagosMonto(number(e.target.value))} />
                                <input type="date" className="form-control" value={cuentasPagosFecha} onChange={e=>setcuentasPagosFecha(e.target.value)} />
                            </div>
                        </div>
                        <div className="form-group m-4 text-center">
                            <div className="btn-group">
                                <button onClick={()=>setcuentasPagosTipo("egreso")} className={(cuentasPagoTipo=="egreso"?"btn-danger":"")+(" btn")}>Egreso</button>
                                <button onClick={()=>setcuentasPagosTipo("ingreso")} className={(cuentasPagoTipo=="ingreso"?"btn-success":"")+(" btn")}>Ingreso</button>
                            </div>
                        </div>
                        
                        <div className="input-group">
                            <select className="form-control" 
                            value={cuentasPagosMetodo} 
                            onChange={e=>setcuentasPagosMetodo(e.target.value)}>
                                <option value="">-Método-</option>
                                {opcionesMetodosPago.filter(e=>e.codigo!="EFECTIVO").map(e=>
                                    <option value={e.id} key={e.id}>{e.descripcion}</option>
                                )}
                            </select>

                            <select className="form-control" 
                            value={cuentasPagosCategoria} 
                            onChange={e=>setcuentasPagosCategoria(e.target.value)}>
                                <option value="">-Categoría-</option>
                                {categoriaMovBanco.map(e=>
                                    <option value={e.id} key={e.id}>{e.descripcion}</option>
                                )}
                            </select>
                        </div>

                        <div className="form-group w-100">
                            <button className="mt-2 btn btn-outline-success btn-block w-100 btn-sm" type="submit">Guardar</button>
                        </div>
                    </form>

                :null
            }

            {
                subviewAuditoria=="cuadre"? 
                    <>
                        <div className="row">
                            <div className="col">

                                <div className="form-group">
                                    <div className="input-group">
                                        <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={event=>setfechaSelectAuditoria(event.target.value)}/>    
                                        <input type="date" className="form-control" value={fechaHastaSelectAuditoria} onChange={event=>setfechaHastaSelectAuditoria(event.target.value)}/>    
                                    </div>
                                </div>

                                <div className="form-group">
                                    <div className="input-group">
                                        <select className="form-control" value={bancoSelectAuditoria}  onChange={event=>setbancoSelectAuditoria(event.target.value)}>
                                            <option value="">-BANCO-</option>
                                            {opcionesMetodosPago.map(e=>
                                                <option key={e.id} value={e.id}>{e.codigo}</option>
                                            )}
                                        </select>
                                    </div>
                                </div>

                                <div className="form-group">
                                    <div className="input-group">
                                        <select className="form-control" value={sucursalSelectAuditoria}  onChange={event=>setsucursalSelectAuditoria(event.target.value)}>
                                            <option value="">-SUCURSAL-</option>
                                            {sucursales.map(e=>
                                                <option key={e.id} value={e.id}>{e.codigo}</option>
                                            )}
                                        </select>
                                    </div>
                                </div>
                                <div className="form-group">
                                    <div className="input-group">
                                        <input type="text" className="form-control" placeholder="Buscar Descripción o Monto..." value={qdescripcionbancosdata} onChange={event=>setqdescripcionbancosdata(event.target.value)}/>    
                                        <button className="btn btn-success" onClick={()=>getBancosData()}><i className="fa fa-search"></i></button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                <table className="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th colSpan={8} className="text-center bg-success">INGRESO</th>
                                            <th colSpan={4} className="text-center bg-danger">EGRESO</th>
                                        </tr>

                                        <tr>
                                            <th>BANCO</th>
                                            <th colSpan={2} className="text-right bg-primary-light">TRANSFERENCIA</th>
                                            <th colSpan={2} className="text-right bg-warning-light">PUNTO</th>
                                            <th colSpan={2} className="text-right bg-danger-light">BIOPAGO</th>
                                            
                                            <th className="text-right bg-warning"></th>
                                            <th className="text-right bg-warning"></th>

                                            <th colSpan={2} className="text-right bg-primary-light">TRANSFERENCIA</th>
                                            
                                            <th className="text-center bg-warning"></th>
                                            <th className="text-center bg-warning"></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        {bancosdata.puntosybiopagosxbancos? Object.entries(bancosdata.puntosybiopagosxbancos).map(bancos=>
                                            <tr key={bancos[0]}>
                                                <th>{bancos[0]}</th>
                                                {bancos[1]?
                                                    <>
                                                        {
                                                            bancos[1]["ingreso"]?
                                                            <>
                                                                <td className="text-right text-primary bg-primary-light">
                                                                    {bancos[1]["ingreso"]["Transferencia"]?
                                                                    <>
                                                                        {moneda(bancos[1]["ingreso"]["Transferencia"]["monto"])} 
                                                                    </>
                                                                    :null}
                                                                </td>
                                                                <td className="text-right text-primary bg-primary-light">
                                                                    {bancos[1]["ingreso"]["Transferencia"]?
                                                                    <>
                                                                        {moneda(bancos[1]["ingreso"]["Transferencia"]["monto_liquidado"])} 
                                                                    </>
                                                                    :null}
                                                                </td>


                                                                <td className="text-right text-sinapsis bg-warning-light">
                                                                    {bancos[1]["ingreso"]["PUNTO"]?
                                                                    <>
                                                                        {moneda(bancos[1]["ingreso"]["PUNTO"]["monto"])}
                                                                    </>
                                                                    :null}
                                                                </td>
                                                                <td className="text-right text-sinapsis bg-warning-light">
                                                                    {bancos[1]["ingreso"]["PUNTO"]?
                                                                    <>
                                                                        {moneda(bancos[1]["ingreso"]["PUNTO"]["monto_liquidado"])} 
                                                                    </>
                                                                    :null}
                                                                </td>


                                                                <td className="text-right text-danger bg-danger-light">
                                                                    {bancos[1]["ingreso"]["BIOPAGO"]?
                                                                    <>
                                                                        {moneda(bancos[1]["ingreso"]["BIOPAGO"]["monto"])} 
                                                                    </>
                                                                    :null}
                                                                </td>
                                                                <td className="text-right text-danger bg-danger-light">
                                                                    {bancos[1]["ingreso"]["BIOPAGO"]?
                                                                    <>
                                                                        {moneda(bancos[1]["ingreso"]["BIOPAGO"]["monto_liquidado"])} 
                                                                    </>
                                                                    :null}
                                                                </td>


                                                                <td className="text-right bg-warning">{moneda(bancos[1]["ingreso"]["monto"])}</td>
                                                                <td className="text-right bg-warning">{moneda(bancos[1]["ingreso"]["monto_liquidado"])}</td>
                                                            </>
                                                            :null
                                                        }

                                                        {
                                                            bancos[1]["egreso"]?
                                                            <>
                                                                <td className="text-right text-primary bg-primary-light">
                                                                    {bancos[1]["egreso"]["Transferencia"]?
                                                                        <>
                                                                            {moneda(bancos[1]["egreso"]["Transferencia"]["monto"])}
                                                                        </>
                                                                    :null}
                                                                </td>
                                                                <td className="text-right text-primary bg-primary-light">
                                                                    {bancos[1]["egreso"]["Transferencia"]?
                                                                        <>
                                                                            {moneda(bancos[1]["egreso"]["Transferencia"]["monto_liquidado"])} 
                                                                        </>
                                                                    :null}
                                                                </td>

                                                                <td className="text-right bg-warning">{moneda(bancos[1]["egreso"]["monto"])}</td>
                                                                <td className="text-right bg-warning">{moneda(bancos[1]["egreso"]["monto_liquidado"])}</td>
                                                            </>
                                                            :null
                                                        }
                                                    </>
                                                :console.log(bancos[1],"bancos[1]")}
                                            </tr>
                                        ):null}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </>
                :null
            }

       </div> 
    )
}