export default function SucursalResumencierres({
    sucursalDetallesData,
    moneda,

}){
    return(
        <div className="container-fluid m-0 p-0">
            <div className="row">
                <div className="col table-responsive">
                    <table className="table table-bordered">
                        <thead>
                            <tr>
                                <th className="bg-sinapsis text-light">SUCURSAL</th>

                                <th className="bg-light text-sinapsis">VENTA TOTAL</th>
                                <th className="bg-light text-sinapsis">GANANCIAS</th>
                                <th className="bg-sinapsis text-light borderleft">CT. VENTAS</th>

                            </tr>
                        </thead>
                            {
                            sucursalDetallesData.data?
                            Object.entries(sucursalDetallesData.data).length?<>
                                    {Object.entries(sucursalDetallesData.data).map(e=>
                                        <tbody key={e.id}>
                                            <tr>
                                                <th>{e[0]}</th>

                                                {e[1].cierres.map(ee=>
                                                    <td>
                                                        <div><b>{ee.mes}-{ee.dia}</b></div>
                                                        <div className="text-sinapsis">{moneda(ee.total)}</div>
                                                        <div className="text-success">{moneda(ee.ganancia)}</div>
                                                        <div className="fs-4 text-sinapsis">{ee.numventas}</div>
                                                    </td>   
                                                )}
                                                <th className="bg-warning">
                                                    <div><b>TOTAL</b></div>
                                                    <div className="text-sinapsis">{moneda(e[1].total)}</div>
                                                    <div className="text-success">{moneda(e[1].ganancia)}</div>
                                                    <div className="fs-4 text-sinapsis">{e[1].numventas}</div>
                                                </th>  
                                            </tr>
                                        </tbody>
                                    )}
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td className="text-sinapsis">{moneda(sucursalDetallesData.sum.total)}</td>
                                            <td className="text-success">{moneda(sucursalDetallesData.sum.ganancia)}</td>
                                            <td className="fs-4 text-sinapsis">{sucursalDetallesData.sum.numventas}</td>
                                        </tr>
                                    </tbody>  
                                </>
                                :null
                            :null
                            } 
                    </table>
                </div>
            </div>
        </div>
    )
}