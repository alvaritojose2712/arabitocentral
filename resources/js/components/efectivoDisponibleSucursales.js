import { useEffect } from "react";
export default function EfectivoDisponibleSucursales({
    efectivoDisponibleSucursalesData,
    setefectivoDisponibleSucursalesData,
    getDisponibleEfectivoSucursal,
    moneda,
    colorSucursal,
    
}){
    useEffect(()=>{
        getDisponibleEfectivoSucursal()
    },[])
    return (
        <div className="container-fluid">
            <table className="table table-striped">
                <thead>
                    <tr>
                        <th>SUCURSAL</th>
                        <th>CREADO</th>
                        <th>CARGADO</th>
                        <th>CONCEPTO</th>
                        <th className="text-right">Monto DOLAR</th>
                        <th className="">Balance DOLAR</th>
                        <th className="text-right">Monto BS</th>
                        <th className="">Balance BS</th>
                        <th className="text-right">Monto PESO</th>
                        <th className="">Balance PESO</th>

                        <th className="text-right">Monto EURO</th>
                        <th className="">Balance EURO</th>
                        <th>
                            <button className="btn btn-success" type="button" onClick={()=>getDisponibleEfectivoSucursal()}><i className="fa fa-search"></i></button>
                        </th>
                    </tr>
                    {
                        efectivoDisponibleSucursalesData.dolarbalance?
                            <tr>
                                <td colSpan={5}></td>
                                <td className="bg-info fs-5">{moneda(efectivoDisponibleSucursalesData.dolarbalance)}</td>
                                <td></td>
                                <td className="bg-info fs-5">{moneda(efectivoDisponibleSucursalesData.bsbalance)}</td>
                                <td></td>
                                <td className="bg-info fs-5">{moneda(efectivoDisponibleSucursalesData.pesobalance)}</td>
                                <td></td>
                                <td className="bg-info fs-5">{moneda(efectivoDisponibleSucursalesData.eurobalance)}</td>
                            </tr>
                        :null
                    }
                </thead>
                <tbody>
                    {efectivoDisponibleSucursalesData.data? 
                        efectivoDisponibleSucursalesData.data.map(e=>
                            <tr key={e.id}>
                                <td>
                                    <button className={"btn w-100 fw-bolder "} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>{e.sucursal.codigo}</button>
                                </td>
                                <td>{e.fecha}</td>
                                <td>{e.created_at}</td>
                                <td>{e.concepto}</td>

                                <td className={(e.montodolar<0? "text-danger": "text-dark")+(" text-right")}>{moneda(e.montodolar)}</td>
                                <td className={("bg-warning-light text-success fs-5")}>{moneda(e.dolarbalance)}</td>
                                
                                <td className={(e.montobs<0? "text-danger": "text-dark")+(" text-right")}>{moneda(e.montobs)}</td>
                                <td className={("bg-warning-light text-success fs-5")}>{moneda(e.bsbalance)}</td>
                                
                                <td className={(e.montopeso<0? "text-danger": "text-dark")+(" text-right")}>{moneda(e.montopeso)}</td>
                                <td className={("bg-warning-light text-success fs-5")}>{moneda(e.pesobalance)}</td>

                                <td className={(e.montoeuro<0? "text-danger": "text-dark")+(" text-right")}>{moneda(e.montoeuro)}</td>
                                <td className={("bg-warning-light text-success fs-5")}>{moneda(e.eurobalance)}</td>
                            </tr>
                        ):null
                    }
                </tbody>
            </table>

        </div>
    )
}