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

                <input type="date" className="form-control" value={qdesdePedidoAnulacionAprobacion} onChange={event=>setqdesdePedidoAnulacionAprobacion(event.target.value)} />
                <input type="date" className="form-control" value={qhastaPedidoAnulacionAprobacion} onChange={event=>setqhastaPedidoAnulacionAprobacion(event.target.value)} />
                <select className="form-control form-control-lg" value={sucursalPedidoAnulacionAprobacion} onChange={e=>setsucursalPedidoAnulacionAprobacion(e.target.value)}>
                    <option value="">-SUCURSAL-</option>
                    {sucursales.map(e=>
                        <option key={e.id} value={e.id}>{e.codigo}</option>
                    )}
                </select>

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
                        <th className="text-center"></th>
                        <th className="text-center">SUCURSAL</th>
                        <th className="text-center"># PEDIDO</th>
                        <th className="text-center">MOTIVO</th>
                        <th className="text-right">MONTO</th>
                        <th className="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    {dataPedidoAnulacionAprobacion.map(e=>
                        <tr key={e.id} className={(!e.estatus?"bg-sinapsis-superlight":"bg-light")+" text-secondary mb-3 pointer"}>
                            <td className="text-danger text-center align-middle">
                                <i onClick={()=>setAprobacionPedidoAnulacion(e.id,"delete")} className="fa fa-times fa-2x"></i>
                            </td>
                            <th className="align-middle text-center">
                                <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:e.sucursal.background,color:e.sucursal.color}}>
                                    {e.sucursal.codigo}
                                </button>
                            </th>
                            <th className="text-center align-middle">
                                <span className="fw-bold fs-3">
                                    {e.idinsucursal}
                                </span>
                            </th> 
                            <th className="fst-italic text-center align-middle fs-4">
                                {e.motivo}
                            </th>
                            <td className="align-middle">
                                <div className="text-right">
                                    {
                                        e.monto!=0?
                                            <span className={"h3 text-"+(e.monto<0?"danger":"success")}>$ <b>{moneda(e.monto)}</b></span>
                                        :null
                                    }
                                </div>
                                <hr />
                                <div className="card p-2">
                                    <table className="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>BARRAS</th>
                                                <th>DESCRIPCION</th>
                                                <th>CT</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {JSON.parse(e.items)?JSON.parse(e.items).map(ee=>
                                                <tr className="" key={ee.id}>
                                                    <td>{ee.id}</td>
                                                    <td>{ee.barras}</td>
                                                    <td className="text-sinapsis">{ee.desc}</td>
                                                    <td>{ee.ct}</td>
                                                    <td className="text-right text-success">{moneda(ee.m)}</td>
                                                    {/* <td>{ee.alterno}</td> */}
                                                </tr>
                                            ):null}


                                            <tr>
                                                <th colSpan={3} className="fs-3">PAGO</th>
                                                <th colSpan={2} className="fs-3">MONTO</th>
                                            </tr>
                                            {JSON.parse(e.pagos)?JSON.parse(e.pagos).map(ee=>
                                                <tr className="" key={ee.id}>
                                                    <td colSpan={3}>
                                                        <span className="fs-2" key={ee.id}>
                                                            {ee.tipo == 1 ?
                                                                <span className="btn btn-info btn-sm">Trans. {ee.m}</span>
                                                                : null}

                                                            {ee.tipo == 2 ?
                                                                <span className="btn btn-secondary btn-sm">Deb. {ee.m}</span>
                                                                : null}

                                                            {ee.tipo == 3 ?
                                                                <span className="btn btn-success btn-sm">Efec. {ee.m}</span>
                                                                : null}

                                                            {ee.tipo == 4 ?
                                                                <span className="btn btn-sinapsis btn-sm">Cred. {ee.m}</span>
                                                                : null}

                                                            {ee.tipo == 5 ?
                                                                <span className="btn btn-primary btn-sm">Biopago {ee.m}</span>
                                                                : null}

                                                            {ee.tipo == 6 ?
                                                                <span className="btn btn-danger btn-sm">Vuel. {ee.m}</span>
                                                                : null}
                                                        </span>
                                                    </td>
                                                    <td colSpan={2}>{moneda(ee.m)}</td>
                                                </tr>
                                            ):null}

                                            <tr>
                                                <th colSpan={5} className="fs-3 text-center">CLIENTE</th>
                                            </tr>
                                            {JSON.parse(e.cliente)?[JSON.parse(e.cliente)].map(ee=>
                                                <tr className="" key={ee.id}>
                                                    <td colSpan={3} className="">
                                                        {ee.nombre}
                                                    </td>
                                                    <td colSpan={2}>{ee.identificacion}</td>
                                                </tr>
                                            ):null}
                                        </tbody>
                                    </table>
                                </div>
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