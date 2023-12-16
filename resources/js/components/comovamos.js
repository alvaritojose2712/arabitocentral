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


                {sucursalDetallesData ? sucursalDetallesData.length ?
                    sucursalDetallesData.map(e =>
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
                                        <span className="text-success pull-right fs-2">
                                            <i className="fa fa-user m-2"></i>
                                            <button className="btn btn-xl btn-outline-success btn-circle fs-5">
                                                {e.numventas}
                                            </button>
                                        </span>

                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    )
                    : null : null}

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
