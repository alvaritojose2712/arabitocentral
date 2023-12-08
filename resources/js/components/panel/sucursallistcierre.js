export default function Sucursallistcierre({
    sucursalListData,
    sucursalSelect,
    setsucursalSelect,
}){
    return(
        <table className="table">
            <thead>
                <tr>
                    <th>SUCURSAL</th>
                    <th>VENTAS UNICAS</th>
                    <th>DÉBITO</th>
                    <th>EFECTIVO</th>
                    <th>TRANSFERENCIA</th>
                    <th>BIOPAGO</th>
                    <th>TOTAL</th>
                    <th>Ganancia y Porcentaje</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
            {sucursalListData.length?sucursalListData.map(e=>
                <tr key={e.id} onClick={()=>setsucursalSelect(e.id)} className="pointer" >
                    <th>
                        {e.nombre}
                    </th>
                    <td>
                        <i className="fa fa-user"></i> <b>{e.numventastotal?e.numventastotal:null}</b> 
                    </td>
                    <td>
                        {e.debitototal?e.debitototal:null}
                    </td>
                    <td>
                        {e.efectivototal?e.efectivototal:null}
                    </td>
                    <td>
                        {e.transferenciatotal?e.transferenciatotal:null}
                    </td>
                    <td>BIOPAGO</td>
                    <td>
                        {e.total?e.total:null}
                    </td>
                    <td>
                        <button className="btn m-1 btn-success">
                            <i className="fa fa-money"></i> <b>{e.gananciatotal?e.gananciatotal:null} </b>
                        </button>
                        <button className="btn m-1 btn-outline-success">
                            <b>{e.porcentajetotal?e.porcentajetotal:null}</b> %
                        </button>
                    </td>
                </tr>

                 
            ):null}
        </table>
    )
}
