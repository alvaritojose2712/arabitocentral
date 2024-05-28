import { useEffect, useState } from "react";
import  SearchBarFacturas  from "./searchBarFacturas";
import Modalselectfile from "./modalselectfile";

export default function Comprascargarfactsdigitales({
    selectCuentaPorPagarProveedorDetallesFun,
    cuentaporpagarAprobado,
    setcuentaporpagarAprobado,
    setqcuentasPorPagarDetalles,
    qcuentasPorPagarDetalles,
    setselectProveedorCxp,
    selectProveedorCxp,
    proveedoresList,
    sucursalcuentasPorPagarDetalles,
    setsucursalcuentasPorPagarDetalles,
    sucursales,
    categoriacuentasPorPagarDetalles,
    setcategoriacuentasPorPagarDetalles,
    qCampocuentasPorPagarDetalles,
    setOrdercuentasPorPagarDetalles,
    setqCampocuentasPorPagarDetalles,
    selectCuentaPorPagarId,
    qcuentasPorPagarTipoFact,
    dateFormat,
    returnCondicion,
    colorSucursal,
    moneda,
    handleFacturaxLotes,
    saveFacturaLote,
    number,
    setmodalfilesexplorercxp,
    modalfilesexplorercxp,
    setviewmainPanel,

    selectFilecxp,
    setselectFilecxp,
    dataFilescxp,
    showFilescxp,
    numfact_select_imagen,
    setfactInpImagen,
    numcuentasPorPagarDetalles,
    setnumcuentasPorPagarDetalles,
    
}){
    /* useEffect(()=>{
        setcuentaporpagarAprobado(0)
        setqCampocuentasPorPagarDetalles("id")
        setOrdercuentasPorPagarDetalles("desc")
    },[]) */
    const type = type => {
        return !type || type === "delete" ? true : false
    }
    

    return <div className="container-fluid">
        {/* <Modalselectfile
            numfact_select_imagen={numfact_select_imagen}
            setselectFilecxp={setselectFilecxp}
            colorSucursal={colorSucursal}
            showFilescxp={showFilescxp}
        /> */}


        <SearchBarFacturas
            selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
            cuentaporpagarAprobado={cuentaporpagarAprobado}
            setcuentaporpagarAprobado={setcuentaporpagarAprobado}
            setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
            qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
            setselectProveedorCxp={setselectProveedorCxp}
            selectProveedorCxp={selectProveedorCxp}
            proveedoresList={proveedoresList}
            sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
            setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}
            sucursales={sucursales}
            categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
            setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
            numcuentasPorPagarDetalles={numcuentasPorPagarDetalles}
            setnumcuentasPorPagarDetalles={setnumcuentasPorPagarDetalles}
            
        />

        <table className="table table-borderless table-striped mb-500">
                <thead className="">
                    <tr className="align-middle">
                        <th colSpan={6} className="p-0">
                            {/* <div className="btn-group">
                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="abonos"?"btn-success":"btn-outline-success")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="abonos"?"":"abonos")}>PAGOS</span>

                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="pagadas"?"btn-medsuccess":"btn-outline-medsuccess")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="pagadas"?"":"pagadas")}>PAGADAS</span>

                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="semipagadas"?"btn-primary":"btn-outline-primary")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="semipagadas"?"":"semipagadas")}>ABONADAS</span>
                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="porvencer"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="porvencer"?"":"porvencer")}>POR VENCER</span>
                                <span className={("btn btn-lg ")+(qcuentasPorPagarTipoFact=="vencidas"?"btn-danger":"btn-outline-danger")} onClick={()=>setqcuentasPorPagarTipoFact(qcuentasPorPagarTipoFact=="vencidas"?"":"vencidas")}>VENCIDAS</span>
                            </div> */}
                            <button className="btn btn-warning fs-2" onClick={()=>setviewmainPanel("comprasmodalselectfactsfisicas")}>ANCLAR <i className="fa fa-link"></i></button>


                        </th>
                        <th colSpan={4} className="text-right">
                            { 
                                selectCuentaPorPagarId?
                                    selectCuentaPorPagarId.sum!=""? 
                                        <>
                                            Resultados
                                            <span className="text-muted fs-2 ms-2">
                                                <b>({selectCuentaPorPagarId.sum})</b>
                                            </span>
                                        </>
                                    :null
                                :null
                            }
                        </th>
                        <th className="text-right">
                            <button className="btn btn-warning" onClick={()=>handleFacturaxLotes(null,null,"add")}><i className="fa fa-plus"></i></button>
                            <button className="btn btn-success" onClick={()=>saveFacturaLote()}>GUARDAR <i className="fa fa-send"></i></button>

                        </th>
                    </tr>
                    <tr>
                       
                        <th>ID</th>
                        <th  className="pointer p-3">
                            <span onClick={()=>{if(qCampocuentasPorPagarDetalles=="created_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("created_at")}}>CREADA</span>
                            <br />
                            <span onClick={()=>{if(qCampocuentasPorPagarDetalles=="updated_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("updated_at")}}>ACTUALIZADA</span>
                        </th> 
                        <th className="p-3">
                            <span className="pointer" onClick={()=>{if(qCampocuentasPorPagarDetalles=="fecharecepcion"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("fecharecepcion")}}>
                                RECEPCIÓN
                            </span>
                        </th>  
                        <th className="p-3">
                            <span className="pointer" onClick={()=>{if(qCampocuentasPorPagarDetalles=="fechaemision"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("fechaemision")}}>
                                EMISIÓN
                            </span>
                        </th>       
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="fechavencimiento"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("fechavencimiento")}} className="pointer  p-3">
                            VENCE
                        </th>  

                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_proveedor"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_proveedor")}} className="pointer p-3">
                            PROVEEDOR
                        </th>  
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="numfact"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("numfact")}} className="pointer  p-3 text-center">
                            NúMERO DE FACTURA
                        </th>  
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_sucursal"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_sucursal")}} className="pointer p-3 text-center">
                            ORIGEN
                        </th>  
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer  p-3">
                            DESCUENTO
                        </th>
                        <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer  p-3">
                            MONTO
                        </th>
                        <th></th>
                            
                    </tr>
                </thead> 
                    
            {
                selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                    <tbody key={i}>
                        <tr className={e.aprobado?"bg-success-superlight":"bg-sinapsis-superlight"}>
                            {e.type=="update" || e.type=="new" || e.type=="delete"?
                                <>
                                    <td colSpan={2}>
                                        {numfact_select_imagen?
                                            <div className="text-center">
                                                <div>
                                                    <img src={numfact_select_imagen.ruta} width={150} onClick={()=>showFilescxp(numfact_select_imagen.ruta)} className="pointer"/>
                                                </div>
                                                <span>{numfact_select_imagen.ruta}</span>
                                            </div>
                                        :null}
                                        <button className="btn btn-warning w-100" onClick={()=>setviewmainPanel("comprasmodalselectfactsfisicas")}><i className="fa fa-link"></i></button>

                                    </td>
                                    <td>
                                        <input disabled={type(e.type)} type="date" className="form-control"
                                        placeholder="fecharecepcion" 
                                        value={!e.fecharecepcion?"":e.fecharecepcion}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "fecharecepcion")} 
                                        />
                                    </td>
                                    <td>
                                        <input disabled={type(e.type)} type="date" className="form-control"
                                        placeholder="fechaemision" 
                                        value={!e.fechaemision?"":e.fechaemision}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "fechaemision")} 
                                        />
                                    </td>
                                    <td>
                                        <input disabled={type(e.type)} type="date" className="form-control"
                                        placeholder="fechavencimiento" 
                                        value={!e.fechavencimiento?"":e.fechavencimiento}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "fechavencimiento")} 
                                        />
                                    </td>
                                    <td>
                                        <select disabled={type(e.type)} className="form-control" 
                                        value={!e.id_proveedor?"":e.id_proveedor}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "id_proveedor")} 
                                        >
                                            <option value="">-</option>
                                            {proveedoresList.map(e=>
                                                <option key={e.id} value={e.id}>{e.descripcion}-{e.rif}</option>
                                            )}
                                        </select>
                                    </td>
                                    <td>
                                        <input disabled={type(e.type)} type="text" className="form-control fs-3"
                                        placeholder="numfact" 
                                        value={!e.numfact?"":e.numfact}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "numfact")} 
                                        />

                                        <textarea disabled={type(e.type)} placeholder="Observaciones..." className="form-control fs-3"
                                        value={!e.nota?"":e.nota}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "nota")} 
                                        ></textarea>

                                        <div className="form-group mb-2">
                                            <label htmlFor="formFile" className="form-label">Adjunte FOTO NITIDA, COMPLETA Y CENTRADA DE LA FACTURA</label>
                                            <input type="file" className="form-control" id="formFile" onChange={event=>setfactInpImagen(event.target.files[0])}/>
                                        </div>
                                    </td>
                                    <td>
                                        <select disabled={type(e.type)} className="form-control" 
                                        value={!e.id_sucursal?"":e.id_sucursal}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "id_sucursal")} 
                                        >
                                            <option value="">-</option>
                                            {sucursales.map(e=>
                                                <option key={e.id} value={e.id}>{e.codigo}</option>
                                            )}
                                        </select>
                                    </td>
                                    <td>
                                        <input disabled={type(e.type)} type="text" className="form-control"
                                        placeholder="descuento" 
                                        value={!e.descuento?"":number(e.descuento)}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "descuento")} 
                                        />
                                    </td>
                                    <td>
                                        <input disabled={type(e.type)} type="text" className="form-control"
                                        placeholder="monto" 
                                        value={!e.monto?"":number(e.monto)}
                                        onChange={e => handleFacturaxLotes((e.target.value), i, "changeInput", "monto")} 
                                        />
                                    </td>
                                </>    
                            :null}
                            {!e.type?
                                <>
                                    <td>{e.id}</td>
                                    <td className="">
                                        <small className="text-muted">{e.created_at}</small>
                                        <br />
                                        <small className="text-muted">{e.updated_at}</small>
                                    </td> 
                                    <td className="">
                                        <span className="text-successfuerte">{dateFormat(e.fecharecepcion,"dd-MM-yyyy")}</span>
                                    </td>
                                    <td className=" fs-4">
                                        <span className="text-successfuerte">{dateFormat(e.fechaemision,"dd-MM-yyyy")}</span>
                                    </td>       
                                    <td className=" fs-4">
                                        <span className="text-danger ms-1">{dateFormat(e.fechavencimiento,"dd-MM-yyyy")}</span>
                                    </td>  
                                    <td className="">
                                        <span className="fw-bold fs-4">{e.proveedor?e.proveedor.descripcion:null}</span>
                                    </td>  
                                    <td className="">
                                        <span className={(returnCondicion(e.condicion))+(" w-100 btn fs-2 pointer fw-bolder text-light ")} onClick={()=>showFilescxp(e.descripcion)}> 
                                            {e.numfact}
                                        </span>
                                        {e.nota?
                                            <>
                                                <hr />
                                                <p>
                                                    {e.nota}
                                                </p>
                                            </>
                                        :null}
                                    </td>  
                                    <td className=" ">
                                        <button className={"btn w-100 fw-bolder fs-3"} style={{backgroundColor:colorSucursal(e.sucursal? e.sucursal.codigo:"")}}>
                                            {e.sucursal? e.sucursal.codigo:""}
                                        </button>
                                    </td>
                                    <td className=" ">
                                        {
                                            e.monto_descuento!="" && e.monto_descuento!="0"?
                                                <span className="text-muted fst-italic fs-6">{moneda(e.monto_descuento)} <br /> ({e.descuento}%)</span>
                                            :null
                                        }
                                    </td>
                                    <td className=" ">
                                        <span className={(e.monto<0? "text-danger": "text-success")+(" fs-3 fw-bold ")}>{moneda(e.monto)}</span>
                                    </td>
                                </>    
                            :null}
                            <td className="cell1">
                                {e.aprobado==0?
                                <div className='d-flex justify-content-between'>

                                    {!e.type ?
                                        <>
                                            <span className="btn-sm btn btn-danger" onClick={() => handleFacturaxLotes(null, i, "delMode")}><i className="fa fa-trash"></i></span>
                                            <span className="btn-sm btn btn-warning" onClick={() => {setfactInpImagen("");handleFacturaxLotes(null, i, "update")}}><i className="fa fa-pencil"></i></span>
                                        </>
                                        : null}
                                    {e.type === "new" ?
                                        <span className="btn-sm btn btn-danger" onClick={() => handleFacturaxLotes(null, i, "delNew")}><i className="fa fa-times"></i></span>
                                        : null}
                                    {e.type === "update" ?
                                        <span className="btn-sm btn btn-warning" onClick={() => handleFacturaxLotes(null, i, "delModeUpdateDelete")}><i className="fa fa-times"></i></span>
                                        : null}
                                    {e.type === "delete" ?
                                        <span className="btn-sm btn btn-danger" onClick={() => handleFacturaxLotes(null, i, "delModeUpdateDelete")}><i className="fa fa-arrow-left"></i></span>
                                        : null}

                                    <span className="btn-sm btn btn-warning"><i className="fa fa-print"></i></span>

                                </div>:null}
                            </td>
                        </tr>
                    </tbody>
                )
                : null : null
            } 
            
        </table>
    </div>

}