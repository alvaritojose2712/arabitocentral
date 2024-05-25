export default function Trmaincuentasporpagar({
e,
i,
selectFactViewDetalles,
dateFormat,
colorSucursal,
moneda,
abonarFact,
showImageFact,
setSelectCuentaPorPagarDetalle,
changeSucursal,
viewAvanzatedShow,
returnCondicion,
dataselectFacts,
setselectFactViewDetalles,
selectFacts,
}){
    return <>
        <tr className={(selectFactViewDetalles==e.id?"bg-success-light":null)+(" pointer border-top border-top-5 border-dark")} onDoubleClick={event=>selectFacts(event,e.id)} onClick={()=>setselectFactViewDetalles(selectFactViewDetalles==e.id?null:e.id)}>
            
                {viewAvanzatedShow?<>
                    <td className="">
                        <small className="text-muted">{e.created_at}</small>
                    </td> 
                    <td className="">
                        <small className="text-muted">{e.updated_at}</small>
                    </td>
                </>:null}

                
                <td>
                    {i+1}
                </td>
                <td className="text-right fs-4">
                    <span className="text-successfuerte">{dateFormat(e.fechaemision,"dd-MM-yyyy")}</span>
                </td>       
                <td className="text-right fs-4">
                    <span className="text-danger ms-1">{dateFormat(e.fechavencimiento,"dd-MM-yyyy")} <br />
                        <span className={(e.dias<0? "text-danger": "text-success")+(" ")}>({e.dias} días)</span>
                    </span>
                </td>  

                <td className="text-right">
                    <span className="fw-bold fs-4">{e.proveedor?e.proveedor.descripcion:null}</span>
                </td>  
                <td>
                    <span className="m-2">
                        {e.aprobado==0?<i className="fa-2x fa fa-clock-o text-sinapsis"></i>:<i className="fa fa-check text-success"></i>} 
                    </span>
                </td>
                <td className="text-right">
                    
                    {/* <input type="checkbox" className="form-check-input me-1 fs-2" onMouseEnter={event=>selectFacts(event,e.id,"leave")} onChange={event=>selectFacts(event,e.id)} checked={dataselectFacts.data.filter(selefil =>selefil.id==e.id).length?
                        true
                    :false} /> */}
                    <span className={(returnCondicion(e.condicion))+(" w-100 btn fs-2 pointer fw-bolder text-light ")+(dataselectFacts.data.filter(fil=>fil.id == e.id).length?"border-select":"")}> 
                        {e.numfact}
                    </span>
                </td>  
                <td className=" text-right">
                    <button className={"btn w-100 fw-bolder fs-3"} style={{backgroundColor:colorSucursal(e.sucursal?e.sucursal.codigo:"")}}>
                        {e.sucursal?e.sucursal.codigo:""}
                    </button>
                </td>
                <td className=" text-right">
                    <span className="text-muted fs-6">{moneda(e.monto_bruto)}</span>
                </td>
                <td className=" text-right">
                    {
                        e.monto_descuento!="" && e.monto_descuento!="0"?
                            <span className="text-muted fst-italic fs-6">{moneda(e.monto_descuento)} <br /> ({e.descuento}%)</span>
                        :null
                    }
                </td>

                <td className=" text-right">
                    {selectFactViewDetalles!=e.id || !e.pagos.length?
                    <>
                        <span className={(e.monto<0? "text-danger": "text-success")+(" fs-3 fw-bold ")}>{moneda(e.monto)}</span>
                    </>:null}
                </td>
                <td className="text-right">
                {selectFactViewDetalles!=e.id || !e.pagos.length?
                    <>
                        {e.monto_abonado?
                            <>
                                <span className={(e.monto_abonado<0? "text-danger": "text-success")+(" fs-3 fw-bold")}> {moneda(e.monto_abonado)}</span>
                            </>
                            : null 
                        }
                        
                    </>
                :null}
                </td>
                <td className="text-right">
                    {selectFactViewDetalles!=e.id || !e.pagos.length?
                        <>
                            {e.monto<0?
                                <span className={(e.balance<0? "text-danger": "text-success")+(" fs-3 fw-bold")}> {moneda(e.balance)}</span>
                            :null}
                        </>
                    :null}
                </td>
            

        </tr>
        {selectFactViewDetalles==e.id?
            <>
                
                <tr className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5"}>
                    <th colSpan={10} className="text-center">
                        <div className="btn-group">
                            {e.condicion!="pagadas" && e.condicion!="abonos"?<button className="btn btn-outline-success" onClick={()=>abonarFact(e.id_proveedor,e.id)}>
                                <i className=" fa fa-credit-card"></i>
                                PAGAR 
                            </button>:null}
                            <button className="btn btn-outline-info" onClick={()=>showImageFact(e.descripcion)}> <i className="fa fa-eye"></i> VER </button>
                            <button className="btn btn-outline-sinapsis" onClick={()=>setSelectCuentaPorPagarDetalle(e.id)}> <i className="fa fa-pencil"></i> EDITAR </button>
                            
                            <button className="btn" onDoubleClick={()=>changeSucursal(e.id)}> {e.sucursal?e.sucursal.codigo:""} </button>
                            
                        </div>
                    </th>

                </tr>

                {e.pagos.length?
                    <>
                        <tr className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5"}>
                            <th colSpan={5}></th>

                            <th className="align-middle" colSpan={3}>
                                DEUDA                                        
                            </th>
                            <td colSpan={4} className="text-danger text-right align-middle fs-3">
                                {moneda(e.monto)}
                            </td>
                        </tr>
                        {e.monto_abonado && e.pagos?
                        <>
                            {e.pagos.map(pago=>
                                    <tr key={pago.id} className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5 align-middle"}>
                                        <th colSpan={4}></th>
                                        <td className="text-muted fst-italic text-right" colSpan={2}>
                                            {pago.created_at}
                                        </td>
                                        <td className="align-middle text-muted fst-italic text-right" colSpan={2}>
                                            PAGO REALIZADO <i className="fa fa-check text-success"></i>
                                        </td>
                                        <td className="align-middle" colSpan={2}>
                                            <span className="btn-success btn pointer w-100">
                                                {pago.numfact}
                                            </span> 
                                        </td>
                                        <td className="text-right fs-5" colSpan={2}>
                                            <span className="text-sinapsis">{moneda(pago.monto)}</span> / <span className="text-success">{moneda(pago.pivot.monto)}</span>
                                        </td>
                                    </tr>
                                )}
                        </>
                        :null}
                        <tr className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5"}>
                            <th colSpan={5}></th>

                            <th className="align-middle" colSpan={3}>
                                ABONADO                                        
                            </th>
                            <td colSpan={4} className="text-success text-right align-middle fs-3">
                                {moneda(e.monto_abonado)}
                            </td>
                        </tr>
                        <tr className={(selectFactViewDetalles==e.id?"bg-success-superlight":null)+" border-bottom-5"}>
                            <th colSpan={5}></th>

                            <th className="align-middle" colSpan={3}>
                                BALANCE                                        
                            </th>
                            <td colSpan={4} className={((e.balance)<0? "text-danger": "text-success")+(" fs-2 text-right align-middle bg-warning-light")}>
                                {e.condicion!="pagadas" && e.condicion!="abonos"?<button className="btn btn-outline-success m-2" onClick={()=>abonarFact(e.id_proveedor,e.id)}>
                                    <i className=" fa fa-credit-card"></i>
                                    PAGAR 
                                </button>:null}
                                {moneda(e.balance)}
                            </td>
                        </tr>
                    </> 
                :null}
                {e.facturas ?
                    e.facturas.length?
                        e.facturas.map(fact=>
                            <tr key={fact.id} className="border-top">
                                <td colSpan={4}></td>
                                <td className="text-right align-middle text-muted fst-italic" colSpan={2}>
                                    {fact.created_at}
                                </td>
                                <td className=" align-middle text-muted fst-italic text-right" colSpan={2}>
                                    FACTURA ASOCIADA <i className="fa fa-check text-sinapsis"></i>
                                </td>
                                <td className=" align-middle" colSpan={2}>
                                    <span className="btn-sinapsis btn pointer w-100">
                                        FACT {fact.numfact}
                                    </span> 
                                </td>
                                <td className="text-right align-middle" colSpan={2}>
                                    <span className="text-sinapsis">{moneda(fact.pivot.monto)}</span> / <span className="text-success">{moneda(fact.monto)}</span>
                                </td>
                            </tr>
                        )
                    :null
                :null}
                
            </>
        :null}
    </>
}