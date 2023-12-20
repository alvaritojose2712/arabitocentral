export default function SucursalDetallesCierres({
    sucursalDetallesData
}){
    return(
        <div className="container-fluid m-0 p-0">
            <div className="row">
                <div className="col table-responsive">
                    <table className="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th colSpan={2} className="borderleft text-center fw-bold">TASA DÍA</th>
                                <th colSpan={6} className="borderleft text-center fw-bold">VENTAS</th>
                                <th colSpan={6} className="borderleft text-center fw-bold">CONTABILIDAD</th>
                                <th colSpan={2} className="borderleft text-center fw-bold">INVENTARIO</th>
                                <th colSpan={4} className="borderleft text-center fw-bold">CUENTAS</th>
                                <th colSpan={4} className="borderleft text-center fw-bold">EFECTIVO EN CAJA</th>
                                <th colSpan={4} className="borderleft text-center fw-bold">EFECTIVO GUARDADO</th>
                                <th colSpan={1} className="borderleft text-center fw-bold">NOTA</th>
                                

                            </tr>
                            <tr>
                                <th className="bg-sinapsis text-light">SUCURSAL</th>
                                <th className="bg-sinapsis text-light">Fecha</th>

                                <th className="bg-sinapsis text-light borderleft">DOLAR</th>
                                <th className="bg-sinapsis text-light">PESO</th>
                                
                                <th className="bg-sinapsis text-light borderleft">CT. VENTAS</th>
                                <th className="bg-sinapsis text-light">DEBITO</th>
                                <th className="bg-sinapsis text-light">EFECTIVO</th>
                                <th className="bg-s inapsis text-light">TRANSFERENCIA</th>
                                <th className="bg-light text-sinapsis">VENTA TOTAL</th>
                                <th className="bg-light text-sinapsis">GANANCIAS APROXIMADAS</th>

                                <th className="bg-sinapsis text-light borderleft"># DE REPORTE Z</th>
                                <th className="bg-sinapsis text-light">VENTA EXENTO</th>
                                <th className="bg-sinapsis text-light">VENTA GRAVADA (16%)</th>
                                <th className="bg-light text-sinapsis">IVA VENTAS</th>
                                <th className="bg-light text-sinapsis">TOTAL VENTAS</th>
                                <th className="bg-sinapsis text-light">ÚLTIMA FACTURA</th>

                                <th className="bg-sinapsis text-light borderleft">BASE</th>
                                <th className="bg-sinapsis text-light">VENTA</th>

                                <th className="bg-sinapsis text-light borderleft">CRÉDITO</th>
                                <th className="bg-sinapsis text-light">CRÉDITO POR COBRAR TOTAL</th>
                                <th className="bg-sinapsis text-light">VUELTOS TOTALES</th>
                                <th className="bg-sinapsis text-light">ABONOS DEL DIA</th>

                                <th className="bg-sinapsis text-light borderleft">BOLIVARES</th>
                                <th className="bg-sinapsis text-light">PESOS</th>
                                <th className="bg-sinapsis text-light">DOLARES</th>
                                <th className="bg-sinapsis text-light">EUROS</th>

                                <th className="bg-sinapsis text-light borderleft">BOLIVARES</th>
                                <th className="bg-sinapsis text-light">PESOS</th>
                                <th className="bg-sinapsis text-light">DOLARES</th>
                                <th className="bg-sinapsis text-light">EUROS</th>


                                <th className="bg-sinapsis text-light borderleft">OBSERVACIONES</th>

                            </tr>
                        </thead>
                            {
                            sucursalDetallesData.data?
                                sucursalDetallesData.data.length?<>
                                    {sucursalDetallesData.data.map(e=>
                                        <tbody key={e.id}>
                                            <tr>
                                                <th>{e.sucursal?e.sucursal.codigo:null}</th>
                                                <th>{e.fecha?e.fecha:null}</th>
                                                
                                                <td className="borderleft">{e.tasa?e.tasa:null}</td>
                                                <td>{e.tasacop?e.tasacop:null}</td>
                                                
                                                
                                                <td className="borderleft text-success">{e.numventas?e.numventas:null}</td>
                                                <td>{e.debito?e.debito:null}</td>
                                                <td>{e.efectivo?e.efectivo:null}</td>
                                                <td>{e.transferencia?e.transferencia:null}</td>
                                                <td>{e.total?e.total:null}</td>
                                                <td>{e.ganancia?e.ganancia:null}</td>
                                                
                                                <td className="borderleft">{e.numreportez?e.numreportez:null}</td>
                                                <td>{e.ventaexcento?e.ventaexcento:null}</td>
                                                <td>{e.ventagravadas?e.ventagravadas:null}</td>
                                                <td>{e.ivaventa?e.ivaventa:null}</td>
                                                <td>{e.totalventa?e.totalventa:null}</td>
                                                <td>{e.ultimafactura?e.ultimafactura:null}</td>

                                                <td className="borderleft text-sinapsis">{e.inventariobase?e.inventariobase:null}</td>
                                                <td className="text-success">{e.inventarioventa?e.inventarioventa:null}</td>

                                                <td className="borderleft">{e.credito?e.credito:null}</td>
                                                <td>{e.creditoporcobrartotal?e.creditoporcobrartotal:null}</td>
                                                <td>{e.vueltostotales?e.vueltostotales:null}</td>
                                                <td>{e.abonosdeldia?e.abonosdeldia:null}</td>

                                                <td className="borderleft">{e.dejar_bss?e.dejar_bss:null}</td>
                                                <td>{e.dejar_peso?e.dejar_peso:null}</td>
                                                <td>{e.dejar_dolar?e.dejar_dolar:null}</td>
                                                <td></td>

                                                <td className="borderleft">{e.efectivo_guardado_bs?e.efectivo_guardado_bs:null}</td>
                                                <td>{e.efectivo_guardado_cop?e.efectivo_guardado_cop:null}</td>
                                                <td>{e.efectivo_guardado?e.efectivo_guardado:null}</td>
                                                <td></td>

                                                
                                                
                                                {/* <td>{e.caja_biopago?e.caja_biopago:null}</td>
                                                <td>{e.puntodeventa_actual_bs?e.puntodeventa_actual_bs:null}</td>
                                                <td>{e.precio?e.precio:null}</td>
                                                <td>{e.precio_base?e.precio_base:null}</td>
                                                <td>{e.porcentaje?e.porcentaje:null}</td>
                                                <td>{e.desc_total?e.desc_total:null}</td> */}

                                                <td className="borderleft">{e.nota?e.nota:null}</td>
                                            </tr>
                                        </tbody>
                                    )}
                                    <tbody>
                                        <tr>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.numero:""}</td>
                                                <td></td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.tasa:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.tasacop:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.numventas:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.debito:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.efectivo:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.transferencia:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.total:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.ganancia:""}</td>
                                            <td></td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.ventaexcento:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.ventagravadas:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.ivaventa:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.totalventa:""}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.credito:""}</td>
                                            <td></td>
                                            <td></td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.abonosdeldia:""}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.efectivo_guardado_bs:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.efectivo_guardado_cop:""}</td>
                                            <td>{sucursalDetallesData.sum?sucursalDetallesData.sum.efectivo_guardado:""}</td>
                                            <td></td>

                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>  
                                </>
                                :null
                            :null
                            } 
                    </table>
                </div>
            </div>
        </div>
    )
}