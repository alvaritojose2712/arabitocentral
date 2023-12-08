export default function Puntosyseriales({
    getsucursalDetallesData,
    sucursalDetallesData,
}){
    return (
        <div>
            <table className="table">
                <thead>
                    <tr>

                    <th>FECHA</th>
                    <th>ID_SUCURSAL</th>
                    <th>ID_USUARIO</th>
                    <th>TIPO</th>
                    <th>LOTESERIAL</th>
                    <th>MONTO</th>
                    <th>BANCO</th>
                    </tr>
                </thead>
                <tbody>
                    {sucursalDetallesData.length?sucursalDetallesData[0].fecha?sucursalDetallesData.map(e=>
                        <tr key={e.id}>
                            <td>{e.fecha}</td>
                            <td>{e.id_sucursal}</td>
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