export default function Puntosyseriales({
    getsucursalDetallesData,
    sucursalDetallesData,
}){
    return (
        <div>
            <table className="table">
                <thead>
                    <tr>
                        <th>SUCURSAL</th>
                        <th>FECHA</th>
                        <th>ID_USUARIO / ID_PEDIDO</th>
                        <th>TIPO</th>
                        <th>BANCO</th>
                        <th>MONTO</th>
                        <th>REF / LOTE / SERIAL</th>
                    </tr>
                </thead>
                <tbody>
                    {sucursalDetallesData.length?sucursalDetallesData[0].fecha?sucursalDetallesData.map(e=>
                        <tr key={e.id}>
                            <td>{e.sucursal.codigo}</td>
                            <td>{e.fecha}</td>
                            <td>{e.id_usuario}</td>
                            <td>{e.tipo}</td>
                            <td>{e.loteserial}</td>
                            <td>{e.monto}</td>
                            <td>{e.banco}</td>
                        </tr>    
                    ):null:null}
                </tbody>
            </table>
        </div>
    )
}