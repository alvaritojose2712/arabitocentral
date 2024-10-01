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
                        <th>ID</th>
                        <th>SUCURSAL</th>
                        <th>FECHA</th>
                        <th>TIPO</th>
                        <th>ANTES</th>
                        <th>DESPUES</th>
                    </tr>
                </thead>
                <tbody>
                    {tareasPendientesData.data?
                        tareasPendientesData.data.map(e=>
                            <tr key={e.id}>
                                <td>{e.id}</td>
                                <td>{e.sucursal.codigo}</td>
                                <td>{e.created_at}</td>
                                <td>
                                    {e.tipo==1?"MODIFICAR":null}
                                    {e.tipo==2?"ELIMINAR DUPLICADOS":null}
                                </td>
                                <td>
                                    {e.antesproducto?
                                        <table className="table">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <b>CB:</b> {e.prodantesproducto.codigo_barras} <br />
                                                        <b>CA:</b> {e.prodantesproducto.codigo_alterno}
                                                    </td>
                                                    <td>{e.prodantesproducto.descripcion}</td>
                                                    <td>{e.prodantesproducto.cantidad}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    :null}


                                    {e.id_producto_verde?e.id_producto_verde:null}
                                </td>
                                <td>
                                    {e.id_producto_rojo?e.id_producto_rojo:null} 

                                    {e.cambiarproducto?
                                        <table className="table">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <b>CB:</b> {e.prodcambiarproducto.codigo_barras} <br />
                                                        <b>CA:</b> {e.prodcambiarproducto.codigo_alterno}
                                                    </td>
                                                    <td>{e.prodcambiarproducto.descripcion}</td>
                                                    <td>{e.prodcambiarproducto.cantidad}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    :null}
                                </td>
                                <td>
                                    <button className={("btn btn-"+(qTareaPendienteEstado==0?"sinapsis":"success"))}>
                                        {qTareaPendienteEstado==0?
                                            <i className="fa fa-clock-o"></i>
                                            :
                                            <i className="fa fa-check"></i>
                                        }
                                    </button>
                                </td>
                            </tr>
                    ):null}
                </tbody>
            </table>
        </div>
    )
}