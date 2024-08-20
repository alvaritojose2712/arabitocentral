export default function Aprobapedidosanulacion({
    dataPedidoAnulacionAprobacion,
    qdesdePedidoAnulacionAprobacion,
    qhastaPedidoAnulacionAprobacion,
    qnumPedidoAnulacionAprobacion,
    qestatusPedidoAnulacionAprobacion,
    getAprobacionPedidoAnulacion,
    setAprobacionPedidoAnulacion,

    setqdesdePedidoAnulacionAprobacion,
    setqhastaPedidoAnulacionAprobacion,
    setqnumPedidoAnulacionAprobacion,
    setqestatusPedidoAnulacionAprobacion,

    sucursalPedidoAnulacionAprobacion,
    setsucursalPedidoAnulacionAprobacion,
    moneda,
    sucursales,
}){
    return (
        <div className="container-fluid">
            <form className="input-group" onSubmit={event=>{event.preventDefault();getAprobacionPedidoAnulacion()}}>
                <input type="text" className="form-control" placeholder="Buscar # Pedido..." value={qnumPedidoAnulacionAprobacion} onChange={event=>setqnumPedidoAnulacionAprobacion(event.target.value)} />
                <select className="form-control form-control-lg" value={sucursalPedidoAnulacionAprobacion} onChange={e=>setsucursalPedidoAnulacionAprobacion(e.target.value)}>
                    <option value="">-SUCURSAL-</option>
                    {sucursales.map(e=>
                        <option key={e.id} value={e.id}>{e.codigo}</option>
                    )}
                </select>

                <input type="date" className="form-control" value={qdesdePedidoAnulacionAprobacion} onChange={event=>setqdesdePedidoAnulacionAprobacion(event.target.value)} />
                <input type="date" className="form-control" value={qhastaPedidoAnulacionAprobacion} onChange={event=>setqhastaPedidoAnulacionAprobacion(event.target.value)} />

                <button className={("btn btn-"+(qestatusPedidoAnulacionAprobacion==0?"sinapsis":""))} onClick={e=>{
                    if (qestatusPedidoAnulacionAprobacion==0) {
                        getAprobacionPedidoAnulacion()
                    }
                    setqestatusPedidoAnulacionAprobacion(0)
                }}><i className="fa fa-clock-o"></i></button>
                <button className={("btn btn-"+(qestatusPedidoAnulacionAprobacion==1?"success":""))} onClick={e=>{
                    if (qestatusPedidoAnulacionAprobacion==1) {
                        getAprobacionPedidoAnulacion()
                    }
                    setqestatusPedidoAnulacionAprobacion(1)
                }}><i className="fa fa-check"></i></button>
            </form>
            <table className="table">
                <thead>
                    <tr>
                        <th></th>
                        <th>SUCURSAL</th>
                        <th># PEDIDO</th>
                        <th>MONTO</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {dataPedidoAnulacionAprobacion.map(e=>
                        <tr key={e.id} className={(!e.estatus?"bg-sinapsis-superlight":"bg-light")+" text-secondary mb-3 pointer"}>
                            <td className="text-danger text-center align-middle">
                                <i onClick={()=>setAprobacionPedidoAnulacion(e.id,"delete")} className="fa fa-times fa-2x"></i>
                            </td>
                            <th className="align-middle">
                                <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:e.sucursal.background,color:e.sucursal.color}}>
                                    {e.sucursal.codigo}
                                </button>
                            </th>
                            <th className="text-center align-middle">
                                <span className="fw-bold fs-3">
                                    {e.idincentral}
                                </span>
                            </th> 
                            <td className="text-right align-middle">
                                {
                                    e.monto!=0?
                                        <>
                                            {/* <span className="h6 text-muted font-italic">BALANCE $ <b>{moneda(e.balancedolar)}</b></span>
                                            <br/>
                                            <br/> */}
                                            <span className={"h3 text-"+(e.monto<0?"danger":"success")}>$ <b>{moneda(e.monto)}</b></span>
                                        </>
                                    :null
                                }
                            </td>
                            <td className="text-center align-middle">
                                <button className={("btn btn-sm btn-")+(e.estatus==0?"sinapsis":"success")} onClick={()=>setAprobacionPedidoAnulacion(e.id,"aprobar")}>{e.estatus==0?"APROBAR":"REVERSAR"} <i className="fa fa-check"></i></button>
                            </td>
                        </tr>
                    )}
                </tbody>
            </table>
        </div>
    )
}