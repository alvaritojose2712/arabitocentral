export default function CargarTraspasos({
    sendMovimientoBanco,
    cuentasPagosDescripcion,
    setcuentasPagosDescripcion,
    cuentasPagosMonto,
    setcuentasPagosMonto,
    setiscomisiongasto,
    iscomisiongasto,
    comisionpagomovilinterban,
    setcomisionpagomovilinterban,
    cuentasPagosFecha,
    setcuentasPagosFecha,
    cuentasPagosMetodo,
    setcuentasPagosMetodo,
    cuentasPagosMetodoDestino,
    setcuentasPagosMetodoDestino,
    opcionesMetodosPago,
    number,
}){
    return (
        <div className="container">

            <form onSubmit={sendMovimientoBanco}>
                <div className="form-group mb-1">
                    <span className="text-label fs-4 cell3">Traspaso entre Cuentas</span>
                    <input type="text" className="form-control" placeholder="Referencia" value={cuentasPagosDescripcion} onChange={e=>setcuentasPagosDescripcion(e.target.value)} />
                </div>

                <div className="form-group mb-1">
                    <span className="text-label fs-4 cell3">Monto</span>
                    <input type="text" className="form-control fs-3 text-success" placeholder="Monto" value={cuentasPagosMonto} onChange={e=>setcuentasPagosMonto(number(e.target.value))} />

                    <div className="input-group w-50 mt-1">
                        <button type="button" className={("btn btn")+(iscomisiongasto==1?"-success":"-danger")} onClick={()=>setiscomisiongasto(iscomisiongasto==1?0:1)}>Genera Comisión</button>
                        {iscomisiongasto?
                            <input type="text" disabled={true} className="form-control" size={5} placeholder="% Comión" value={comisionpagomovilinterban} onChange={event=>setcomisionpagomovilinterban(event.preventDefault())}/>
                        :null}
                    </div>
                </div>
                <div className="form-group mb-1">
                    <span className="text-label fs-4 cell3">Fecha</span>
                    <input type="date" className="form-control" value={cuentasPagosFecha} onChange={e=>setcuentasPagosFecha(e.target.value)} />
                </div>
                {/* <div className="form-group mb-1">
                    <span className="text-label fs-4 cell3">Método</span>
                    <select className="form-control" value={cuentasPagosPuntooTranfe} onChange={e=>setcuentasPagosPuntooTranfe((e.target.value))}>
                        <option value="">-</option>
                        <option value="PUNTO">PUNTO</option>
                        <option value="Transferencia">TRANSFERENCIA</option>
                        <option value="BioPago">BIOPAGO</option>
                    </select>
                </div> */}

                {/* <div className="form-group mb-1">
                    <span className="text-label fs-4 cell3">Sucursal</span>
                    <select className="form-control" 
                    value={cuentasPagosSucursal} 
                    onChange={e=>setcuentasPagosSucursal(e.target.value)}>
                        <option value="">-SUCURSAL-</option>
                            {sucursales.map(e=>
                                <option key={e.id} value={e.id}>{e.codigo}</option>
                            )}
                    </select>
                </div> */}

                {/* <div className="form-group m-4 text-center">
                    <div className="btn-group">
                        <button type="button" onClick={()=>setcuentasPagosTipo("egreso")} className={(cuentasPagoTipo=="egreso"?"btn-danger":"")+(" btn")}>Egreso</button>
                        <button type="button" onClick={()=>setcuentasPagosTipo("ingreso")} className={(cuentasPagoTipo=="ingreso"?"btn-success":"")+(" btn")}>Ingreso</button>
                    </div>
                </div> */}
                
                <div className="input-group">
                    <select className="form-control" 
                    value={cuentasPagosMetodo} 
                    onChange={e=>setcuentasPagosMetodo(e.target.value)}>
                        <option value="">-Banco Origen-</option>
                        {opcionesMetodosPago.filter(e=>e.codigo!="EFECTIVO").map(e=>
                            <option value={e.id} key={e.id}>{e.descripcion}</option>
                        )}
                    </select>

                    <select className="form-control" 
                    value={cuentasPagosMetodoDestino} 
                    onChange={e=>setcuentasPagosMetodoDestino(e.target.value)}>
                        <option value="">-Banco Destino-</option>
                        {opcionesMetodosPago.filter(e=>e.codigo!="EFECTIVO").map(e=>
                            <option value={e.id} key={e.id}>{e.descripcion}</option>
                        )}
                    </select>

                    {/* <select className="form-control" 
                    value={cuentasPagosCategoria} 
                    onChange={e=>setcuentasPagosCategoria(e.target.value)}>
                        <option value="">-Categoría-</option>
                        {categoriasCajas.map(e=>
                            <option value={e.id} key={e.id}>{e.nombre}</option>
                        )}
                    </select> */}
                </div>

                <div className="form-group w-100 text-center">
                    <button className="mt-2 btn btn-outline-success fs-3 btn-lg" type="submit">Guardar Traspaso <i className="fa fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    )
}