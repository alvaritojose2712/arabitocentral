import { useEffect, useState } from "react";
export default function Controldeefectivo({
    sucursalDetallesData,
    controlefecSelectGeneral,
    setcontrolefecSelectGeneral,
    moneda,
    getCatGeneralFun,
    getCatCajas,
}){
    const [subviewCajasResDet, setsubviewCajasResDet] = useState("resumen")
    useEffect(()=>{
        getCatCajas()
    },[])
    return (
        <div>
            <div className="btn-group mt-2">
                <button className={("btn ") + (controlefecSelectGeneral == 1 ?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setcontrolefecSelectGeneral(1)}>Caja Fuerte</button> 
                <button className={("btn ") + (controlefecSelectGeneral == 0 ? "btn-sinapsis" : "btn-outline-sinapsis")} onClick={() => setcontrolefecSelectGeneral(0)}>Caja Chica</button>
            </div>
            <div className="w-100">
                <div className="w-100 d-flex justify-content-center">
                    <div className="btn-group mt-2 mb-2">
                        <button className={("btn ") + (subviewCajasResDet == "resumen" ?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setsubviewCajasResDet("resumen")}>RESUMEN</button> 
                        <button className={("btn ") + (subviewCajasResDet == "detallado" ? "btn-sinapsis" : "btn-outline-sinapsis")} onClick={() => setsubviewCajasResDet("detallado")}>DETALLADO</button>
                    </div>
                </div>
                {subviewCajasResDet=="resumen"?
                    <div className="">
                        <table className="table mb-2">
                            <thead>
                                <tr>
                                    <th className="h4">GENERAL</th>
                                    <th></th>
                                    <th className="text-right">DOLAR</th>
                                    <th className="text-right">BS</th>
                                    <th className="text-right">PESO</th>
                                    <th className="text-right">EURO</th>
                                </tr>
                            </thead>
                            <tbody>
                            {sucursalDetallesData.sum ? sucursalDetallesData.sum.catgeneral? Object.entries(sucursalDetallesData.sum.catgeneral).length?
                                Object.entries(sucursalDetallesData.sum.catgeneral).map((e,i)=>
                                    <tr key={i}>
                                        <th>
                                            <button className="btn w-100 btn-sm" style={{color:"white",fontWeight:"bold",backgroundColor:getCatGeneralFun(e[1].categoria).color}}>{getCatGeneralFun(e[1].categoria).nombre}</button>
                                        </th>
                                        <th></th>
                                        <td className="text-right">{moneda(e[1].montodolar)}</td>
                                        <td className="text-right">{moneda(e[1].montobs)}</td>
                                        <td className="text-right">{moneda(e[1].montopeso)}</td>
                                        <td className="text-right">{moneda(e[1].montoeuro)}</td>
                                    </tr>
                                )
                            :null:null:null}
                            </tbody> 

                        </table>
                        <table className="table mb-2">
                            <thead>
                                <tr>
                                    <th className="h4">ESPECÍFICAS</th>
                                    <th></th>
                                    <th className="text-right">DOLAR</th>
                                    <th className="text-right">BS</th>
                                    <th className="text-right">PESO</th>
                                    <th className="text-right">EURO</th>
                                </tr>
                            </thead>
                            <tbody>
                            {sucursalDetallesData.sum ? sucursalDetallesData.sum.categorias? Object.entries(sucursalDetallesData.sum.categorias).length?
                                Object.entries(sucursalDetallesData.sum.categorias).map((e,i)=>
                                    <tr key={i}>
                                        <th>
                                            <button className="btn w-100 btn-sm" style={{color:"white",fontWeight:"bold",backgroundColor:getCatGeneralFun(e[1].categoria).color}}>{getCatGeneralFun(e[1].categoria).nombre}</button>
                                        </th>
                                        <th>
                                            {e[1].nombre}
                                        </th>
                                        <td className="text-right">{moneda(e[1].montodolar)}</td>
                                        <td className="text-right">{moneda(e[1].montobs)}</td>
                                        <td className="text-right">{moneda(e[1].montopeso)}</td>
                                        <td className="text-right">{moneda(e[1].montoeuro)}</td>
                                    </tr>
                                )
                            :null:null:null}
                            </tbody>    
                        </table>
                    </div>
                :null}
                {subviewCajasResDet=="detallado"?

                    <div className="">
                        <table className="table">
                            <thead>
                                <tr>
                                    <th>SUCURSAL</th>
                                    <th>FECHA</th>
                                    <th>CAT GENERAL</th>
                                    <th className="w-20">CATEGORÍA</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th className="text-right">Monto DOLAR</th>
                                    <th className="">Balance DOLAR</th>
                                    <th className="text-right">Monto BS</th>
                                    <th className="">Balance BS</th>
                                    <th className="text-right">Monto PESO</th>
                                    <th className="">Balance PESO</th>

                                    <th className="text-right">Monto EURO</th>
                                    <th className="">Balance EURO</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {sucursalDetallesData ? sucursalDetallesData.cajas? sucursalDetallesData.cajas.length?
                                    sucursalDetallesData.cajas.map(e=>
                                    e.cat?
                                        <tr key={e.id}>
                                            <td>{e.sucursal.codigo}</td>
                                            <td className=""><small className="text-muted">{e.created_at}</small></td>
                                            <td className="">
                                                <button className="btn w-100 btn-sm" 
                                                    style={{color:"white",fontWeight:"bold",backgroundColor:getCatGeneralFun(e.categoria).color}}>
                                                        {getCatGeneralFun(e.categoria).nombre}
                                                </button>
                                            </td>
                                            <td className="w-20">{e.cat.nombre}</td>
                                            <td className="">{e.concepto}</td>
                                            
                                            <td className={(e.montodolar<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montodolar)}</td>
                                            <td className={("")}>{moneda(e.dolarbalance)}</td>
                                            
                                            <td className={(e.montobs<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montobs)}</td>
                                            <td className={("")}>{moneda(e.bsbalance)}</td>
                                            
                                            <td className={(e.montopeso<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montopeso)}</td>
                                            <td className={("")}>{moneda(e.pesobalance)}</td>

                                            <td className={(e.montoeuro<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montoeuro)}</td>
                                            <td className={("")}>{moneda(e.eurobalance)}</td>
                                            
                                        </tr>
                                    :null)
                                :null:null:null}
                            </tbody>
                        </table>
                    </div>
                :null}

            </div>

            

        </div>
    )
}