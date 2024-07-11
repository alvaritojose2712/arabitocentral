export default function CajaMatriz({
    datacajamatriz,
    colorsGastosCat,
    moneda,
    depositarmatrizalbanco,
    getCajaMatriz,
    qcajamatriz,
    setqcajamatriz,
    sucursalqcajamatriz,
    setsucursalqcajamatriz,
    fechadesdecajamatriz,
    setfechadesdecajamatriz,
    fechahastacajamatriz,
    setfechahastacajamatriz,
    sucursales,

    opcionesMetodosPago,

    bancodepositobanco,
    setbancodepositobanco,
    fechadepositobanco,
    setfechadepositobanco,
    selectdepositobanco,
    setselectdepositobanco,
}){
    return (
        <div className="container-fluid">
            <form className="input-group" onSubmit={event=>{event.preventDefault();getCajaMatriz()}}>
                <input type="text" className="form-control" placeholder="Buscar..." value={qcajamatriz} onChange={e=>setqcajamatriz(e.target.value)} />
                <select className="form-control form-control-lg" value={sucursalqcajamatriz} onChange={e=>setsucursalqcajamatriz(e.target.value)}>
                    <option value="">-SUCURSAL-</option>
                    {sucursales.map(e=>
                        <option key={e.id} value={e.id}>{e.codigo}</option>
                    )}
                </select>
                <input type="date" className="form-control" value={fechadesdecajamatriz} onChange={e=>setfechadesdecajamatriz(e.target.value)}  />
                <input type="date" className="form-control" value={fechahastacajamatriz} onChange={e=>setfechahastacajamatriz(e.target.value)}  />
                <button className="btn btn-success"><i className="fa fa-search"></i></button>
            </form>
            <table className="table">
                <thead>
                     <tr>
                        <th>SUCURSAL</th>
                        <th>FECHA</th>
                        <th>CAT GENERAL</th>
                        <th className="w-20">CATEGORÍA</th>
                        <th>DESCRIPCIÓN</th>

                        <th className="text-right">
                            Monto DOLAR
                            <hr />
                            <span className="fs-4 text-success">{datacajamatriz.balance?moneda(datacajamatriz.balance.bs):null}</span>
                            
                        </th>
                        <th className="text-right">
                            Monto BS
                            <hr />
                            <span className="fs-4 text-success">{datacajamatriz.balance?moneda(datacajamatriz.balance.cop):null}</span>
                            
                        </th>
                        <th className="text-right">
                            Monto PESO
                            <hr />
                            <span className="fs-4 text-success">{datacajamatriz.balance?moneda(datacajamatriz.balance.dolar):null}</span>
                            
                        </th>
                        <th className="text-right">
                            Monto EURO
                            <hr />
                            <span className="fs-4 text-success">{datacajamatriz.balance?moneda(datacajamatriz.balance.euro):null}</span>
                            
                        </th>

                        <th></th>
                    </tr>
                </thead>
                <tbody>

                    {datacajamatriz.data?
                        datacajamatriz.data.map(e=>
                            <tr key={e.id}>
                                <td>{e.sucursal?e.sucursal.codigo:null}</td>
                                <td className=""><small className="text-muted">{e.created_at}</small></td>
                                <td className="">
                                    <button className="btn w-100 btn-sm" 
                                        style={{color:"black",fontWeight:"bold",backgroundColor:colorsGastosCat(e.categoria,"cat","color")}}>
                                            {colorsGastosCat(e.categoria,"cat","desc")}
                                    </button>
                                </td>
                                <td className="w-20">{e.cat.nombre}</td>
                                <td className="">{e.concepto}</td>
                                
                                <td className={(e.montodolar<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montodolar)}</td>
                                
                                <td className={(e.montobs<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montobs)}</td>
                                
                                <td className={(e.montopeso<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montopeso)}</td>

                                <td className={(e.montoeuro<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montoeuro)}</td>
                                <td>

                                    {selectdepositobanco==e.id?
                                        <div className="input-group">
                                            <select className="form-control" value={bancodepositobanco}  onChange={event=>setbancodepositobanco(event.target.value)}>
                                                <option value="">-BANCO-</option>
                                                {opcionesMetodosPago.map(e=>
                                                    <option key={e.id} value={e.id}>{e.codigo}</option>
                                                )}
                                            </select>
                                            <input type="date" className="form-control" value={fechadepositobanco}  onChange={event=>setfechadepositobanco(event.target.value)} />
                                            <button className="btn btn-sinapsis" onClick={()=>depositarmatrizalbanco(e.id)}> <i className="fa fa-paper-plane"></i> </button>
                                        </div>
                                    :
                                    <button className="btn btn-sinapsis" onClick={()=>setselectdepositobanco(e.id)}>Depositar al Banco <i className="fa fa-arrow-right"></i></button>

                                }
                                
                                
                                </td>
                            </tr>
                        )
                    :null}
                </tbody>

            </table>
        </div>
    )
}