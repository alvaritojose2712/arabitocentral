import { useEffect, useState } from "react";
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


}) {
    const [subviewcomovamos, setsubviewcomovamos] = useState("comovamos")
    
    const [showmoreefectivo, setshowmoreefectivo] = useState(false)
    const [showmorebanco, setshowmorebanco] = useState(false)
    const [showmoreinventario, setshowmoreinventario] = useState(false)
    const [showmorecxc, setshowmorecxc] = useState(false)
    const [showmorecxp, setshowmorecxp] = useState(false)
    useEffect(() => {
        getsucursalDetallesData(null, "comovamos")
    }, [])


    return (
        <div className="container">
            <div className="p-3 text-center">
                <div className="btn-group">
                    <button className="btn btn-success" onClick={()=>setsubviewcomovamos("comovamos")}>Cómo Vamos</button>
                    <button className="btn btn-sinapsis" onClick={()=>setsubviewcomovamos("balancegeneral")}>BALANCE GENERAL</button>
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
                                            <button className="btn btn-outline-info fs-3">{e.sucursal.nombre}</button>
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
                            <td className="w-30 align-middle">
                                
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
            
            {subviewcomovamos=="balancegeneral"?
                <>
                    <form className="form-group" onSubmit={event=>{event.preventDefault();getBalanceGeneral()}}>
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
                    </form>

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


        </div>
    )
}
