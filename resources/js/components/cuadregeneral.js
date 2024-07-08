import { useEffect , useState} from "react";


export default function cuadregeneraltivo({
    sucursalqcuadregeneral,
    setsucursalqcuadregeneral,
    fechadesdeqcuadregeneral,
    setfechadesdeqcuadregeneral,
    fechahastaqcuadregeneral,
    setfechahastaqcuadregeneral,
    datacuadregeneral,
    getCuadreGeneral,
    moneda,
    number,
    formatAmount,
    sucursales,

    colorSucursal,
    colorsGastosCat,
    colors,
}){

    const [showefectivodetalles, setshowefectivodetalles] = useState(null)
    const [showdebitodetalles, setshowdebitodetalles] = useState(null)
    const [showdebito_bancosdetalles, setshowdebito_bancosdetalles] = useState(null)
    const [showtransferencia_bancosdetalles, setshowtransferencia_bancosdetalles] = useState(null)
    const [showtransferenciadetalles, setshowtransferenciadetalles] = useState(null)
    const [showcaja_biopagodetalles, setshowcaja_biopagodetalles] = useState(null)
    
    const [showpago_proveedoresdetalles, setshowpago_proveedoresdetalles] = useState(null)
    const [subshowpago_proveedoresdetalles, setsubshowpago_proveedoresdetalles] = useState(null)

    const [showgastosdetalles, setshowgastosdetalles] = useState(null)
    const [showgastos_var_fijosdetalles, setshowgastos_var_fijosdetalles] = useState(null)
    const [showsubgastos_var_fijosdetalles, setshowsubgastos_var_fijosdetalles] = useState(null)

    const [showcaja_inicialdetalles, setshowcaja_inicialdetalles] = useState(null)
    const [showsubcaja_inicialdetalles, setshowsubcaja_inicialdetalles] = useState(null)

    

    return  <div className='container-fluid'>
        <div className="d-flex justify-content-center">
            <div className="form-group w-50">
                <form className="input-group" onSubmit={event=>{event.preventDefault();getCuadreGeneral()}}>
                    <select className="form-control form-control-lg" value={sucursalqcuadregeneral} onChange={e=>setsucursalqcuadregeneral(e.target.value)}>
                        <option value="">-SUCURSAL-</option>
                        {sucursales.map(e=>
                            <option key={e.id} value={e.id}>{e.codigo}</option>
                        )}
                    </select>
                    <input type="date" className="form-control" value={fechadesdeqcuadregeneral} onChange={e=>setfechadesdeqcuadregeneral(e.target.value)}  />
                {/*    <input type="date" className="form-control" value={fechahastaqcuadregeneral} onChange={e=>setfechahastaqcuadregeneral(e.target.value)}  /> */}
                    <button className="btn btn-success"><i className="fa fa-search"></i></button>
                </form>
            </div>
        </div>

        {datacuadregeneral.sum_efectivo?
            <>
                <div className="row mt-2">
                    <div className="col w-50">
                        <div className="bg-success-superlight card p-3">
                            <h2 className="text-success fw-bolder text-decoration-underline text-center">INGRESOS</h2>
                            <table className="table">
                                <tr onClick={()=>setshowefectivodetalles(showefectivodetalles==1?0:1)}>
                                    <td colSpan={2} className="">
                                        <button className="btn bg-success-1 w-200px">EFECTIVO</button>
                                    </td>
                                    <td className="text-right text-success fs-2">$ {moneda(datacuadregeneral.sum_efectivo)}</td>
                                </tr>
                                {showefectivodetalles?
                                    Object.entries(datacuadregeneral.efectivo).map((e,i)=>
                                        <tr key={i}>
                                            <td colSpan={2} className="ps-5">
                                                <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                                    {e[0]}
                                                </button>
                                            </td>
                                            <td className="text-right text-success fs-4 pe-5">$ {moneda(e[1])}</td>
                                        </tr>
                                    )
                                :null}

                                <tr onClick={()=>setshowdebitodetalles(showdebitodetalles==1?0:1)}>
                                    <td colSpan={2} className="">
                                        <button className="btn bg-success-2 w-200px">DÉBITO</button>
                                    </td>
                                    <td className="text-right text-success fs-4">
                                        <span className="text-muted">Bs. {moneda(datacuadregeneral.sum_debito)}</span>
                                        /
                                        <span className="text-success fs-2"> $ {moneda(datacuadregeneral.sum_debito_dolar)}</span>
                                    </td>
                                </tr>

                                {showdebitodetalles?
                                    Object.entries(datacuadregeneral.debito).map((e,i)=>
                                        <>
                                            <tr key={i} onClick={()=>setshowdebito_bancosdetalles(showdebito_bancosdetalles==i?null:i)}>
                                                <td colSpan={2} className="ps-5">
                                                    <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                                        {e[0]}
                                                    </button>
                                                </td>
                                                <td className="text-right text-muted pe-5">
                                                    <span className="text-muted fs-5">Bs. {moneda(e[1]["sum_debitos"])}</span>
                                                    /
                                                    <span className="text-success fs-4"> $ {moneda(e[1]["sum_debitos_dolar"])}</span>
                                                </td>
                                            </tr>
                                            {showdebito_bancosdetalles==i?
                                            Object.entries(e[1]["bancos_debito"]).map((ee,ii)=>
                                                <tr key={ii}>
                                                    <td colSpan={2} className="ps-6">
                                                            
                                                        <button className="btn fw-bolder" 
                                                        style={{
                                                            backgroundColor:colors[ee[0]][0], 
                                                            color:colors[ee[0]][1]
                                                        }}
                                                        >{ee[0]}</button>
                                                    </td>
                                                    <td className="text-right text-muted fs-6 pe-6">
                                                        <span className="text-muted">Bs. {moneda(ee[1]["bs"])}</span>
                                                        /
                                                        <span className="text-success"> $ {moneda(ee[1]["dolar"])}</span>
                                                    </td>
                                                </tr>
                                            ):null}
                                        </>
                                    )
                                :null}

                                <tr onClick={()=>setshowtransferenciadetalles(showtransferenciadetalles==1?0:1)}>
                                    <td colSpan={2} className="">
                                        <button className="btn bg-success-3 w-200px">TRANSFERENCIA</button>
                                    </td>
                                    <td className="text-right text-success">
                                        <span className="text-muted fs-4">Bs. {moneda(datacuadregeneral.sum_transferencia)}</span>
                                        /
                                        <span className="text-success fs-2"> $ {moneda(datacuadregeneral.sum_transferencia_dolar)}</span>
                                    </td>
                                </tr>
                                {showtransferenciadetalles?
                                    Object.entries(datacuadregeneral.transferencia).map((e,i)=>
                                        <>
                                            <tr key={i} onClick={()=>setshowtransferencia_bancosdetalles(showtransferencia_bancosdetalles==i?null:i)}>
                                                <td colSpan={2} className="ps-5">
                                                    <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                                        {e[0]}
                                                    </button>
                                                </td>
                                                <td className="text-right text-muted pe-5">
                                                    <span className="text-muted fs-5">Bs. {moneda(e[1]["sum_transferencias"])}</span>
                                                    /
                                                    <span className="text-success fs-4"> $ {moneda(e[1]["sum_transferencias_dolar"])}</span>
                                                </td>
                                            </tr>
                                            {showtransferencia_bancosdetalles==i?Object.entries(e[1]["bancos_transferencias"]).map((ee,ii)=>
                                                <tr key={ii}>
                                                    <td colSpan={2} className="ps-6">
                                                            
                                                        <button className="btn fw-bolder" 
                                                        style={{
                                                            backgroundColor:colors[ee[0]][0], 
                                                            color:colors[ee[0]][1]
                                                        }}
                                                        >{ee[0]}</button>
                                                    </td>
                                                    <td className="text-right text-muted fs-6 pe-6">
                                                        <span className="text-muted">Bs. {moneda(ee[1]["bs"])}</span>
                                                        /
                                                        <span className="text-success"> $ {moneda(ee[1]["dolar"])}</span>
                                                    </td>
                                                </tr>
                                            ):null}
                                        </>
                                    )
                                :null}

                                <tr onClick={()=>setshowcaja_biopagodetalles(showcaja_biopagodetalles==1?0:1)}>
                                    <td colSpan={2} className="">
                                        <button className="btn bg-success-4 w-200px">BIOPAGO</button>
                                    </td>
                                    <td className="text-right text-success">
                                        <span className="text-muted fs-4">Bs. {moneda(datacuadregeneral.sum_caja_biopago)}</span>
                                        /
                                        <span className="text-success fs-2"> $ {moneda(datacuadregeneral.sum_caja_biopago_dolar)}</span>
                                    </td>
                                </tr>

                                {showcaja_biopagodetalles?
                                    Object.entries(datacuadregeneral.caja_biopago).map(e=>
                                        <tr>
                                            <td colSpan={2} className="ps-2">
                                                <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                                    {e[0]}
                                                </button>
                                            </td>
                                            <td className="text-right text-muted pe-2">
                                                <span className=" fs-4"> Bs {moneda(e[1])}</span>
                                            </td>
                                        </tr>

                                    )
                                :null}
                            </table>
                        </div>
                    </div>
                    <div className="col w-50">
                        <div className="bg-danger-superlight card p-3">
                            <h2 className="text-danger fw-bolder text-decoration-underline text-center">EGRESO</h2>

                            <table className="table">
                                <tbody>
                                    <tr onClick={()=>setshowgastosdetalles(showgastosdetalles==1?null:1)}>
                                        <td>
                                            <span className="fs-6 btn" style={{backgroundColor:colorsGastosCat(1,"variable_fijo","color")}}>
                                                GASTOS FIJOS
                                            </span>
                                        </td>
                                        <td className="text-right text-danger fs-2">
                                            $ {moneda(datacuadregeneral.sum_gastos_fijos)}
                                        </td>
                                    </tr>

                                    {showgastosdetalles==1?
                                        Object.entries(datacuadregeneral.gastos_fijos).map((e,i)=>
                                            <>
                                                <tr onClick={()=>setshowgastos_var_fijosdetalles(showgastos_var_fijosdetalles==i?null:i)}>
                                                    <td className="ps-4">
                                                        <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                                            {e[0]}
                                                        </button>
                                                    </td>
                                                    <td className="text-right text-danger fs-4 pe-4">
                                                        $ {moneda(e[1].sum)}
                                                    </td>
                                                </tr>
                                                {showgastos_var_fijosdetalles==i?
                                                    Object.entries(e[1].data).map((ee,ii)=>
                                                        <>
                                                            <tr onClick={()=>setshowsubgastos_var_fijosdetalles(showsubgastos_var_fijosdetalles==ii?null:ii)}>
                                                                <td className="ps-5">
                                                                    <button className={"btn fw-bolder fs-6 btn-sm"} style={{backgroundColor:colorsGastosCat(ee[0],"cat","color")}}>
                                                                        {colorsGastosCat(ee[0],"cat","desc")}
                                                                    </button>
                                                                </td>
                                                                <td className="text-right text-danger fs-5 pe-5">
                                                                    $ {moneda(ee[1].sum)}
                                                                </td>
                                                            </tr>
                                                            {showgastos_var_fijosdetalles==i && showsubgastos_var_fijosdetalles==ii?
                                                                ee[1].data.map((eee,iii)=>
                                                                    <tr>
                                                                        <td className="ps-5">
                                                                            {eee.concepto}
                                                                            <br />
                                                                            <span className="text-muted">{eee.created_at}</span>
                                                                        </td>
                                                                        <td className="text-right text-muted fs-5 pe-5">
                                                                            $ {moneda(eee.montodolar)}
                                                                        </td>
                                                                    </tr>
                                                                )
                                                            :null}
                                                        </>
                                                    )
                                                :null}
                                            </>
                                        )
                                    :null}

                                    <tr onClick={()=>setshowgastosdetalles(showgastosdetalles==0?null:0)}>
                                        <td>
                                            <span className="fs-6 btn" style={{backgroundColor:colorsGastosCat(0,"variable_fijo","color")}}>
                                                GASTOS VARIABLES
                                            </span>
                                        </td>
                                        <td className="text-right text-danger fs-2">
                                            $ {moneda(datacuadregeneral.sum_gastos_variables)}
                                        </td>
                                    </tr>

                                    {showgastosdetalles==0?
                                        Object.entries(datacuadregeneral.gastos_variables).map((e,i)=>
                                            <>
                                                <tr onClick={()=>setshowgastos_var_fijosdetalles(showgastos_var_fijosdetalles==i?null:i)}>
                                                    <td className="ps-4">
                                                        <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                                            {e[0]}
                                                        </button>
                                                    </td>
                                                    <td className="text-right text-danger fs-4 pe-4">
                                                        $ {moneda(e[1].sum)}
                                                    </td>
                                                </tr>
                                                {showgastos_var_fijosdetalles==i?
                                                    Object.entries(e[1].data).map((ee,ii)=>
                                                        <>
                                                            <tr onClick={()=>setshowsubgastos_var_fijosdetalles(showsubgastos_var_fijosdetalles==ii?null:ii)}>
                                                                <td className="ps-5">
                                                                    <button className={"btn fw-bolder fs-6 btn-sm"} style={{backgroundColor:colorsGastosCat(ee[0],"cat","color")}}>
                                                                        {colorsGastosCat(ee[0],"cat","desc")}
                                                                    </button>
                                                                </td>
                                                                <td className="text-right text-danger fs-5 pe-5">
                                                                    $ {moneda(ee[1].sum)}
                                                                </td>
                                                            </tr>
                                                            {showgastos_var_fijosdetalles==i && showsubgastos_var_fijosdetalles==ii?
                                                                ee[1].data.map((eee,iii)=>
                                                                    <tr>
                                                                        <td className="ps-5">
                                                                            {eee.concepto}
                                                                            <br />
                                                                            <span className="text-muted">{eee.created_at}</span>
                                                                        </td>
                                                                        <td className="text-right text-muted fs-5 pe-5">
                                                                            $ {moneda(eee.montodolar)}
                                                                        </td>
                                                                    </tr>
                                                                )
                                                            :null}
                                                        </>
                                                    )
                                                :null}
                                            </>
                                        )
                                    :null}
                                    
                                    
                                    <tr onClick={()=>setshowpago_proveedoresdetalles(showpago_proveedoresdetalles==1?null:1)}>
                                        <td>
                                            <span className="fs-6 btn" style={{backgroundColor:colorsGastosCat(0,"catgeneral","color")}}>
                                                PAGO PROVEEDORES
                                            </span>
                                        </td>
                                        <td className="text-right text-danger fs-2">
                                            $ {moneda(datacuadregeneral.sum_pago_proveedores)}
                                        </td>
                                    </tr>
                                    
                                    
                                    {showpago_proveedoresdetalles?
                                    datacuadregeneral.pago_proveedores.map((e,i)=>
                                        <>
                                            <tr onClick={()=>setsubshowpago_proveedoresdetalles(subshowpago_proveedoresdetalles==i?null:i)}>
                                                <td className="fs-3 ps-3">
                                                    <button className="btn btn-sinapsis">{e.descripcion}</button>
                                                </td>
                                                <td className="fs-3 pe-3 text-danger text-right">$ {moneda(e.sum)}</td>
                                            </tr>
                                            {subshowpago_proveedoresdetalles==i?
                                                e.data.map(ee=>
                                                    <tr>
                                                        <td className="ps-5 fs-5">
                                                            {ee.descripcion}
                                                            <br />
                                                            <span className="text-muted">{ee.created_at}</span>
                                                        </td>
                                                        <td className="pe-5 fs-5 text-muted text-right">
                                                            $ {moneda(ee.monto)}
                                                        </td>
                                                    </tr>
                                                ) 
                                            :null}
                                        </>
                                    ):null}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> 
                <div className="row mt-2">
                    <div className="col w-50">
                        <div className="card p-3">
                            <h2 className="text-sinapsis fw-bolder text-decoration-underline text-center">CAJA INICIAL</h2>
                            <table className="table">
                                <tbody>

                                    <tr onClick={()=>setshowcaja_inicialdetalles(showcaja_inicialdetalles=="efectivo"?null:"efectivo")}>
                                        <td>
                                            <button className="btn btn-sinapsis">EFECTIVO</button>
                                        </td>
                                        <td className="text-right">
                                            <span className="text-sinapsis fs-2">
                                                $ {moneda(datacuadregeneral.sum_caja_inicial)}
                                            </span>
                                            <br />
                                            <p>
                                                <span className="text-muted">Fuerte $ {moneda(datacuadregeneral.sum_caja_fuerte)}</span> / <span className="text-sinapsis">Chica $ {moneda(datacuadregeneral.sum_caja_chica)}</span> / <span className="text-success">Registradora $ {moneda(datacuadregeneral.sum_caja_regis)}</span>

                                            </p>
                                                                
                                        </td>
                                    </tr>
                                    {showcaja_inicialdetalles=="efectivo"?
                                        Object.entries(datacuadregeneral.caja_inicial).map((e,i)=>
                                            <>
                                                <tr key={i} onClick={()=>setshowsubcaja_inicialdetalles(showsubcaja_inicialdetalles==i?null:i)}>
                                                    <td className="ps-5">
                                                        <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                                            {e[0]}
                                                        </button>
                                                    </td>
                                                    <td className="text-right text-success fs-4 pe-5">$ {moneda(e[1]["sum_cajas"])}</td>
                                                </tr>
                                                {showsubcaja_inicialdetalles==i?
                                                    <>
                                                        <tr>
                                                            <td className="ps-5">CAJA FUERTE</td>
                                                            <td>
                                                                <span className="text-sinapsis">{moneda(e[1]["caja_fuerte"]["total_dolar"])}</span>
                                                                <br />
                                                                <span className="text-success">$ {moneda(e[1]["caja_fuerte"]["dolar"])}</span> / <span className="text-sinapsis">Bs {moneda(e[1]["caja_fuerte"]["bs"])}</span> / <span className="text-muted">Peso {moneda(e[1]["caja_fuerte"]["peso"])}</span> / <span className="text-muted">Euro {moneda(e[1]["caja_fuerte"]["euro"])}</span> 
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td className="ps-5">CAJA CHICA</td>
                                                            <td>
                                                                <span className="text-sinapsis">{moneda(e[1]["caja_chica"]["total_dolar"])}</span>
                                                                <br />
                                                                <span className="text-success">$ {moneda(e[1]["caja_chica"]["dolar"])}</span> / <span className="text-sinapsis">Bs {moneda(e[1]["caja_chica"]["bs"])}</span> / <span className="text-muted">Peso {moneda(e[1]["caja_chica"]["peso"])}</span> / <span className="text-muted">Euro {moneda(e[1]["caja_chica"]["euro"])}</span> 
                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td className="ps-5">CAJA REGISTRADORA</td>
                                                            <td>
                                                                <span className="text-sinapsis">{moneda(e[1]["caja_registradora"]["total_dolar"])}</span>
                                                                <br />
                                                                <span className="text-success">$ {moneda(e[1]["caja_registradora"]["dolar"])}</span> / <span className="text-sinapsis">Bs {moneda(e[1]["caja_registradora"]["bs"])}</span> / <span className="text-muted">Peso {moneda(e[1]["caja_registradora"]["peso"])}</span> / <span className="text-muted">Euro {moneda(e[1]["caja_registradora"]["euro"])}</span> 
                                                                
                                                            </td>
                                                        </tr>
                                                    </>
                                                :null}
                                            </>
                                        )
                                    :null}

                                    <tr onClick={()=>setshowcaja_inicialdetalles(showcaja_inicialdetalles=="banco"?null:"banco")}>
                                        <td>
                                            <button className="btn btn-primary">BANCO</button>
                                        </td>
                                        <td className="text-right">
                                            <span className="text-muted fs-4">Bs. {moneda(datacuadregeneral.sum_caja_inicial_banco)}</span> / <span className="text-success fs-2"> $ {moneda(datacuadregeneral.sum_caja_inicial_banco_dolar)}</span>
                                        </td>
                                    </tr>
                                    {showcaja_inicialdetalles=="banco"?
                                        datacuadregeneral.caja_inicial_banco.map(e=>
                                            <tr>
                                                <td className="ps-3">
                                                    <button className="btn fw-bolder" 
                                                    style={{
                                                        backgroundColor:colors[e.banco][0], 
                                                        color:colors[e.banco][1]
                                                    }}
                                                    >{e.banco}</button>
                                                </td>
                                                <td className="pe-3 text-right">
                                                    <span className="text-muted fs-5">Bs. {moneda(e["saldo"])}</span> / <span className="text-success fs-4"> $ {moneda(e["saldo_dolar"])}</span>
                                                </td>
                                            </tr>
                                        )
                                    :null}


                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div className="w-50">
                        <div className="card p-3">
                            <h2 className="text-secondary fw-bolder text-decoration-underline text-center">DEBO TENER</h2>

                            <table className="table">
                                <tbody>
                                    <tr>
                                        <td colSpan={2} className="">
                                            <button className="btn bg-success-3 w-200px">TOTAL INGRESOS</button>
                                        </td>
                                        <td className="text-right">
                                            <span className="fs-2 text-success">{moneda(datacuadregeneral.total_ingresos)}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colSpan={2} className="">
                                            <button className="btn bg-sinapsis w-200px">TOTAL CAJA INICIAL</button>
                                        </td>
                                        <td className="text-right">
                                            <span className="fs-2 text-sinapsis">{moneda(datacuadregeneral.total_caja_inicial)}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colSpan={2} className="">
                                            <button className="btn bg-danger w-200px">TOTAL EGRESOS</button>
                                        </td>
                                        <td className="text-right">
                                            <span className="fs-2 text-danger">{moneda(datacuadregeneral.total_egresos)}</span>
                                        </td>
                                    </tr>


                                    <tr>
                                        <td colSpan={2} className="">
                                            <button className="btn bg-primary w-200px">DEBO TENER</button>
                                        </td>
                                        <td className="text-right">
                                            <span className="fs-1 text-primary">{moneda(datacuadregeneral.cuantodebotener)}</span>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            
                            
                            
                            
                        </div>
                    </div>
                </div> 
            </>
        :null}    
    </div>

}