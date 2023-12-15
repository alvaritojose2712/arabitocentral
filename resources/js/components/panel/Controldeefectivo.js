export default function Controldeefectivo({
    sucursalDetallesData,
    controlefecSelectGeneral,
    setcontrolefecSelectGeneral,
    moneda,
}){
    return (
        <div>
            <button className={("btn ") + (controlefecSelectGeneral == 1 ?"btn-success":"btn-outline-success")} onClick={()=>setcontrolefecSelectGeneral(1)}>Caja Fuerte</button> 
            <button className={("btn ") + (controlefecSelectGeneral == 0 ? "btn-success" : "btn-outline-success")} onClick={() => setcontrolefecSelectGeneral(0)}>Caja Chica</button>
            <table className="table">
                <thead>
                    <tr>
                        <th>TIPO</th>
                        <th>FECHA</th>
                        <th>RESPONSABLE</th>
                        <th>ASIGNAR</th>
                        <th>Descripción</th>
                        <th>Categoría</th>
                        <th className="text-right">Monto DOLAR</th>
                        <th className="">Balance DOLAR</th>
                        <th className="text-right">Monto BS</th>
                        <th className="">Balance BS</th>
                        <th className="text-right">Monto PESO</th>
                        <th className="">Balance PESO</th>

                        <th className="text-right">Monto EURO</th>
                        <th className="">Balance EURO</th>
                    </tr>
                </thead>
                <tbody>
                    {sucursalDetallesData ? sucursalDetallesData.length?
                        sucursalDetallesData.map(e=><tr key={e.id}>
                            <td className="">
                                <small className="text-muted">
                                    {e.tipo==0?"Caja Chica":null}
                                    {e.tipo==1?"Caja Fuerte":null}
                                </small>
                            </td>
                            <td className=""><small className="text-muted">{e.created_at}</small></td>
                            <td className="">{(e.responsable.nombre)}</td>
                            <td className="">{(e.asignar.nombre)}</td>
                            <td className="">{e.concepto}</td>
                            <td className="">{(e.cat.nombre)}</td>
                            
                            <td className={(e.montodolar<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montodolar)}</td>
                            <td className={("")}>{moneda(e.dolarbalance)}</td>
                            
                            <td className={(e.montobs<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montobs)}</td>
                            <td className={("")}>{moneda(e.bsbalance)}</td>
                            
                            <td className={(e.montopeso<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montopeso)}</td>
                            <td className={("")}>{moneda(e.pesobalance)}</td>

                            <td className={(e.montoeuro<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montoeuro)}</td>
                            <td className={("")}>{moneda(e.eurobalance)}</td>
                            
                        </tr>)
                    :null:null}
                </tbody>
            </table>
            
        </div>
    )
}