import { useState } from "react";
export default function Cajascatdesplegable({
    balanceGeneralData,
    colorsGastosCat,
    moneda,
    filter
}){
    const [viewcat,setviewcat] = useState(false);
    const [viewcatgeneral,setviewcatgeneral] = useState(false);
    return (
        <table className="table">
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
                                <tr style={{backgroundColor:colorsGastosCat(catgeneral[0],"catgeneral","color")}} onClick={()=>setviewcatgeneral(!viewcatgeneral)} className="pointer">
                                    <td></td>
                                    <th>
                                        <span className="fs-5">
                                            {colorsGastosCat(catgeneral[0],"catgeneral","desc")}    
                                        </span>
                                    </th>
                                    <td></td>

                                    <th>{moneda(balanceGeneralData["sumArrcatgeneral"][catgeneral[0]]["sumdolar"])}</th>

                                </tr>

                                {viewcatgeneral?
                                    Object.entries(catgeneral[1]).map((categoria,iii)=>
                                    <>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <button className={"btn fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(categoria[0],"cat","color")}}  onClick={()=>setviewcat(!viewcat)}>
                                                    {colorsGastosCat(categoria[0],"cat","desc")}
                                                </button>
                                            </td>
                                            <td></td>
                                            <th>{moneda(balanceGeneralData["sumArrcat"][categoria[0]]["sumdolar"])}</th>
                                        </tr>
                                        {viewcat?
                                            categoria[1].map(e=>
                                                <tr key={e.id}>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{e.concepto}</td>
                                                    <td>{moneda(e.montofull)}</td>
                                                </tr>
                                            )
                                        :null}
                                    </>   
                                ):null}
                            </>   

                        )}
                    </tbody>
                )
            :null}
        </table>
    )
}