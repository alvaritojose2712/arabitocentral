export default function Tareassucursalespendientes({
    sucursales,
    setqTareaPendienteFecha,
    qTareaPendienteFecha,
    qTareaPendienteSucursal,
    setqTareaPendienteSucursal,
    getTareasPendientes,
    tareasPendientesData,
    qTareaPendienteEstado,
    setqTareaPendienteEstado,
    qTareaPendienteNum,
    setqTareaPendienteNum,
    aprobarPermisoModDici,
    delTareaPendiente,
}){
    return (
        <div className="container-fluid">

            <form className="input-group mb-3" onSubmit={event=>{event.preventDefault(); getTareasPendientes()}}>
                <input type="date"className="form-control" onChange={e => setqTareaPendienteFecha(e.target.value)} value={qTareaPendienteFecha} />
                
                <select className="form-control" value={qTareaPendienteSucursal} onChange={event=>setqTareaPendienteSucursal(event.target.value)}>
                    <option value={""}>-SUCURSAL-</option>
                    {sucursales.map(e=>
                        <option key={e.id} value={e.id}>{e.codigo}</option>
                    )}
                </select>

                <select className="form-control" value={qTareaPendienteNum} onChange={event=>setqTareaPendienteNum(event.target.value)}>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                    <option value="1000">1000</option>
                </select>

                <div className="input-group-prepend">
                    <button className={("btn btn-"+(qTareaPendienteEstado==0?"sinapsis":""))} onClick={e=>{
                        if (qTareaPendienteEstado==0) {
                            getTareasPendientes()
                        }
                        setqTareaPendienteEstado(0)
                    }}><i className="fa fa-clock-o"></i></button>
                    <button className={("btn btn-"+(qTareaPendienteEstado==1?"success":""))} onClick={e=>{
                        if (qTareaPendienteEstado==1) {
                            getTareasPendientes()
                        }
                        setqTareaPendienteEstado(1)
                    }}><i className="fa fa-check"></i></button>
                </div>

                

            </form>

            <table className="table">
                <thead>
                    <tr>
                        <th>PERMISO</th>
                        <th>SUCURSAL</th>
                        <th>FECHA</th>
                        <th>TIPO</th>
                        <th>VERDE</th>
                        <th>ROJO</th>

                        <th>BARRAS</th>
                        <th>ALTERNO</th>
                        <th>DESCRIPCIÓN</th>
                        <th>CT</th>
                        <th>BASE</th>
                        <th>VENTA</th>
                    </tr>
                </thead>
                    {tareasPendientesData.data?
                        tareasPendientesData.data.map(e=>
                            <tbody key={e.id}>
                                <tr>
                                    <td rowSpan={2}>{e.permiso==1?<i className="fa fa-check text-success"></i>: <i className="fa fa-times text-danger"></i>}</td>
                                    <td rowSpan={2}>{e.sucursal.codigo}</td>
                                    <td rowSpan={2}>{e.created_at}</td>
                                    <td rowSpan={2}>
                                        {e.tipo==1?"MODIFICAR":null}
                                        {e.tipo==2?"ELIMINAR DUPLICADOS":null}
                                    </td>
                                    <td rowSpan={2}>
                                        {e.id_producto_verde?e.id_producto_verde:null}
                                    </td>
                                    <td rowSpan={2}>
                                        {e.id_producto_rojo?e.id_producto_rojo:null} 

                                        
                                    </td>
                                   
                                    {e.cambiarproducto?
                                        <>
                                            <td className="bg-danger-light">{e.prodantesproducto?e.prodantesproducto.codigo_barras:null}</td>
                                            <td className="bg-danger-light">{e.prodantesproducto?e.prodantesproducto.codigo_proveedor:null}</td>
                                            <td className="bg-danger-light">{e.prodantesproducto?e.prodantesproducto.descripcion:null}</td>
                                            <td className="bg-danger-light">{e.prodantesproducto?e.prodantesproducto.cantidad:null}</td>
                                            <td className="bg-danger-light">{e.prodantesproducto?e.prodantesproducto.precio_base:null}</td>
                                            <td className="bg-danger-light">{e.prodantesproducto?e.prodantesproducto.precio:null}</td>
                                        </>
                                    :null}
                                    <th rowSpan={2}>
                                        <button className={("btn btn-"+(qTareaPendienteEstado==0?"sinapsis":"success"))}>
                                            {qTareaPendienteEstado==0?
                                                <i className="fa fa-clock-o"></i>
                                                :
                                                <i className="fa fa-check"></i>
                                            }
                                        </button>

                                        {e.estado==0?
                                            <button className={(" btn btn-")+(e.estado==0?"btn-success":"btn-sinapsis")} onDoubleClick={()=>aprobarPermisoModDici(e.id,(e.estado==0?1:0))}>
                                                {e.estado==0?"APROBAR MODIFICACIÓN":"RECHAZAR"}
                                            </button>
                                        :null}
                                    </th>
                                    <th rowSpan={2}>
                                        {e.estado==0?
                                            <button className="btn btn-danger" onDoubleClick={()=>delTareaPendiente(e.id)}>ELIMINAR <i className="fa fa-times"></i></button>
                                        :null}
                                    </th>
                                </tr>

                                {e.antesproducto?
                                    <tr className="bg-success-light">
                                        <td>{e.prodcambiarproducto.codigo_barras}</td>
                                        <td>{e.prodcambiarproducto.codigo_proveedor}</td>
                                        <td>{e.prodcambiarproducto.descripcion}</td>
                                        <td>{e.prodcambiarproducto.cantidad}</td>
                                        <td>{e.prodcambiarproducto.precio_base}</td>
                                        <td>{e.prodcambiarproducto.precio}</td>
                                    </tr>
                                :null}
                            </tbody>

                    ):null}
            </table>
        </div>
    )
}