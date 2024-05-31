import { useState } from "react";
export default function Cajascatdesplegable({
    balanceGeneralData,
    colorsGastosCat,
    moneda,
    filter
}){
    const [viewvariable_fijo,setviewvariable_fijo] = useState(true);
    const [viewcatgeneral,setviewcatgeneral] = useState(true);
    const [viewcat,setviewcat] = useState(true);

    const [viewpagoproveedor,setviewpagoproveedor] = useState(false);

    const [indexviewcat,setindexviewcat] = useState(null);
    const [indexviewcatgeneral,setindexviewcatgeneral] = useState(null);
    const [indexviewvariable_fijo,setindexviewvariable_fijo] = useState(null);
    return (
        <table className="table table-bordered">
            {balanceGeneralData.gastos?
                Object.entries(balanceGeneralData.gastos).filter((e,i)=>e[0]==filter).map((ingreso_egreso,i)=>
                    <tbody key={i}>

                        <tr>
                            <td colSpan={4} className="p-0">
                                <button className={"btn fw-bolder fs-4"} style={{backgroundColor:colorsGastosCat(ingreso_egreso[0],"ingreso_egreso","color")}}>
                                    {colorsGastosCat(ingreso_egreso[0],"ingreso_egreso","desc")} {filter==1?" EFECTIVO":null}
                                </button>
                            </td>
                        </tr>
                        {Object.entries(ingreso_egreso[1]).map((catgeneral,ii)=>
                            <>
                                <tr className="pointer" style={{backgroundColor:colorsGastosCat(catgeneral[0],"catgeneral","color")}}>
                                    <th colSpan={2} className="w-50">
                                        <span className="fs-5 btn ms-2"  onClick={()=>{setindexviewvariable_fijo(indexviewvariable_fijo==ii?null:ii);}}>
                                            {colorsGastosCat(catgeneral[0],"catgeneral","desc")}    
                                        </span>
                                    </th>

                                    <th colSpan={2} className="w-50">
                                        <span className="fs-3">
                                            {moneda(balanceGeneralData["sumArrcatgeneral"][catgeneral[0]]["sumdolar"])}
                                        </span>
                                    </th>
                                </tr>

                                {indexviewvariable_fijo==ii&&viewvariable_fijo?
                                    Object.entries(catgeneral[1]).map((variable_fijo,iii)=>
                                        <>
                                         <tr className="pointer">
                                            <th colSpan={3}>
                                                <span className="fs-6 btn ms-4" style={{backgroundColor:colorsGastosCat(variable_fijo[0],"variable_fijo","color")}} onClick={()=>{setindexviewcatgeneral(indexviewcatgeneral==iii?null:iii);}} >
                                                    {colorsGastosCat(variable_fijo[0],"variable_fijo","desc")}    
                                                </span>
                                            </th>

                                            <th>
                                                <span className="btn" style={{backgroundColor:colorsGastosCat(variable_fijo[0],"variable_fijo","color")}}>
                                                    {moneda(balanceGeneralData["sumArrvariablefijo"][catgeneral[0]][variable_fijo[0]]["sumdolar"])}
                                                </span>
                                            </th>

                                        </tr>
                                            {indexviewcatgeneral==iii&&viewcatgeneral?
                                                Object.entries(variable_fijo[1]).map((categoria,iiii)=>
                                                <>
                                                    <tr>
                                                        <td colSpan={3}>
                                                            <button className={"btn fw-bolder fs-6 ms-5 btn-sm"} style={{backgroundColor:colorsGastosCat(categoria[0],"cat","color")}}  onClick={()=>{setindexviewcat(indexviewcat==iiii?null:iiii);}}>
                                                                {colorsGastosCat(categoria[0],"cat","desc")}
                                                            </button>
                                                        </td>
                                                        <th>
                                                            <span className="btn" style={{backgroundColor:colorsGastosCat(categoria[0],"cat","color")}}>
                                                                {moneda(balanceGeneralData["sumArrcat"][categoria[0]]["sumdolar"])}
                                                            </span>
                                                        </th>
                                                    </tr>
                                                    {indexviewcat==iiii&&viewcat?
                                                        categoria[1].map(e=>
                                                            <tr key={e.id} className="bg-success-light">
                                                                <td colSpan={3}>
                                                                    <div className="ms-5">
                                                                        <span className="">{e.concepto}</span>
                                                                        <br />

                                                                        <span className=" fw-bolder">({e.sucursal.codigo})</span>
                                                                        <br />

                                                                        <span className=" text-muted">{e.created_at}</span>

                                                                    </div>
                                                                </td>
                                                                <td>{moneda(e.montofull)}</td>
                                                            </tr>
                                                        )
                                                    :null}
                                                </>   
                                            ):null}
                                        </>
                                ):null}
                            </>   

                        )}

                        <tr className="pointer" style={{backgroundColor:colorsGastosCat(0,"catgeneral","color")}}>
                            <th colSpan={2}  className="w-50">
                                <span className="fs-5 btn ms-2" onClick={()=>{setviewpagoproveedor(!viewpagoproveedor)}}>
                                    {colorsGastosCat(0,"catgeneral","desc")}    
                                </span>
                            </th>
                            <th colSpan={2} className="w-50">
                                <span className="fs-3">
                                    {moneda(balanceGeneralData["pagoproveedor"]?balanceGeneralData["pagoproveedor"]["balance"]:null)}
                                </span>
                            </th>
                        </tr>
                        {viewpagoproveedor?
                            balanceGeneralData["pagoproveedor"]?
                                balanceGeneralData["pagoproveedor"]["detalles"].map(pagosproveedor=>
                                    <tr key={pagosproveedor.id}>
                                        <td colSpan={3}>
                                            <div className="ms-3">

                                                <span className="fw-bolder">
                                                    {pagosproveedor.proveedor.descripcion}
                                                </span>
                                                <br />
                                                <span className="text-muted">
                                                    {pagosproveedor.created_at}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <button className="btn btn-success">{moneda(pagosproveedor.monto)}</button>
                                        </td>
                                    </tr>
                                )
                            :null
                        :null}
                    </tbody>
                )
            :null}
        </table>
    )
}