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
                                        <span className="fs-5 ms-2"  onClick={()=>{setindexviewvariable_fijo(indexviewvariable_fijo==ii?null:ii);}}>
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
                                                <span className="fs-6  ms-4" style={{backgroundColor:colorsGastosCat(variable_fijo[0],"variable_fijo","color")}} onClick={()=>{setindexviewcatgeneral(indexviewcatgeneral==iii?null:iii);}} >
                                                    {colorsGastosCat(variable_fijo[0],"variable_fijo","desc")}    
                                                </span>
                                            </th>

                                            <th>
                                                <span className="" style={{backgroundColor:colorsGastosCat(variable_fijo[0],"variable_fijo","color")}}>
                                                    {moneda(balanceGeneralData["sumArrvariablefijo"][catgeneral[0]][variable_fijo[0]]["sumdolar"])}
                                                </span>
                                            </th>

                                        </tr>
                                            {indexviewcatgeneral==iii&&viewcatgeneral?
                                                Object.entries(variable_fijo[1]).map((categoria,iiii)=>
                                                <>
                                                    <tr>
                                                        <td colSpan={3} style={{backgroundColor:colorsGastosCat(categoria[0],"cat","color")}}>
                                                            <span className={" fw-bolder fs-6 ms-5"}   onClick={()=>{setindexviewcat(indexviewcat==iiii?null:iiii);}}>
                                                                {colorsGastosCat(categoria[0],"cat","desc")}
                                                            </span>
                                                        </td>
                                                        <th>
                                                            <span className="" style={{backgroundColor:colorsGastosCat(categoria[0],"cat","color")}}>
                                                                {moneda(balanceGeneralData["sumArrcat"][categoria[0]]["sumdolar"])}
                                                            </span>
                                                        </th>
                                                    </tr>
                                                    {indexviewcat==iiii&&viewcat?
                                                        categoria[1].map(e=>
                                                            <tr key={e.id} className="bg-success-light">
                                                                <td colSpan={3}>
                                                                    <div className="ms-5">
                                                                        <span className="">{e.concepto?e.concepto:e.loteserial}</span>
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
                                <span className="fs-5 ms-2">
                                    PAGO PROVEEDOR BRUTO     
                                </span>
                            </th>
                            <th colSpan={2} className="w-50">
                                <span className="fs-3">
                                    {moneda(balanceGeneralData["sumPagoProveedorBancoEfectivoReal"]?balanceGeneralData["sumPagoProveedorBancoEfectivoReal"]:null)}
                                </span>
                            </th>
                        </tr>
                        <tr className="pointer">
                            <th colSpan={2}  className="w-50">
                                <span className="fs-5 ms-2" onClick={()=>{setviewpagoproveedor(!viewpagoproveedor)}}>
                                    PAGO PROVEEDOR NETO    
                                </span>
                            </th>
                            <th colSpan={2} className="w-50">
                                <span className="fs-3">
                                    {moneda(balanceGeneralData["pagoproveedor"]?balanceGeneralData["pagoproveedor"]["balance"]:null)}
                                </span>
                            </th>
                        </tr>

                        <tr className="pointer bg-danger-light">
                            <th colSpan={2}  className="w-50">
                                <span className="fs-5 ms-2">
                                    PAGO PROVEEDOR PÉDIDA COMISIÓN DE TASAS     
                                </span>
                            </th>
                            <th colSpan={2} className="w-50">
                                <span className="fs-3">
                                    {moneda(balanceGeneralData["perdidaPagoProveedor"]?balanceGeneralData["perdidaPagoProveedor"]:null)}
                                </span>
                            </th>
                        </tr>

                        {/* <tr className="pointer">
                            <th colSpan={2}  className="w-50">
                                <span className="fs-5 ms-2">
                                    PAGO PROVEEDOR NETO BANCO    
                                </span>
                            </th>
                            <th colSpan={2} className="w-50">
                                <span className="fs-3">
                                    {moneda(balanceGeneralData["sumPagoProveedorBanco"]?balanceGeneralData["sumPagoProveedorBanco"]:null)}
                                </span>
                            </th>
                        </tr>
                        <tr className="pointer">
                            <th colSpan={2}  className="w-50">
                                <span className="fs-5 ms-2">
                                    PAGO PROVEEDOR NETO EFECTIVO    
                                </span>
                            </th>
                            <th colSpan={2} className="w-50">
                                <span className="fs-3">
                                    {moneda(balanceGeneralData["sumPagoProveedorEfectivo"]?balanceGeneralData["sumPagoProveedorEfectivo"]:null)}
                                </span>
                            </th>
                        </tr>

                        <tr className="pointer">
                            <th colSpan={2}  className="w-50">
                                <span className="fs-5 ms-2">
                                    PAGO PROVEEDOR BRUTO BANCO     
                                </span>
                            </th>
                            <th colSpan={2} className="w-50">
                                <span className="fs-3">
                                    {moneda(balanceGeneralData["sumPagoProveedorBancoReal"]?balanceGeneralData["sumPagoProveedorBancoReal"]:null)}
                                </span>
                            </th>
                        </tr> */}




                        <tr className="pointer">
                            <th colSpan={2}  className="w-50">
                                <span className="fs-5 ms-2">
                                    FDI    
                                </span>
                            </th>
                            <th colSpan={2} className="w-50">
                                <span className="fs-3">
                                    {moneda(balanceGeneralData["fdi"]?balanceGeneralData["fdi"]:null)}
                                </span>
                            </th>
                        </tr>
                        {viewpagoproveedor?
                            <tr>
                                <td colSpan={3}>
                                    <table className="table table-bordered">
                                        <tbody>
                                            {
                                                balanceGeneralData["pagoproveedor"]?
                                                    balanceGeneralData["pagoproveedor"]["detalles"].map((pagosproveedor,i)=>
                                                        <tr>
                                                            <td>{i+1}</td>
                                                            <th>
                                                                {pagosproveedor.proveedor.descripcion}
                                                            </th>
                                                            <td>
                                                                {pagosproveedor.fechaemision}
                                                            </td>
                                                            <td>
                                                                <table className="table">
                                                                {pagosproveedor.montobs1&&pagosproveedor.montobs1!="0.00"?
                                                                        <tr>
                                                                            <td className="w-25">Bs. {moneda(pagosproveedor.montobs1)}</td>
                                                                            <td className="w-25 text-success">{moneda(pagosproveedor.tasabs1)}</td>
                                                                            <td className="w-25">{(pagosproveedor.metodobs1)}</td>
                                                                            <td className="w-25 text-muted">{(pagosproveedor.refbs1)}</td>
                                                                        </tr>
                                                                :null}

                                                                {pagosproveedor.montobs2&&pagosproveedor.montobs2!="0.00"?
                                                                        <tr>
                                                                            <td className="w-25">Bs. {moneda(pagosproveedor.montobs2)}</td>
                                                                            <td className="w-25 text-success">{moneda(pagosproveedor.tasabs2)}</td>
                                                                            <td className="w-25">{(pagosproveedor.metodobs2)}</td>
                                                                            <td className="w-25 text-muted">{(pagosproveedor.refbs2)}</td>
                                                                        </tr>
                                                                        :null}
                                                                {pagosproveedor.montobs3&&pagosproveedor.montobs3!="0.00"?
                                                                        <tr>
                                                                            <td className="w-25">Bs. {moneda(pagosproveedor.montobs3)}</td>
                                                                            <td className="w-25 text-success">{moneda(pagosproveedor.tasabs3)}</td>
                                                                            <td className="w-25">{(pagosproveedor.metodobs3)}</td>
                                                                            <td className="w-25 text-muted">{(pagosproveedor.refbs3)}</td>
                                                                        </tr>
                                                                        :null}
                                                                {pagosproveedor.montobs4&&pagosproveedor.montobs4!="0.00"?
                                                                        <tr>
                                                                            <td className="w-25">Bs. {moneda(pagosproveedor.montobs4)}</td>
                                                                            <td className="w-25 text-success">{moneda(pagosproveedor.tasabs4)}</td>
                                                                            <td className="w-25">{(pagosproveedor.metodobs4)}</td>
                                                                            <td className="w-25 text-muted">{(pagosproveedor.refbs4)}</td>
                                                                        </tr>
                                                                        :null}

                                                                {pagosproveedor.montobs5&&pagosproveedor.montobs5!="0.00"?
                                                                        <tr>
                                                                            <td className="w-25">Bs. {moneda(pagosproveedor.montobs5)}</td>
                                                                            <td className="w-25 text-success">{moneda(pagosproveedor.tasabs5)}</td>
                                                                            <td className="w-25">{(pagosproveedor.metodobs5)}</td>
                                                                            <td className="w-25 text-muted">{(pagosproveedor.refbs5)}</td>
                                                                        </tr>
                                                                :null}
                                                                    </table>


                                                            </td>
                                                            <td>
                                                                $ {moneda(pagosproveedor.monto)}
                                                            </td>
                                                        </tr>
                                                    )
                                                :null
                                            }
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        :null}
                    </tbody>
                )
            :null}
        </table>
    )
}