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
    const [showcaja_biopagodetalles, setshowcaja_biopagodetalles] = useState(null)
    
    const [showpago_proveedoresdetalles, setshowpago_proveedoresdetalles] = useState(null)

    const [showgastosdetalles, setshowgastosdetalles] = useState(null)
    const [showgastos_var_fijosdetalles, setshowgastos_var_fijosdetalles] = useState(null)

    const [showcaja_inicialdetalles, setshowcaja_inicialdetalles] = useState(null)
    const [showsubcaja_inicialdetalles, setshowsubcaja_inicialdetalles] = useState(null)

    

    return  <div className='container-fluid'>

        <div className="form-group">
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
        {datacuadregeneral.sum_efectivo?
        <div className="row">
            <div className="col w-50">
                <div className="card m-3 p-3">
                    <h2>INGRESOS</h2>
                    <table className="table">
                        <tr onClick={()=>setshowefectivodetalles(showefectivodetalles==1?0:1)}>
                            <th colSpan={2} className="">
                                <button className="btn bg-success-1 w-200px">EFECTIVO</button>
                            </th>
                            <td className="text-right text-success">{moneda(datacuadregeneral.sum_efectivo)}</td>
                        </tr>
                        {showefectivodetalles?
                            Object.entries(datacuadregeneral.efectivo).map((e,i)=>
                                <tr key={i}>
                                    <th colSpan={2} className="">
                                        <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                            {e[0]}
                                        </button>
                                    </th>
                                    <td className="text-right text-muted">{moneda(e[1])}</td>
                                </tr>
                            )
                        :null}

                        <tr onClick={()=>setshowdebitodetalles(showdebitodetalles==1?0:1)}>
                            <th colSpan={2} className="">
                                <button className="btn bg-success-2 w-200px">DÉBITO</button>
                            </th>
                            <td className="text-right text-success">
                                <span className="text-muted">Bs. {moneda(datacuadregeneral.sum_debito)}</span>
                                /
                                <span className="text-success"> $ {moneda(datacuadregeneral.sum_debito_dolar)}</span>
                            </td>
                        </tr>

                        {showdebitodetalles?
                            Object.entries(datacuadregeneral.debito).map((e,i)=>
                                <>
                                    <tr key={i} onClick={()=>setshowdebito_bancosdetalles(showdebito_bancosdetalles==1?0:1)}>
                                        <th colSpan={2} className="">
                                            <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e[0])}}>
                                                {e[0]}
                                            </button>
                                        </th>
                                        <td className="text-right text-muted">
                                            <span className="text-muted">Bs. {moneda(e[1]["sum_debitos"])}</span>
                                            /
                                            <span className="text-success"> $ {moneda(e[1]["sum_debitos_dolar"])}</span>
                                        </td>
                                    </tr>
                                    {showdebito_bancosdetalles?Object.entries(e[1]["bancos_debito"]).map((ee,ii)=>
                                        <tr key={ii}>
                                            <th colSpan={2} className="">
                                                    
                                                <button className="btn w-100 fw-bolder" 
                                                style={{
                                                    backgroundColor:colors[ee[0]]?colors[ee[0]]:"", 
                                                    color:colors[ee[0]]?colors[ee[0]]:""
                                                }}
                                                >{ee[0]}</button>
                                            </th>
                                            <td className="text-right text-muted">
                                                <span className="text-muted">Bs. {moneda(ee[1]["bs"])}</span>
                                                /
                                                <span className="text-success"> $ {moneda(ee[1]["dolar"])}</span>
                                            </td>
                                        </tr>
                                    ):null}
                                </>
                            )
                        :null}

                        <tr>
                            <th colSpan={2} className="">
                                <button className="btn bg-success-3 w-200px">TRANSFERENCIA</button>
                            </th>
                            <td className="text-right text-success">
                                <span className="text-muted">Bs. {moneda(datacuadregeneral.sum_transferencia)}</span>
                                /
                                <span className="text-success"> $ {moneda(datacuadregeneral.sum_transferencia_dolar)}</span>
                            </td>
                        </tr>

                        <tr>
                            <th colSpan={2} className="">
                                <button className="btn bg-success-4 w-200px">BIOPAGO</button>
                            </th>
                            <td className="text-right text-success">
                                <span className="text-muted">Bs. {moneda(datacuadregeneral.sum_caja_biopago)}</span>
                                /
                                <span className="text-success"> $ {moneda(datacuadregeneral.sum_caja_biopago_dolar)}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div className="col w-50">
                <h2>EGRESO</h2>
            </div>


        </div>  :null}    
    </div>

}