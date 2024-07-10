export default function AuditoriaEfectivo({
    getAuditoriaEfec,
    qauditoriaefectivo,
    setqauditoriaefectivo,
    sucursalqauditoriaefectivo,
    setsucursalqauditoriaefectivo,
    sucursales,
    fechadesdeauditoriaefec,
    setfechadesdeauditoriaefec,
    fechahastaauditoriaefec,
    setfechahastaauditoriaefec,
    setqcajaauditoriaefectivo,
    qcajaauditoriaefectivo,
    dataAuditoriaEfectivo,
    colorsGastosCat,
    moneda,
    
}){
    return (
        <div className="container-fluid">
            <div className="d-flex justify-content-center">
                <div className="form-group w-50">
                    <form className="input-group" onSubmit={event=>{event.preventDefault();getAuditoriaEfec()}}>
                        <select className="form-control" onChange={event=>setqcajaauditoriaefectivo(event.target.value)} value={qcajaauditoriaefectivo}>
                            <option value="1">CAJA FUERTE</option>
                            <option value="0">CAJA CHICA</option>
                        </select>
                        <input type="text" className="form-control" placeholder="Buscar..." value={qauditoriaefectivo} onChange={e=>setqauditoriaefectivo(e.target.value)} />
                        <select className="form-control form-control-lg" value={sucursalqauditoriaefectivo} onChange={e=>setsucursalqauditoriaefectivo(e.target.value)}>
                            <option value="">-SUCURSAL-</option>
                            {sucursales.map(e=>
                                <option key={e.id} value={e.id}>{e.codigo}</option>
                            )}
                        </select>
                        <input type="date" className="form-control" value={fechadesdeauditoriaefec} onChange={e=>setfechadesdeauditoriaefec(e.target.value)}  />
                        <input type="date" className="form-control" value={fechahastaauditoriaefec} onChange={e=>setfechahastaauditoriaefec(e.target.value)}  />
                        <button className="btn btn-success"><i className="fa fa-search"></i></button>
                    </form>
                </div>
            </div>

            <table className="table">
                <thead>
                    <tr>
                        <th>CAJA</th>
                        <th>SUCURSAL</th>
                        <th>FECHA</th>
                        <th>CATEGORÍA</th>
                        <th>CAT. GENERAL</th>
                        <th>INGRESO/EGRESO</th>
                        <th>DESCRIPCION</th>
                        <th className="text-right">Monto DOLAR</th>
                        <th className="bg-info">Balance DOLAR</th>
                        
                        <th className="bg-success-1">Balance DOLAR AUDITORIA</th>
                        

                        <th className="text-right">Monto BS</th>
                        <th className="bg-info">Balance BS</th>
                        
                        <th className="bg-success-1">Balance BS AUDITORIA</th>
                        

                        <th className="text-right">Monto PESO</th>
                        <th className="bg-info">Balance PESO</th>
                        
                        <th className="bg-success-1">Balance PESO AUDITORIA</th>
                        

                        <th className="text-right">Monto EURO</th>
                        <th className="bg-info">Balance EURO</th>
                        
                        <th className="bg-success-1">Balance EURO AUDITORIA</th>
                        <th></th>

                        <th className="bg-primary">Balance TOTAL POR SISTEMA</th>
                        <th className="bg-warning">Balance TOTAL POR SUMA REAL</th>
                        <th className="bg-sinapsis">Balance TOTAL POR VENTA</th>
                        <th className="bg-dark">CUADRE</th>
                        
                        
                    </tr>
                </thead>
                <tbody>
                    {dataAuditoriaEfectivo.data?
                        dataAuditoriaEfectivo.data.map(e=>

                            <tr key={e.id}>
                                <td>
                                    {e.tipo==0?
                                        <button className="btn btn-sinapsis">CAJA CHICA</button>
                                    :
                                        <button className="btn btn-success">CAJA FUERTE</button>
                                    }
                                </td>
                                <td>{e.sucursal.codigo}</td>
                                <td className=""><small className="text-muted">{e.fecha}</small></td>

                                <td> 
                                    {e.cat?
                                        <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.id,"cat","color")}}>
                                            {colorsGastosCat(e.cat.id,"cat","desc")}
                                        </button>
                                    :null}
                                </td>
                                <td> 
                                    {e.cat?
                                        <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.catgeneral,"catgeneral","color")}}>
                                            {colorsGastosCat(e.cat.catgeneral,"catgeneral","desc")}
                                        </button>
                                    :null}
                                </td>
                                <td> 
                                    {e.cat?
                                        <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.ingreso_egreso,"ingreso_egreso","color")}}>
                                            {colorsGastosCat(e.cat.ingreso_egreso,"ingreso_egreso","desc")}
                                        </button>
                                    :null}
                                </td>


                                <td className="">{e.concepto}</td>
                                
                                <td className={(e.montodolar<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montodolar)}</td>
                                <td className={("bg-info")}>{moneda(e.dolarbalance)}</td>
                                <th className={("bg-")+(e.dolarbalance_real==e.dolarbalance?"success":"danger")}>{moneda(e.dolarbalance_real)}</th>
                                
                                <td className={(e.montobs<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montobs)}</td>
                                <td className={("bg-info")}>{moneda(e.bsbalance)}</td>
                                <th className={("bg-")+(e.bsbalance_real==e.bsbalance?"success":"danger")}>{moneda(e.bsbalance_real)}</th>
                                
                                <td className={(e.montopeso<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montopeso)}</td>
                                <td className={("bg-info")}>{moneda(e.pesobalance)}</td>
                                <th className={("bg-")+(e.pesobalance_real==e.pesobalance?"success":"danger")}>{moneda(e.pesobalance_real)}</th>

                                <td className={(e.montoeuro<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montoeuro)}</td>
                                <td className={("bg-info")}>{moneda(e.eurobalance)}</td>
                                <th className={("bg-")+(e.eurobalance_real==e.eurobalance?"success":"danger")}>{moneda(e.eurobalance_real)}</th>
                                <th></th>

                                <th className="bg-primary">{moneda(e.sumasistema)}</th>
                                <th className="bg-warning">{moneda(e.sumbruta)}</th>
                                <th className="bg-sinapsis">{moneda(e.debestener)}</th>
                                <th className={("bg-")+(e.cuadre==0?"success":"danger")}>{moneda(e.cuadre)}</th>
                                
                            </tr>
                        )
                    :null}
                </tbody>
            </table>
        </div>
    )
}