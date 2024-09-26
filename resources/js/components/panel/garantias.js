export default function Garantias({
    garantiasData,
    garantiaq,
    setgarantiaq,
    garantiaqsucursal,
    setgarantiaqsucursal,
    getGarantias,
    sucursales,
}){
    return(
        <div>
            <h3>Garantías</h3>

            <div className="form-group mb-3">
                <form className="input-group" onSubmit={event=>{event.preventDefault();getGarantias()}}>
                    <input type="text" className="form-control" value={garantiaq} placeholder="Buscar..." onChange={event=>setgarantiaq(event.target.value)} />
                    <select className="form-control" value={garantiaqsucursal} onChange={event=>setgarantiaqsucursal(event.target.value)}>
                      <option value="">-SUCURSAL-</option>
                      {sucursales.map(e=>
                        <option key={e.id} value={e.id}>
                            {e.codigo}
                        </option>
                      )}  
                    </select>
                    <button className="btn btn-success"><i className="fa fa-search"></i></button>
                </form>
            </div>
            <table className="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SUCURSAL</th>
                        <th>PRODUCTO</th>
                        <th>CANTIDAD</th>
                        <th>MOTIVO</th>
                        <th>FECHA</th>
                    </tr>
                </thead>
                <tbody>
                    {garantiasData?
                        garantiasData.map(e=>
                            <tr key={e.id}>
                                <td>{e.id}</td>
                                <td>{e.sucursal?e.sucursal.codigo:null}</td>
                                <td>{e.producto?
                                    <>
                                        <b>{e.producto.codigo_barras}</b> {e.producto.descripcion}
                                    </>
                                :null}</td>
                                <td>{e.cantidad}</td>
                                <td>{e.motivo}</td>
                                <td>{e.created_at}</td>
                            </tr>
                        )
                    :null}
                </tbody>
            </table>
        </div>
    )
}