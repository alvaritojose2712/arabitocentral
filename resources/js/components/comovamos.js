import { useEffect } from "react";
export default function ComoVamos({
    getsucursalDetallesData,
    sucursalDetallesData,
    subviewpanelsucursales,
    setsubviewpanelsucursales,
    moneda
}) {

    useEffect(() => {
        getsucursalDetallesData(null, "comovamos")
    }, [])

    console.log(sucursalDetallesData.data)

    return (
        <div className="container">
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
                    </tr>
                </tbody>

            </table>
            {/* <table>
                    <thead>
                        <tr>
                            transferencia
                            biopago
                            debito
                            efectivo
                            tasa
                            tasa_cop
                            numventas
                            total_inventario
                            total_inventario_base
                            cred_total
                            total
                            ventas
                            precio
                            precio_base
                            desc_total
                            ganancia
                            porcentaje
                            fecha
                        </tr>
                    </thead>
                </table> */}

        </div>
    )
}
