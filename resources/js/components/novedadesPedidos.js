export default function NovedadesPedidos({
    qnovedadesPedidodos,
    setqnovedadesPedidodos,
    novedadesPedidosData,
    getNovedadesPedidosData,
}){
    return(
        <div className="container">
            <form className="input-group" onSubmit={event=>{event.preventDefault();getNovedadesPedidosData()}}>
                <input type="text" className="form-control" value={qnovedadesPedidodos} onChange={event=>setqnovedadesPedidodos(event.target.value)} />
                <button className="btn btn-success"><i className="fa fa-search"></i></button>
            </form>
            <table className="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>tipo</th>
                        <th>num</th>
                        <th>id_proveedor</th>
                        <th>id_sucursal</th>
                        <th>monto</th>
                        <th>estatus</th>
                        <th>id_factura</th>
                        <th>id_producto</th>
                        <th>cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    {
                    novedadesPedidosData?
                        novedadesPedidosData.map(e=>
                        <tr key={e.id}>
                            <td>{e.id}</td>
                            <td>{e.tipo}</td>
                            <td>{e.num}</td>
                            <td>{e.id_proveedor}</td>
                            <td>{e.id_sucursal}</td>
                            <td>{e.monto}</td>
                            <td>{e.estatus}</td>
                            <td>{e.id_factura}</td>
                            <td>{e.id_producto}</td>
                            <td>{e.cantidad}</td>
                        </tr>
                    ):null}
                </tbody>
            </table>
        </div>
    )
}