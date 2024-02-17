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
    selectxMovimientos,
    movimientoAuditoria,
    setmovimientoAuditoria,
    subviewAuditoria,
    setsubviewAuditoria,

    setselectTrLiquidar,
    selectTrLiquidar,
    inpmontoLiquidar,
    setinpmontoLiquidar,
    inpfechaLiquidar,
    setinpfechaLiquidar,
    liquidarMov,
    orderAuditoria,
    setorderAuditoria,
    orderColumnAuditoria,
    setorderColumnAuditoria,
    selectConciliacion,
    saldoactualbancofecha,
    setsaldoactualbancofecha,
    sendsaldoactualbancofecha,
    selectConciliacionData,
    setselectConciliacionData,
    colorFun,
    colors,
    colorSucursal,
    reverserLiquidar,
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
        orderColumnAuditoria,
        orderAuditoria,
    ])


    const getCat = id => {
        let fil = categoriaMovBanco.filter(e=>e.id==id)
        if (fil.length) {
            return fil[0].descripcion
        }
        return "0"
    }

    const [nummm, setnummm] = useState(0)
    return (
       <div className="container-fluid">
            <div className="d-flex justify-content-center">
                <div className="btn-group m-2">
                    <button className={("btn btn-sm ")+(subviewAuditoria=="cargar"?"btn-sinapsis":"")} onClick={()=>setsubviewAuditoria("cargar")}>Cargar</button>
                    <button className={("btn btn-sm ")+(subviewAuditoria=="liquidar"?"btn-sinapsis":"")} onClick={()=>{setsubviewAuditoria("liquidar");getBancosData("liquidar")}}>Liquidar</button>
                    <button className={("btn btn-sm ")+(subviewAuditoria=="cuadre"?"btn-sinapsis":"")} onClick={()=>{setsubviewAuditoria("cuadre");getBancosData("cuadre")}}>Movimientos</button>
                    <button className={("btn btn-sm ")+(subviewAuditoria=="conciliacion"?"btn-sinapsis":"")} onClick={()=>{setsubviewAuditoria("conciliacion");getBancosData("conciliacion")}}>Cuadre</button>
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
                subviewAuditoria=="cuadre" && bancosdata.view=="cuadre"? 
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
                                <table className="table table-sm table-striped ">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th colSpan={8} className="text-center text-success">INGRESO</th>
                                            <th colSpan={4} className="text-center text-danger">EGRESO</th>
                                        </tr>

                                        <tr>
                                            <th className="text-center">BANCO</th>
                                            <th colSpan={2} className="text-right bg-primary-light pointer">  
                                                <button className="btn" style={{backgroundColor:colors["Transferencia"]}} onClick={()=>selectxMovimientos("ingreso_Transferencia", null)}>TRANSFERENCIA</button> 
                                            </th>
                                            <th colSpan={2} className="text-right bg-warning-light pointer"> <button className="btn" onClick={()=>selectxMovimientos("ingreso_PUNTO", null)} style={{backgroundColor:colors["PUNTO"]}}>PUNTO</button> </th>
                                            <th colSpan={2} className="text-right bg-danger-light pointer"> <button className="btn" onClick={()=>selectxMovimientos("ingreso_BIOPAGO", null)} style={{backgroundColor:colors["BIOPAGO"]}}>BIOPAGO</button> </th>
                                            
                                            <th className="text-right bg-success"></th>
                                            <th className="text-right bg-success"></th>

                                            <th colSpan={2} className="text-right bg-primary-light pointer"> <button className="btn" onClick={()=>selectxMovimientos("egreso_Transferencia", null)} style={{backgroundColor:colors["Transferencia"]}}>TRANSFERENCIA</button> </th>
                                            
                                            <th className="text-center bg-danger"></th>
                                            <th className="text-center bg-danger"></th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        {bancosdata.puntosybiopagosxbancos? Object.entries(bancosdata.puntosybiopagosxbancos).map(bancos=>
                                            <tr key={bancos[0]}>
                                                <th className="pointer align-middle"> 
                                                    <button className="btn w-100 fw-bolder" 
                                                    style={{
                                                        backgroundColor:colors[bancos[0]]?colors[bancos[0]][0]:"", 
                                                        color:colors[bancos[0]]?colors[bancos[0]][1]:""
                                                    }}
                                                    onClick={()=>selectxMovimientos("banco", bancos[0])}
                                                    >{bancos[0]}</button>
                                                </th>
                                                {bancos[1]?
                                                    <>
                                                        {
                                                            bancos[1]["ingreso"]?
                                                            <>
                                                                <td className="fw-bolder text-right bg-primary-light align-middle pb-1 pt-1">
                                                                    

                                                                    {bancos[1]["ingreso"]["Transferencia"]?
                                                                    <>
                                                                        <span className="text-success">
                                                                            {moneda(bancos[1]["ingreso"]["Transferencia"]["monto_liquidado"])} 
                                                                        </span>
                                                                        <hr className="m-0" />
                                                                        <span className="text-sinapsis">
                                                                            {moneda(bancos[1]["ingreso"]["Transferencia"]["monto"])} 
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                    
                                                                </td>
                                                                <td className="align-middle pb-1 pt-1 bg-primary-light">
                                                                    {bancos[1]["ingreso"]["Transferencia"]?
                                                                    <>
                                                                        <span className="text-danger">
                                                                            {moneda(bancos[1]["ingreso"]["Transferencia"]["monto_comision"])} <hr className="m-0" /> 
                                                                            ({moneda(bancos[1]["ingreso"]["Transferencia"]["porcentaje"])}%)
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                </td>


                                                                <td className="fw-bolder text-right bg-warning-light align-middle pb-1 pt-1">
                                                                    

                                                                    {bancos[1]["ingreso"]["PUNTO"]?
                                                                    <>
                                                                        <span className="text-success">
                                                                            {moneda(bancos[1]["ingreso"]["PUNTO"]["monto_liquidado"])} 
                                                                        </span>
                                                                        <hr className="m-0" />
                                                                        <span className="text-sinapsis">
                                                                            {moneda(bancos[1]["ingreso"]["PUNTO"]["monto"])} 
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                    
                                                                </td>
                                                                <td className="align-middle pb-1 pt-1 bg-warning-light">
                                                                    {bancos[1]["ingreso"]["PUNTO"]?
                                                                    <>
                                                                        <span className="text-danger">
                                                                            {moneda(bancos[1]["ingreso"]["PUNTO"]["monto_comision"])} <hr className="m-0" /> 
                                                                            ({moneda(bancos[1]["ingreso"]["PUNTO"]["porcentaje"])}%)
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                </td>


                                                                <td className="fw-bolder text-right bg-danger-light align-middle pb-1 pt-1">
                                                                    

                                                                    {bancos[1]["ingreso"]["BIOPAGO"]?
                                                                    <>
                                                                        <span className="text-success">
                                                                            {moneda(bancos[1]["ingreso"]["BIOPAGO"]["monto_liquidado"])} 
                                                                        </span>
                                                                        <hr className="m-0" />
                                                                        <span className="text-sinapsis">
                                                                            {moneda(bancos[1]["ingreso"]["BIOPAGO"]["monto"])} 
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                    
                                                                </td>
                                                                <td className="align-middle pb-1 pt-1 bg-danger-light">
                                                                    {bancos[1]["ingreso"]["BIOPAGO"]?
                                                                    <>
                                                                        <span className="text-danger">
                                                                            {moneda(bancos[1]["ingreso"]["BIOPAGO"]["monto_comision"])} <hr className="m-0" /> 
                                                                            ({moneda(bancos[1]["ingreso"]["BIOPAGO"]["porcentaje"])}%)
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                </td>

                                                                <td className="fw-bolder text-right bg-success-superlight align-middle pb-1 pt-1 fs-4">
                                                                    

                                                                    {bancos[1]["ingreso"]?
                                                                    <>
                                                                        <span className="text-success">
                                                                            {moneda(bancos[1]["ingreso"]["monto_liquidado"])} 
                                                                        </span>
                                                                        <hr className="m-0" />
                                                                        <span className="text-sinapsis">
                                                                            {moneda(bancos[1]["ingreso"]["monto"])} 
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                    
                                                                </td>
                                                                <td className="align-middle pb-1 pt-1 bg-success-superlight">
                                                                    
                                                                    {bancos[1]["ingreso"]?
                                                                    <>
                                                                        <span className="text-danger">
                                                                            {moneda(bancos[1]["ingreso"]["monto_comision"])} <hr className="m-0" /> 
                                                                            ({moneda(bancos[1]["ingreso"]["porcentaje"])}%)
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                </td>

                                                            </>
                                                            :null
                                                        }

                                                        {
                                                            bancos[1]["egreso"]?
                                                            <>
                                                                <td className="fw-bolder text-right bg-primary-light align-middle pb-1 pt-1">
                                                                    

                                                                    {bancos[1]["egreso"]["Transferencia"]?
                                                                    <>
                                                                        <span className="text-success">
                                                                            {moneda(bancos[1]["egreso"]["Transferencia"]["monto_liquidado"])} 
                                                                        </span>
                                                                        <hr className="m-0" />
                                                                        <span className="text-sinapsis">
                                                                            {moneda(bancos[1]["egreso"]["Transferencia"]["monto"])} 
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                    
                                                                </td>
                                                                <td className="align-middle pb-1 pt-1 bg-primary-light">
                                                                    {bancos[1]["egreso"]["Transferencia"]?
                                                                    <>
                                                                        <span className="text-danger">
                                                                            {moneda(bancos[1]["egreso"]["Transferencia"]["monto_comision"])} <hr className="m-0" /> 
                                                                            ({moneda(bancos[1]["egreso"]["Transferencia"]["porcentaje"])}%)
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                </td>

                                                                <td className="fw-bolder text-right bg-danger-superlight align-middle pb-1 pt-1 fs-4">
                                                                    

                                                                    {bancos[1]["egreso"]?
                                                                    <>
                                                                        <span className="text-success">
                                                                            {moneda(bancos[1]["egreso"]["monto_liquidado"])} 
                                                                        </span>
                                                                        <hr className="m-0" />
                                                                        <span className="text-sinapsis">
                                                                            {moneda(bancos[1]["egreso"]["monto"])} 
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                    
                                                                </td>
                                                                <td className="align-middle pb-1 pt-1 bg-danger-superlight">
                                                                    {bancos[1]["egreso"]?
                                                                    <>
                                                                        <span className="text-danger">
                                                                            {moneda(bancos[1]["egreso"]["monto_comision"])} <hr className="m-0" /> 
                                                                            ({moneda(bancos[1]["egreso"]["porcentaje"])}%)
                                                                        </span>
                                                                    </>
                                                                    :null}
                                                                </td>
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
                        <div className="row">
                            {movimientoAuditoria.length?
                                <div className="col">
                                    <table className="table">
                                        <thead>
                                            <tr>
                                                <th onClick={()=>{if(orderColumnAuditoria=="banco"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("banco")}} className="pointer">BANCO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="tipo"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("tipo")}} className="pointer">MÉTODO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="fecha"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha")}} className="pointer">FECHA REPORTADO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="fecha_liquidacion"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha_liquidacion")}} className="pointer">FECHA LIQUIDADO</th>
                                                <th>TIPO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="id_sucursal"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("id_sucursal")}} className="pointer">SUCURSAL</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="loteserial"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("loteserial")}} className="pointer">LOTE / REFERENCIA</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="categoria"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("categoria")}} className="pointer">CATEGORÍA</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="monto"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto")}} className="pointer">MONTO BRUTO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="monto_liquidado"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto_liquidado")}} className="pointer">LIQUIDADO</th>
                                                <th>COMISIÓN</th>
                                                <th>%</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        {movimientoAuditoria.map(e=>
                                            <tr key={e.id}>
                                                <th>
                                                    <button className="btn w-100 fw-bolder" 
                                                    style={{
                                                        backgroundColor:colors[e.banco]?colors[e.banco][0]:"", 
                                                        color:colors[e.banco]?colors[e.banco][1]:""
                                                    }}>{e.banco}</button>
                                                        
                                                </th>
                                                <th>
                                                    <button className="btn w-100 fw-bolder" 
                                                    style={{backgroundColor:colors[e.tipo]}}>{e.tipo}</button> 
                                                </th>
                                                <th>{e.fecha}</th>
                                                <th>{e.fecha_liquidacion}</th>
                                                <th> <button className={("btn w-100 ")+(e.monto<0?"btn-danger":"btn-success")}>{e.monto<0?"EGRESO":"INGRESO"}</button> </th>
                                                <th>
                                                    <button className={"btn w-100 fw-bolder "} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>{e.sucursal.codigo}</button>
                                                </th>
                                                <th>{e.loteserial}</th>
                                                <th>{getCat(e.categoria)}</th>
                                                <th>{moneda(e.monto)}</th>
                                                <th>{moneda(e.monto_liquidado)}</th>
                                                <th className="text-danger">{moneda(e.monto_comision)}</th>
                                                <th className="text-muted">{moneda(e.porcentaje)}%</th>
                                                <th><button className="btn btn-danger" onClick={()=>reverserLiquidar(e.id)}><i className="fa fa-arrow-left"></i></button></th>
                                            </tr>
                                        )}
                                        </tbody>
                                    </table>
                                </div>
                            :null}
                        </div>
                    </>
                :null
            }

            {
                subviewAuditoria=="liquidar" && bancosdata.view=="liquidar"? 
                    <>
                       <div className="form-group">
                            <div className="input-group">
                                <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={event=>setfechaSelectAuditoria(event.target.value)}/>    
                                <input type="date" className="form-control" value={fechaHastaSelectAuditoria} onChange={event=>setfechaHastaSelectAuditoria(event.target.value)}/>    
                                <button className="btn btn-success" onClick={()=>getBancosData()}><i className="fa fa-search"></i></button>
                            
                            </div>
                        </div> 

                        <table className="table">
                            <thead>
                                <tr>
                                    <th onClick={()=>{if(orderColumnAuditoria=="banco"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("banco")}} className="pointer">BANCO</th>
                                    <th onClick={()=>{if(orderColumnAuditoria=="tipo"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("tipo")}} className="pointer">MÉTODO</th>
                                    <th onClick={()=>{if(orderColumnAuditoria=="fecha"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha")}} className="pointer">FECHA REPORTADO</th>
                                    <th onClick={()=>{if(orderColumnAuditoria=="fecha_liquidacion"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha_liquidacion")}} className="pointer">FECHA LIQUIDADO</th>
                                    <th>TIPO</th>
                                    <th onClick={()=>{if(orderColumnAuditoria=="id_sucursal"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("id_sucursal")}} className="pointer">SUCURSAL</th>
                                    <th onClick={()=>{if(orderColumnAuditoria=="loteserial"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("loteserial")}} className="pointer">LOTE / REFERENCIA</th>
                                    <th onClick={()=>{if(orderColumnAuditoria=="categoria"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("categoria")}} className="pointer">CATEGORÍA</th>
                                    <th onClick={()=>{if(orderColumnAuditoria=="monto"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto")}} className="pointer">MONTO BRUTO</th>
                                    <th onClick={()=>{if(orderColumnAuditoria=="monto_liquidado"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto_liquidado")}} className="pointer">LIQUIDADO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {bancosdata.xliquidar.map((e,i)=>
                                    <tr key={e.id} onClick={()=>setselectTrLiquidar(i)}>
                                        <th>
                                            <button className="btn w-100 fw-bolder" 
                                            style={{
                                                backgroundColor:colors[e.banco]?colors[e.banco][0]:"", 
                                                color:colors[e.banco]?colors[e.banco][1]:""
                                            }}>{e.banco}</button>
                                            
                                        </th>
                                        <th>
                                            <button className="btn w-100 fw-bolder" 
                                            style={{backgroundColor:colors[e.tipo]}}>{e.tipo}</button> 
                                        </th>
                                        <th>{e.fecha}</th>
                                        <th>{e.fecha_liquidacion}</th>
                                        <th> <button className={("btn w-100 ")+(e.monto<0?"btn-danger":"btn-success")}>{e.monto<0?"EGRESO":"INGRESO"}</button> </th>
                                        <th> 
                                            <button className="btn w-100 fw-bolder" style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>{e.sucursal.codigo}</button>
                                        </th>
                                        <th>{e.loteserial}</th>
                                        <th>
                                            {getCat(e.categoria)}
                                        </th>
                                        <th>{moneda(e.monto)}</th>
                                        <th>{moneda(e.monto_liquidado)}</th>
                                        <th>
                                            {selectTrLiquidar===i?
                                                <div className="input-group-vertical">
                                                    <input type="text" className="form-control" value={inpmontoLiquidar} placeholder="Monto Liquidado" onChange={event=>setinpmontoLiquidar(event.target.value)}/>
                                                    <input type="date" className="form-control" value={inpfechaLiquidar} onChange={event=>setinpfechaLiquidar(event.target.value)}/>
                                                    <button className="btn btn-warning w-100" onClick={()=>liquidarMov(e.id)}>LIQUIDAR <i className="fa fa-send"></i></button>
                                                </div>
                                            :null}
                                        </th>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </>
                :null
            }

            {
                subviewAuditoria=="conciliacion" && bancosdata.view=="conciliacion"? 
                    <>
                        <div className="form-group">
                            <div className="input-group">
                                <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={event=>setfechaSelectAuditoria(event.target.value)}/>    
                                <input type="date" className="form-control" value={fechaHastaSelectAuditoria} onChange={event=>setfechaHastaSelectAuditoria(event.target.value)}/>    
                                <button className="btn btn-success" onClick={()=>getBancosData()}><i className="fa fa-search"></i></button>
                            
                            </div>
                        </div> 
                        <table className="table">
                            <thead>
                                <tr>
                                    <th>BANCO</th>
                                    <th>FECHA</th>
                                    <th className="bg-success-light">CUADRE REAL</th>
                                    <th>SALDO INCIAL</th>
                                    <th>INGRESO</th>
                                    <th>EGRESO</th>
                                    <th className="bg-success-light">CUADRE DIGITAL</th>
                                    <th>CONCILIACIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                {bancosdata.xfechaCuadre.map((e,i)=>
                                    <tr key={i} onClick={()=>selectConciliacion(e.banco,e.fecha)}>
                                        <th>
                                            <button className="btn w-100 fw-bolder" 
                                                style={{
                                                    backgroundColor:colors[e.banco]?colors[e.banco][0]:"", 
                                                    color:colors[e.banco]?colors[e.banco][1]:""
                                                }}
                                                onClick={()=>selectxMovimientos("banco", e.banco)}
                                                >{e.banco}</button>
                                        
                                        </th>
                                        <th>{e.fecha}</th>
                                        <th className="bg-success-light">
                                            {selectConciliacionData == e.banco+"-"+e.fecha?
                                                <div className="input-group">
                                                    
                                                    <input type="text" placeholder="Saldo ACTUAL" size={5} className="form-control" value={saldoactualbancofecha} onChange={event=>setsaldoactualbancofecha(event.target.value)} />
                                                    <button className="btn btn-warning" onClick={()=>sendsaldoactualbancofecha(e.banco,e.fecha)}><i className="fa fa-send"></i></button>
                                                </div>
                                            :e.guardado?moneda(e.guardado.saldo):"----"
                                            }
                                        </th>
                                        <th className="bg-warning-light">{moneda(e.inicial)}</th>
                                        <th className="bg-success-light">{moneda(e.ingreso)}</th>
                                        <th className="bg-danger-light">{moneda(e.egreso)}</th>
                                        <th className="bg-success-light">{moneda(e.balance)}</th>
                                        <th className={(e.cuadre==0?"bg-success text-light":"bg-danger text-light")+" fs-3 text-right"}>{moneda(e.cuadre)}</th>

                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </>
                :null
            }

       </div> 
    )
}