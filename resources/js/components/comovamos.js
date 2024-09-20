import { useEffect, useState } from "react";
import Cajascatdesplegable  from "./cajascatdesplegable";
import Chart from "react-apexcharts";
export default function ComoVamos({
    getsucursalDetallesData,
    sucursalDetallesData,
    subviewpanelsucursales,
    setsubviewpanelsucursales,
    moneda,
    balanceGeneralData,
    getBalanceGeneral,
    sucursalBalanceGeneral,
    setsucursalBalanceGeneral,
    setfechaBalanceGeneral,
    fechaBalanceGeneral,
    setfechaHastaBalanceGeneral,
    fechaHastaBalanceGeneral,
    sucursales,
    colorsGastosCat,
    sendCuadreGeneral,
    cuantotengobanco,
    setcuantotengobanco,
    cuantotengoefectivo,
    setcuantotengoefectivo,
    colorSucursal,

    sendReporteDiario,
    fechareportediario,
    setfechareportediario,


}) {
    const [subviewcomovamos, setsubviewcomovamos] = useState("comovamos")
    
    const [showmoreefectivo, setshowmoreefectivo] = useState(false)
    const [showmorebanco, setshowmorebanco] = useState(false)
    const [showmoreinventario, setshowmoreinventario] = useState(false)
    const [showmorecxc, setshowmorecxc] = useState(false)
    const [showmorecxp, setshowmorecxp] = useState(false)

    const [indexsubviewproveedordetalles,setindexsubviewproveedordetalles] = useState(null)

    useEffect(() => {
        getsucursalDetallesData(null, "comovamos")
    }, [])

   


    return (
        <div className="container-fluid">
            <div className="p-3 text-center">
                <div className="btn-group">
                    <button className="btn btn-success" onClick={()=>setsubviewcomovamos("comovamos")}>CÓMO VAMOS</button>
                    <button className="btn btn-info" onClick={()=>setsubviewcomovamos("balanceresultados")}>BALANCE DE RESULTADOS</button>
                    <button className="btn btn-warning" onClick={()=>setsubviewcomovamos("reportediario")}>REPORTE DIARIO</button>
                </div>
            </div>
            {subviewcomovamos=="comovamos"?
                <table className="table table-borderless">
                    {sucursalDetallesData ? sucursalDetallesData.comovamos ?
                        sucursalDetallesData.comovamos.map(e =>
                            <tbody key={e.id}>
                                <tr>
                                    <td colSpan={3} className="text-center">
                                        <small className="text-muted fw-italic">{e.updated_at}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td className="w-30 align-middle">
                                        <div className="btn-group w-100 h-100">
                                            <button className="btn w-100 fw-bolder fs-3" style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>{e.sucursal.nombre}</button>
                                        </div>
                                    </td>
                                    <td className="w-60 align-middle">

                                        <div className="btn-group w-100 h-100">
                                            <button className="btn btn-outline-success fs-3">Tot. {moneda(e.total)}</button>
                                            <button className="btn btn-outline-sinapsis fs-5">Efec. {moneda(e.efectivo)}</button>
                                            <button className="btn btn-outline-sinapsis fs-5">Deb. {moneda(e.debito)}</button>
                                            <button className="btn btn-outline-sinapsis fs-5">Trans. {moneda(e.transferencia)}</button>
                                            <button className="btn btn-outline-sinapsis fs-5">BioPago. {moneda(e.biopago)}</button>
                                        </div>

                                    </td>
                                    <td className="w-10 align-middle">
                                        <div className="w-100 h-100 d-flex justify-content-end">
                                            <span className="text-success pull-right fs-2 text-center">
                                                <i className="fa fa-user m-2"></i><br />
                                                <button className="btn btn-xl btn-outline-success fs-5">
                                                    {e.numventas}
                                                </button>
                                            </span>

                                        </div>
                                    </td>
                                    <td className="fs-2 text-success">
                                        {e.ticked}
                                    </td>
                                </tr>
                            </tbody>
                        )
                        : null : null}

                    <tbody>
                        <tr>
                            <td colSpan={3} className="text-center">
                                <small className="text-muted fw-italic"></small>
                            </td>
                        </tr>
                        <tr>
                            <td className="w-30 align-middle text-center">
                                <button className="btn btn-sinapsis fs-2">
                                    {sucursalDetallesData.comovamos?
                                        sucursalDetallesData.comovamos.length
                                    :null}
                                </button>
                            </td>
                            <td className="w-60 align-middle">

                                <div className="btn-group w-100 h-100">
                                    <button className="btn btn-success fs-3">Tot. {moneda(sucursalDetallesData.sum?sucursalDetallesData.sum.total:"")}</button>
                                    <button className="btn btn-sinapsis fs-5">Efec. {moneda(sucursalDetallesData.sum?sucursalDetallesData.sum.efectivo:"")}</button>
                                    <button className="btn btn-sinapsis fs-5">Deb. {moneda(sucursalDetallesData.sum?sucursalDetallesData.sum.debito:"")}</button>
                                    <button className="btn btn-sinapsis fs-5">Trans. {moneda(sucursalDetallesData.sum?sucursalDetallesData.sum.transferencia:"")}</button>
                                    <button className="btn btn-sinapsis fs-5">BioPago. {moneda(sucursalDetallesData.sum?sucursalDetallesData.sum.biopago:"")}</button>
                                </div>

                            </td>
                            <td className="w-10 align-middle">
                                <div className="w-100 h-100 d-flex justify-content-end">
                                    <span className="text-success pull-right fs-2 text-center">
                                        <i className="fa fa-user m-2"></i><br />
                                        <button className="btn btn-xl btn-success fs-5">
                                            {sucursalDetallesData.sum?sucursalDetallesData.sum.numventas:""}
                                        </button>
                                    </span>
                                </div>
                            </td>
                            <td className="fs-2 text-success">
                                {sucursalDetallesData.sum?sucursalDetallesData.sum.ticked:""}
                            </td>
                        </tr>
                    </tbody>
                </table>
            :null}
            
            {subviewcomovamos=="balancegeneral"||subviewcomovamos=="balanceresultados"?<form className="form-group" onSubmit={event=>{event.preventDefault();getBalanceGeneral()}}>
                <div className="input-group">
                    
                    <select className="form-control form-control-lg" value={sucursalBalanceGeneral} onChange={e=>setsucursalBalanceGeneral(e.target.value)}>
                        <option value="">-SUCURSAL-</option>
                        {sucursales.map(e=>
                            <option key={e.id} value={e.id}>{e.codigo}</option>
                        )}
                    </select>
                    <input type="date" className="form-control" onChange={event=>setfechaBalanceGeneral(event.target.value)} value={fechaBalanceGeneral} />
                    <input type="date" className="form-control" onChange={event=>setfechaHastaBalanceGeneral(event.target.value)} value={fechaHastaBalanceGeneral} />
                    <button type="submit" className="btn btn-success btn-lg"><i className="fa fa-search"></i></button>
                </div>
            </form>:null}
            {subviewcomovamos=="balancegeneral"?
                <>

                    <table className="table">
                        <tbody>
                            <tr>
                                <th colSpan={2} className="fs-4">ACTIVOS</th>
                                <th colSpan={2} className="fs-4">PASIVOS</th>
                            </tr>
                            <tr>
                                <td>
                                    <table className="table">
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td>EFECTIVO</td>
                                                <th>{moneda(balanceGeneralData.efectivodolar)}</th>
                                            </tr>
                                            
                                            {
                                                showmoreefectivo?
                                                    balanceGeneralData.efectivoData.map((e,i)=>
                                                        <tr key={i}>
                                                            <td></td>
                                                        </tr>
                                                    )
                                                :null
                                            }

                                            <tr>
                                                <td></td>
                                                <td>BANCO</td>
                                                <th>{moneda(balanceGeneralData.banco)}</th>
                                            </tr>

                                            {
                                                showmorebanco?
                                                    balanceGeneralData.bancoData.map((e,i)=>
                                                        <tr key={i}>
                                                            <td></td>
                                                        </tr>
                                                    )
                                                :null
                                            }

                                            <tr>
                                                <td></td>
                                                <td>INVENTARIO</td>
                                                <th>{moneda(balanceGeneralData.inventario)}</th>
                                            </tr>

                                            {
                                                showmoreinventario?
                                                    balanceGeneralData.inventarioData.map((e,i)=>
                                                        <tr key={i}>
                                                            <td></td>
                                                        </tr>
                                                    )
                                                :null
                                            }

                                            <tr>
                                                <td></td>
                                                <td>CUENTAS POR COBRAR</td>
                                                <th>{moneda(balanceGeneralData.cxc)}</th>
                                            </tr>

                                            {
                                                showmorecxc?
                                                    balanceGeneralData.cxcData.map((e,i)=>
                                                        <tr key={i}>
                                                            <td></td>
                                                        </tr>
                                                    )
                                                :null
                                            }
                                        </tbody>
                                    </table>
                                </td>
                                <td>
                                    <table className="table">
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td>CUENTAS POR PAGAR</td>
                                                <th>{moneda(balanceGeneralData.cxp)}</th>
                                            </tr>
                                            {
                                                showmorecxp?
                                                    balanceGeneralData.cxpData.map((e,i)=>
                                                        <tr key={i}>
                                                            <td></td>
                                                        </tr>
                                                    )
                                                :null
                                            }
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </>
            :null}

            {subviewcomovamos=="balanceresultados"?
                balanceGeneralData.efectivo?
                    <>
                        <table className="table">
                            <tbody>
                            
                                <tr>
                                    <td className="w-50">
                                        

                                        <table className="table">
                                            <tbody>
                                                <tr>
                                                    <td className="p-0">
                                                        <button className={"btn fw-bolder fs-4"} style={{backgroundColor:colorsGastosCat(1,"ingreso_egreso","color")}}>
                                                            {colorsGastosCat(1,"ingreso_egreso","desc")}
                                                        </button>
                                                    </td>
                                                    <th className="fs-3 p-0">{moneda(balanceGeneralData.total)}</th>
                                                </tr>
                                                <tr className="bg-success-1 fs-4">
                                                    <th className="fs-5">
                                                        INGRESO EFECTIVO
                                                    </th>
                                                    <th className="fs-5">
                                                        {moneda(balanceGeneralData.efectivo)}
                                                    </th>
                                                </tr>
                                                <tr className="bg-success-2 fs-4">
                                                    <th className="fs-5">
                                                        INGRESO DÉBITO
                                                    </th>
                                                    <th className="fs-5">
                                                        {moneda(balanceGeneralData.debito)}
                                                    </th>
                                                </tr>
                                                <tr className="bg-success-3 fs-4">
                                                    <th className="fs-5">
                                                        INGRESO TRANSFERENCIA
                                                    </th>
                                                    <th className="fs-5">
                                                        {moneda(balanceGeneralData.transferencia)}
                                                    </th>
                                                </tr>
                                                <tr className="bg-success-4 fs-4">
                                                    <th className="fs-5">
                                                        INGRESO BIOPAGO
                                                    </th>
                                                    <th className="fs-5">
                                                        {moneda(balanceGeneralData.biopago)}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td className="p-0 w-50">
                                                        <button className={"btn fw-bolder fs-4 btn-sinapsis"}>
                                                            UTILIDAD BRUTA
                                                        </button>
                                                    </td>
                                                    <th className="fs-3">{moneda(balanceGeneralData.ganancia)}</th>
                                                </tr>

                                                <tr>
                                                    <td className="p-0 w-50">
                                                        <button className={"btn fw-bolder fs-4 btn-info"}>
                                                            INGRESO POR CREDITO
                                                        </button>
                                                    </td>
                                                    <th className="fs-3">{moneda(balanceGeneralData.ingreso_credito_sum)}</th>
                                                </tr>

                                                <tr>
                                                    <td className="p-0 w-50">
                                                        <button className={"btn fw-bolder fs-4 btn-primary"}>
                                                            CUOTAS POR CREDITO
                                                        </button>
                                                    </td>
                                                    <th className="fs-3">{moneda(balanceGeneralData.cuota_credito_sum)}</th>
                                                </tr>
                                                <tr>
                                                    <td className="p-0 w-50">
                                                        <button className={"btn fw-bolder fs-4 btn-sinapsis"}>
                                                            INTERÉS POR CREDITO
                                                        </button>
                                                    </td>
                                                    <th className="fs-3">{moneda(balanceGeneralData.interes_credito_sum)}</th>
                                                </tr>
                                                <tr>
                                                    <td className="p-0 w-50">
                                                        <button className={"btn fw-bolder fs-4 btn-sinapsis"}>
                                                            COMISIÓN POR CREDITO
                                                        </button>
                                                    </td>
                                                    <th className="fs-3">{moneda(balanceGeneralData.comision_credito_sum)}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <Cajascatdesplegable
                                            filter={0}
                                            moneda={moneda}
                                            balanceGeneralData={balanceGeneralData}
                                            colorsGastosCat={colorsGastosCat}
                                        />
                                        <table className="table">
                                            <tbody>
                                                <tr>
                                                    <td className="p-0 w-50">
                                                        <button className={"btn fw-bolder fs-4 bg-sinapsis-light"}>
                                                            UTILIDAD NETA
                                                        </button>
                                                    </td>
                                                    <th className="p-0 fs-3">{moneda(balanceGeneralData.gananciaNeta)}</th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    
                                </tr>
                            </tbody>
                        </table>
                        <div className="row">
                            <div className="col">
                                <table className="table">
                                    <tbody>
                                        <tr>
                                            <td colSpan={2}>
                                                <h1>DEBES TENER</h1>
                                                <span className="text-sinapsis fs-1">{moneda(balanceGeneralData.debetener)}</span>
                                                
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>CAJA INICIAL</th>
                                            <td>
                                                <span className="text-sinapsis fs-1">{moneda(balanceGeneralData.caja_inicial)}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>CAJA INICIAL MATRIZ</th>
                                            <td>
                                                <span className="text-sinapsis fs-1">{moneda(balanceGeneralData.matriz_inicial)}</span>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <th>BANCO</th>
                                            <td>
                                                <span className="text-sinapsis fs-1">{moneda(balanceGeneralData.sum_caja_inicial_banco_dolar)}</span>
                                                <br />
                                                <table className="table">
                                                    <tbody>
                                                        {balanceGeneralData.caja_inicial_banco?balanceGeneralData.caja_inicial_banco.map(e=>
                                                            <tr>
                                                                <td>{e.fecha}</td>
                                                                <td>{e.banco}</td>
                                                                <td>{moneda(e.saldo)}</td>
                                                                <td className="text-success">{moneda(e.saldo_dolar)}</td>
                                                                <td className="text-sinapsis">{moneda(e.puntos_liquidados)}</td>
                                                            </tr>
                                                        ):null}
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>EFECTIVO</th>
                                            <td>
                                                <span className="text-sinapsis fs-1">{moneda(balanceGeneralData.sum_caja_inicial)}</span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>SUM INGRESOS</th>
                                            <td>
                                                <span className="text-sinapsis fs-1">{moneda(balanceGeneralData.total)}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>SUM EGRESOS</th>
                                            <td>
                                                <span className="text-sinapsis fs-1">{moneda(balanceGeneralData.sumEgresos)}</span>
                                            </td>
                                        </tr>
                                            
                                            
                                    </tbody>
                                </table>
                            </div>
                            <div className="col">
                                <form onSubmit={event=>{event.preventDefault();sendCuadreGeneral()}}>
                                    <table className="table">
                                        <tbody>
                                            <tr>
                                                <td className="fs-1">
                                                    <h1>TENGO</h1>
                                                    {moneda(balanceGeneralData.tengo)}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>CAJA ACTUAL MATRIZ</th>
                                                <td>
                                                    <span className="text-sinapsis fs-1">{moneda(balanceGeneralData.matriz_actual)}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>BANCO</td>
                                                <td>
                                                    {moneda(balanceGeneralData.sum_caja_actual_banco_dolar)}
                                                    <br />
                                                    <table className="table">
                                                        <tbody>
                                                            {balanceGeneralData.caja_actual_banco?balanceGeneralData.caja_actual_banco.map(e=>
                                                                <tr>
                                                                    <td>{e.fecha}</td>
                                                                    <td>{e.banco}</td>
                                                                    <td>{moneda(e.saldo)}</td>
                                                                    <td className="text-success">{moneda(e.saldo_dolar)}</td>
                                                                    <td className="text-sinapsis">{moneda(e.puntos_liquidados)}</td>

                                                                </tr>
                                                            ):null}
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>EFECTIVO</td>
                                                <td>
                                                    {moneda(balanceGeneralData.sum_caja_actual)}
                                                </td>
                                            </tr>

                                            
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col d-flex justify-content-center align-items-center">
                                <span className="fs-1 p-2 bg-sinapsis">
                                    {moneda(balanceGeneralData.cuadre)}

                                </span>
                            </div>
                        </div>

                        <h2 className="text-center">% VENTAS / GASTOS</h2>

                        <table className="table">
                            <tbody>
                                <tr>
                                    <td>
                                    <table className="table">
                                            <tbody>
                                                <tr>
                                                    <td>VENTA BRUTA</td>
                                                    <th>{moneda(balanceGeneralData.porcevbruta.series[0])}</th>
                                                </tr>
                                                <tr>
                                                    <td>GASTOS</td>
                                                    <th>{moneda(balanceGeneralData.porcevbruta.series[1])}</th>
                                                    <th>{balanceGeneralData.porcevbrutanum} %</th>
                                                </tr>
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <th>{moneda(balanceGeneralData.total)}</th>
                                                    <th> 100% </th>
                                                </tr>
                                            </tbody>
                                        </table> 
                                    </td>
                                    <td>

                                        <Chart
                                            options={
                                                {
                                                    chart: {
                                                    width: 380,
                                                    type: 'pie',
                                                    },
                                                    labels: balanceGeneralData.porcevbruta?balanceGeneralData.porcevbruta.labels:[],
                                                    responsive: [{
                                                    breakpoint: 480,
                                                    options: {
                                                        chart: {
                                                        width: 200
                                                        },
                                                        legend: {
                                                        position: 'bottom'
                                                        }
                                                    }
                                                    }]
                                                }
                                            }
                                            series={
                                                balanceGeneralData.porcevbruta?balanceGeneralData.porcevbruta.series:[]
                                            }
                                            type="pie"
                                            width="500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h2 className="text-center">% GANANCIA BRUTA / GASTOS</h2>
                        <table className="table">
                            <tbody>
                                <tr>
                                    <td>
                                    <table className="table">
                                            <tbody>
                                                <tr>
                                                    <td>GANANCIA NETA</td>
                                                    <th>{moneda(balanceGeneralData.porcegbruta.series[0])}</th>
                                                </tr>
                                                <tr>
                                                    <td>GASTOS</td>
                                                    <th>{moneda(balanceGeneralData.porcegbruta.series[1])}</th>
                                                    <th>{balanceGeneralData.porcegbrutanum} %</th>
                                                </tr>
                                                <tr>
                                                    <td>TOTAL GANANCIA BRUTA</td>
                                                    <th>{moneda(balanceGeneralData.ganancia)}</th>
                                                    <th> 100% </th>
                                                </tr>
                                            </tbody>
                                        </table> 
                                    </td>
                                    <td>

                                        <Chart
                                            options={
                                                {
                                                    chart: {
                                                    width: 380,
                                                    type: 'pie',
                                                    },
                                                    labels: balanceGeneralData.porcegbruta?balanceGeneralData.porcegbruta.labels:[],
                                                    responsive: [{
                                                    breakpoint: 480,
                                                    options: {
                                                        chart: {
                                                        width: 200
                                                        },
                                                        legend: {
                                                        position: 'bottom'
                                                        }
                                                    }
                                                    }]
                                                }
                                            }
                                            series={
                                                balanceGeneralData.porcegbruta?balanceGeneralData.porcegbruta.series:[]
                                            }
                                            type="pie"
                                            width="500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div className="card m-3 p-2">
                            <div className="container">
                                <h2 className="text-center">INVENTARIOS</h2>

                                <div className="row text-center">
                                    <div className="col">
                                        <table className="table">
                                            <tbody>
                                                <tr>
                                                    <th colSpan={2}>INVENTARIO INICIAL</th>
                                                </tr>
                                                <tr>
                                                    <th>BASE</th>
                                                    <th>VENTA</th>
                                                </tr>
                                                <tr>
                                                    <td className="text-sinapsis fs-3">{moneda(balanceGeneralData.inicial_inventariobase)}</td>
                                                    <td className="text-success fs-3">{moneda(balanceGeneralData.inicial_inventarioventa)}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div className="col">
                                        <table className="table">
                                            <tbody>
                                                <tr>
                                                    <th colSpan={2}>INVENTARIO FINAL</th>
                                                </tr>
                                                <tr>
                                                    <th>BASE</th>
                                                    <th>VENTA</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span className="text-sinapsis fs-3">{moneda(balanceGeneralData.final_inventariobase)}</span><br />  <b className={balanceGeneralData.aumento_inventariobase<0?"text-danger":"text-success"}>{moneda(balanceGeneralData.aumento_inventariobase)} %</b> {balanceGeneralData.aumento_inventariobase<0?<i className="fa fa-arrow-down text-danger"></i>:<i className="fa fa-arrow-up text-success"></i>}
                                                    </td>
                                                    <td>
                                                        <span className="text-success fs-3">{moneda(balanceGeneralData.final_inventarioventa)}</span> <br /> <b className={balanceGeneralData.aumento_inventarioventa<0?"text-danger":"text-success"}>{moneda(balanceGeneralData.aumento_inventarioventa)} %</b> {balanceGeneralData.aumento_inventarioventa<0?<i className="fa fa-arrow-down text-danger"></i>:<i className="fa fa-arrow-up text-success"></i>}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="card m-3 p-2">
                            <div className="container">
                                <h2 className="text-center">CUENTAS POR COBRAR CXP</h2>

                                <div className="row text-center">
                                    <div className="col">
                                        <table className="table">
                                            <tbody>
                                                <tr>
                                                    <th>INICIAL</th>
                                                    <th>FINAL</th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <span className="text-sinapsis fs-3">{moneda(balanceGeneralData.cxc_inicial)}</span>
                                                    </td>
                                                    <td>
                                                        <span className="text-success fs-3">{moneda(balanceGeneralData.cxc_final)}</span> 
                                                        <br /> 
                                                        <b className={balanceGeneralData.cxc_aumento<0?"text-danger":"text-success"}>{moneda(balanceGeneralData.cxc_aumento)} %</b> {balanceGeneralData.cxc_aumento<0?<i className="fa fa-arrow-down text-danger"></i>:<i className="fa fa-arrow-up text-success"></i>}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                       {/*  <h2>% GANANCIA NETA / GASTOS</h2>
                        <table className="table">
                            <tbody>
                                <tr>
                                    <td>
                                    <table className="table">
                                            <tbody>
                                                <tr>
                                                    <td>GANANCIA NETA</td>
                                                    <th>{moneda(balanceGeneralData.porcegneta.series[0])}</th>
                                                </tr>
                                                <tr>
                                                    <td>GASTOS</td>
                                                    <th>{moneda(balanceGeneralData.porcegneta.series[1])}</th>
                                                    <th>{balanceGeneralData.porcegnetanum} %</th>
                                                </tr>
                                                <tr>
                                                    <td>TOTAL</td>
                                                    <th>{moneda(balanceGeneralData.gananciaNeta)}</th>
                                                    <th> 100% </th>
                                                </tr>
                                            </tbody>
                                        </table> 
                                    </td>
                                    <td>

                                        <Chart
                                            options={
                                                {
                                                    chart: {
                                                    width: 380,
                                                    type: 'pie',
                                                    },
                                                    labels: balanceGeneralData.porcegneta?balanceGeneralData.porcegneta.labels:[],
                                                    responsive: [{
                                                    breakpoint: 480,
                                                    options: {
                                                        chart: {
                                                        width: 200
                                                        },
                                                        legend: {
                                                        position: 'bottom'
                                                        }
                                                    }
                                                    }]
                                                }
                                            }
                                            series={
                                                balanceGeneralData.porcegneta?balanceGeneralData.porcegneta.series:[]
                                            }
                                            type="pie"
                                            width="500"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table> */}
                        <div className="card m-3 p-3">
                            <div className="container">
                                <h2 className="text-center">PROPORCIÓN DE PROVEEDORES %</h2>
                                
                                {balanceGeneralData.pagoproveedor?
                                    balanceGeneralData.pagoproveedor.byproveedor?
                                        <>
                                            <Chart
                                                options={{chart: {width: 1200,type: 'pie',}
                                                ,dataLabels: {
                                                    style: {
                                                    colors: ['#000','#000','#000']
                                                    }
                                                }
                                                ,colors: [] 
                                                ,labels: balanceGeneralData.pagoproveedor.byproveedor.map(e=>e.descripcion)
                                                ,responsive: [{breakpoint: 1200,options: {chart: {width: 200},legend: {position: 'bottom'}}}]}}
                                                series={
                                                    balanceGeneralData.pagoproveedor.byproveedor.map(e=>(e.sum))
                                                } 
                                                type="pie" width="1200"
                                            />
                                            <table className="table">

                                                {balanceGeneralData.pagoproveedor.byproveedor.map((e,i)=>
                                                    
                                                    <>
                                                        <tr key={i} onClick={()=>setindexsubviewproveedordetalles(indexsubviewproveedordetalles==i?null:i)}>
                                                            <td></td>
                                                            <td>
                                                                <button className={"btn w-100 fw-bolder fs-3"}>
                                                                    {e.descripcion}
                                                                </button>
                                                            </td>
                                                            <td></td>
                                                            <td colSpan={2} className="bg-warning text-right text-danger fs-4">
                                                                {moneda(e["sum"])}
                                                            </td>
                                                        </tr>
                                                        {indexsubviewproveedordetalles==i?
                                                            e.data.map((eee,iii)=>
                                                                <tr key={iii}>
                                                                    <td className="text-muted">{eee.fechaemision}</td>
                                                                    <td>{eee.descripcion}</td>
                                                                    <td></td>
                                                                    <td colSpan={2} className=" text-right text-danger fs-4">
                                                                        {moneda(eee["monto"])}
                                                                    </td>
                                                                </tr>
                                                            )
                                                        
                                                        :null}
                                                    </>
                                                    
                                                )}
                                            </table>
                                        </>
                                    :null
                                :null}

                            </div>
                        </div>
                    </>
                :null
            :null}

            {subviewcomovamos=="reportediario"?
                <div className="container">
                    <div className="form-group">
                        <span className="text-label">
                            FECHA
                        </span>

                        <input type="date" className="form-control" value={fechareportediario} onChange={event=>setfechareportediario(event.target.value)} />
                    </div>
                    <div className="form-group text-center">
                        <button className="btn m-2 btn-warning" onClick={()=>sendReporteDiario("ver")}>VER</button>
                        <button className="btn m-2 btn-sinapsis" onClick={()=>sendReporteDiario("enviar")}>ENVIAR</button>
                    </div>

                </div> 
            :null}




        </div>
    )
}
