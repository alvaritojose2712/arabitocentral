export default function listBanco({
    opcionesMetodosPago,
    categoriasCajas,
    sucursales,
    colorsGastosCat,
    moneda,
    setcontrolbancoQ,
    controlbancoQ,
    setcontrolbancoQCategoria,
    controlbancoQCategoria,
    setcontrolbancoQDesde,
    controlbancoQDesde,
    setcontrolbancoQHasta,
    controlbancoQHasta,
    controlbancoQBanco,
    setcontrolbancoQBanco,
    controlbancoQSiliquidado,
    setcontrolbancoQSiliquidado,
    movBancosData,
    getMovBancos,
    controlbancoQSucursal,
    setcontrolbancoQSucursal,
    colors,
    colorSucursal,
    number,
}){
    return (
        <>
            <form className="input-group mb-3" onSubmit={event=>{event.preventDefault();getMovBancos()}}>

                <input type="text" className="form-control fs-3"
                    placeholder="Buscar..."
                    onChange={e => setcontrolbancoQ(e.target.value)}
                    value={controlbancoQ} />
                <select
                    className="form-control fs-3"
                    onChange={e => setcontrolbancoQCategoria(e.target.value)}
                    value={controlbancoQCategoria}>
                        <option value="">-CATEGORÍA-</option>
                    {categoriasCajas.map((e,i)=>
                        <option key={i} value={e.id}>{e.nombre}</option>
                    )}

                </select>
                <select className="form-control fs-3" value={controlbancoQBanco}  onChange={event=>setcontrolbancoQBanco(event.target.value)}>
                    <option value="">-BANCO-</option>
                    {opcionesMetodosPago.map(e=>
                        <option key={e.id} value={e.codigo}>{e.codigo}</option>
                    )}
                </select>
                <select className="form-control fs-3" value={controlbancoQSucursal} onChange={e=>setcontrolbancoQSucursal(e.target.value)}>
                    <option value="">-SUCURSAL-</option>
                    {sucursales.map(e=>
                        <option key={e.id} value={e.id}>{e.codigo}</option>
                    )}
                </select>
                {/* <button type="button" className={("btn ")+(controlbancoQSiliquidado==1?"btn-success":"btn-danger")} onClick={()=>setcontrolbancoQSiliquidado(controlbancoQSiliquidado==1?0:1)}>
                    NULOS {controlbancoQSiliquidado==1?<i className="fa fa-exclamation-triangle"></i>:null}
                </button> */}

                <input type="date" className="form-control fs-3"
                    onChange={e => setcontrolbancoQDesde(e.target.value)}
                    value={controlbancoQDesde} />

                <input type="date" className="form-control fs-3"
                    onChange={e => setcontrolbancoQHasta(e.target.value)}
                    value={controlbancoQHasta} />

                <button className="btn btn-outline-secondary" type="submit"><i className="fa fa-search"></i></button>
            </form>
            <table className="table">
                <thead>
                    <tr>
                        <th>SUCURSAL</th>
                        <th>ORIGEN</th>
                        <th>FECHA</th>
                        <th>FECHA LIQUIDACIÓN</th>
                        <th>BANCO</th>
                     {/*    <th>BENEFICIARIO</th> */}
                        <th colSpan={3}>CATEGORIA</th>
                        <th>LOTE / SERIAL / DESC</th>
                        <th>TIPO</th>
                        {/* <th>DEBITO_CREDITO</th> */}
                        <th>MONTO</th>
                        <th>MONTO LIQUIDADO</th>
                        <th>TASA</th>
                        <th>MONTO DOLAR</th>
                    </tr>
                </thead>
                <tbody>
                    {
                    movBancosData.data?movBancosData.data.map(e=>
                        <tr key={e.id}>
                            {/* <td>{e.id_usuario}</td> */}
                            <td>
                                <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>
                                    {e.sucursal.codigo}
                                </button>
                            </td>
                            <td>{e.origen==2?"ADMINISTRACIÓN":"SUCURSAL"}</td>
                            <td>{e.fecha}</td>
                            <td>{e.fecha_liquidacion}</td>
                            <td>

                                <button className="btn w-100 fw-bolder" 
                                style={{
                                    backgroundColor:colors[e.banco]?colors[e.banco][0]:"", 
                                    color:colors[e.banco]?colors[e.banco][1]:""
                                }}>{e.banco}</button>
                            </td>
                            {/* <td>
                                {e.beneficiario?" / "+e.beneficiario.nominanombre:"-"}    
                                </td> */}
                            <td>
                                <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.id,"cat","color")}}>
                                    {colorsGastosCat(e.cat.id,"cat","desc")}
                                </button>
                            </td>
                            <td>
                            <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.catgeneral,"catgeneral","color")}}>
                                {colorsGastosCat(e.cat.catgeneral,"catgeneral","desc")}
                            </button>

                            </td>
                            <td>
                                <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorsGastosCat(e.cat.ingreso_egreso,"ingreso_egreso","color")}}>
                                    {colorsGastosCat(e.cat.ingreso_egreso,"ingreso_egreso","desc")}
                                </button>
                            </td>
                                <td>
                                    {e.loteserial}
    
                                    {e.beneficiario?
                                        <>
                                            <br />
                                            <b>({e.beneficiario.nominanombre})</b>
                                        </>
                                    :null}
                                </td>
                            <td>{e.tipo}</td>
                            {/* <td>{e.debito_credito}</td> */}
                            <td className={(e.monto<0? "text-danger": "text-success")}>
                                {moneda(e.monto)}
                            </td>
                            <td  className={(e.monto_liquidado<0? "text-danger": "text-success")}>{moneda(e.monto_liquidado)}</td>
                            <td>{moneda(e.tasa)}</td>
                            <td className="">{e.monto_dolar?moneda(e.monto_dolar):moneda(e.monto_liquidado/e.tasa)}</td>
                        </tr>
                    ):null  
                    }
                </tbody>
            </table>
        </>
    )
}