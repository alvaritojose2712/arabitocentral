export default function Puntosyseriales({
    getsucursalDetallesData,
    sucursalDetallesData,
    changeLiquidacionPagoElec,

    fechaSelectAuditoria,
    setfechaSelectAuditoria,
    BancoSelectAuditoria,
    setBancoSelectAuditoria,
    SaldoInicialSelectAuditoria,
    setSaldoInicialSelectAuditoria,
    SaldoActualSelectAuditoria,
    setSaldoActualSelectAuditoria,
}){
    return (
        <div>
            <table className="table">
                <thead>
                    <tr>
                        <th>ACCIÓN</th>
                        <th>LIQUIDACIÓN</th>
                        <th>FECHA</th>
                        <th>SUCURSAL</th>
                        <th>ID_USUARIO / ID_PEDIDO</th>
                        <th>TIPO</th>
                        <th>BANCO</th>
                        <th>MONTO</th>
                        <th>REF / LOTE / SERIAL</th>
                    </tr>
                </thead>
                <tbody>
                    {sucursalDetallesData.data?sucursalDetallesData.data.length?sucursalDetallesData.data.map(e=>
                        <tr key={e.id} className={(e.fecha_liquidacion?"bg-success-light":"bg-danger-light")}>
                            <td><button className="btn-success btn" onClick={()=>changeLiquidacionPagoElec(e.id)}>LIQUIDAR</button></td>
                            <td>{e.fecha_liquidacion}</td>
                            <td>{e.fecha}</td>
                            <td>{e.sucursal.codigo}</td>
                            <td>{e.id_usuario}</td>
                            <td>{e.tipo}</td>
                            <td>{e.banco}</td>
                            <td>{e.monto}</td>
                            <td>{e.loteserial}</td>
                        </tr>    
                    ):null:null}
                </tbody>
            </table>

            <table className="table">
                <tbody>
                    <tr>
                        <th colSpan={3}><h1>AUDITORÍA</h1></th>
                    </tr>
                    <tr>
                        <th>FECHA</th>
                        <th>BANCO</th>
                        <th>CAJA INICIAL</th>
                        <th>INGRESO</th>
                        <th>EGRESOS</th>
                        <th>SALDO ACTUAL</th>
                        <th>CUADRE</th>
                    </tr>
                    <tr>
                        <td>
                            <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={e=>setfechaSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <select className="form-control" value={BancoSelectAuditoria} onChange={e=>setBancoSelectAuditoria(e.target.value)} >
                                <option value="0134">BANESCO</option>
                                <option value="0102">BDV</option>
                                <option value="0191">BNC</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo inicial" value={SaldoInicialSelectAuditoria} onChange={e=>setSaldoInicialSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <span className="text-success fs-4">23.230,56</span>
                        </td>
                        <td>
                            <span className="text-danger fs-4">23.236,56</span>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo Actual" value={SaldoActualSelectAuditoria} onChange={e=>setSaldoActualSelectAuditoria(e.target.value)} />
                        </td>
                        

                        <td className="bg-success text-center fs-4">
                           0.05 
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={e=>setfechaSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <select className="form-control" value={BancoSelectAuditoria} onChange={e=>setBancoSelectAuditoria(e.target.value)} >
                                <option value="0134">BANESCO</option>
                                <option value="0102">BDV</option>
                                <option value="0191">BNC</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo inicial" value={SaldoInicialSelectAuditoria} onChange={e=>setSaldoInicialSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <span className="text-success fs-4">23.230,56</span>
                        </td>
                        <td>
                            <span className="text-danger fs-4">23.236,56</span>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo Actual" value={SaldoActualSelectAuditoria} onChange={e=>setSaldoActualSelectAuditoria(e.target.value)} />
                        </td>
                        

                        <td className="bg-success text-center fs-4">
                           0.05 
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={e=>setfechaSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <select className="form-control" value={BancoSelectAuditoria} onChange={e=>setBancoSelectAuditoria(e.target.value)} >
                                <option value="0134">BANESCO</option>
                                <option value="0102">BDV</option>
                                <option value="0191">BNC</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo inicial" value={SaldoInicialSelectAuditoria} onChange={e=>setSaldoInicialSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <span className="text-success fs-4">23.230,56</span>
                        </td>
                        <td>
                            <span className="text-danger fs-4">23.236,56</span>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo Actual" value={SaldoActualSelectAuditoria} onChange={e=>setSaldoActualSelectAuditoria(e.target.value)} />
                        </td>
                        

                        <td className="bg-success text-center fs-4">
                           0.05 
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={e=>setfechaSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <select className="form-control" value={BancoSelectAuditoria} onChange={e=>setBancoSelectAuditoria(e.target.value)} >
                                <option value="0134">BANESCO</option>
                                <option value="0102">BDV</option>
                                <option value="0191">BNC</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo inicial" value={SaldoInicialSelectAuditoria} onChange={e=>setSaldoInicialSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <span className="text-success fs-4">23.230,56</span>
                        </td>
                        <td>
                            <span className="text-danger fs-4">23.236,56</span>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo Actual" value={SaldoActualSelectAuditoria} onChange={e=>setSaldoActualSelectAuditoria(e.target.value)} />
                        </td>
                        

                        <td className="bg-success text-center fs-4">
                           0.05 
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={e=>setfechaSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <select className="form-control" value={BancoSelectAuditoria} onChange={e=>setBancoSelectAuditoria(e.target.value)} >
                                <option value="0134">BANESCO</option>
                                <option value="0102">BDV</option>
                                <option value="0191">BNC</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo inicial" value={SaldoInicialSelectAuditoria} onChange={e=>setSaldoInicialSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <span className="text-success fs-4">23.230,56</span>
                        </td>
                        <td>
                            <span className="text-danger fs-4">23.236,56</span>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo Actual" value={SaldoActualSelectAuditoria} onChange={e=>setSaldoActualSelectAuditoria(e.target.value)} />
                        </td>
                        

                        <td className="bg-success text-center fs-4">
                           0.05 
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={e=>setfechaSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <select className="form-control" value={BancoSelectAuditoria} onChange={e=>setBancoSelectAuditoria(e.target.value)} >
                                <option value="0134">BANESCO</option>
                                <option value="0102">BDV</option>
                                <option value="0191">BNC</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo inicial" value={SaldoInicialSelectAuditoria} onChange={e=>setSaldoInicialSelectAuditoria(e.target.value)} />
                        </td>
                        <td>
                            <span className="text-success fs-4">23.230,56</span>
                        </td>
                        <td>
                            <span className="text-danger fs-4">23.236,56</span>
                        </td>
                        <td>
                            <input type="text" className="form-control" placeholder="Saldo Actual" value={SaldoActualSelectAuditoria} onChange={e=>setSaldoActualSelectAuditoria(e.target.value)} />
                        </td>
                        

                        <td className="bg-success text-center fs-4">
                           0.05 
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    )
}