import { useState } from "react";
import  SearchBarFacturas  from "./searchBarFacturas";

export default function ComprasDistribuirFacts({
    setqcampoBusquedacuentasPorPagarDetalles,
    qcampoBusquedacuentasPorPagarDetalles,
    setqinvertircuentasPorPagarDetalles,
    qinvertircuentasPorPagarDetalles,

    facturaSelectAddItems,
    setfacturaSelectAddItems,
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
    returnCondicion,
    colorSucursal,
    moneda,
    subviewDistribuir,
    setsubviewDistribuir,
    listdistribucionselect,
    setlistdistribucionselect,
    distribucionSelectSucursal,
    setdistribucionSelectSucursal,
    addlistdistribucionselect,
    dellistdistribucionselect,
    sendlistdistribucionselect,
    changeInputDistribuirpedido,
    number,
    autorepartircantidades,
    numcuentasPorPagarDetalles,
    setnumcuentasPorPagarDetalles,
    selectFactToDistribuirFun,
}){
    


    let facturaSelectAddItemsSelect = {}
    if (facturaSelectAddItems) {
        if (selectCuentaPorPagarId.detalles) {
            
            let match = selectCuentaPorPagarId.detalles.filter(e=>e.id==facturaSelectAddItems) 
            if (match.length) {
                facturaSelectAddItemsSelect = match[0]
            }
        }
    }

    const getSucursalById = id => {
        let fil = sucursales.filter(e=>e.id==id)
        if (fil.length) {
            return fil[0].codigo
        }
        return null
    }

    return (
    <>
        {subviewDistribuir==="selectfacttodistribuir"?
            <div className="container">
                
                <SearchBarFacturas

                    setqcampoBusquedacuentasPorPagarDetalles={setqcampoBusquedacuentasPorPagarDetalles}
                    qcampoBusquedacuentasPorPagarDetalles={qcampoBusquedacuentasPorPagarDetalles}
                    setqinvertircuentasPorPagarDetalles={setqinvertircuentasPorPagarDetalles}
                    qinvertircuentasPorPagarDetalles={qinvertircuentasPorPagarDetalles}

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
                    isonlyestatus={1}

                />

                <table className="table table-borderless table-striped mb-500">
                        <thead className="">
                            <tr className="align-middle">
                                <th colSpan={3}>

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
                            </tr>
                            <tr>
                            
                                <th>ID</th>
                                <th  className="pointer p-3">
                                    <span onClick={()=>{if(qCampocuentasPorPagarDetalles=="created_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("created_at")}}>CREADA</span>

                                </th> 
                                <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_proveedor"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_proveedor")}} className="pointer p-3">
                                    PROVEEDOR
                                </th>  
                                <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="numfact"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("numfact")}} className="pointer p-3">
                                    NÚMERO DE FACTURA
                                </th>  
                                <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_sucursal"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_sucursal")}} className="pointer p-3 text-center">
                                    ORIGEN
                                </th>  
                                <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer  p-3">
                                    MONTO
                                </th>
                                <th>
                                    ITEMS
                                </th>
                                    
                            </tr>
                        </thead> 
                            
                    {
                        selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                        ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                            <tbody key={i}>
                                {e.aprobado?
                                <tr className={e.aprobado?"bg-success-superlight":"bg-sinapsis-superlight"}>
                                    <>
                                        <td>{e.id}</td>
                                        <td className="">
                                            <small className="text-muted">{e.created_at}</small>
                                        </td> 
                                        <td className="">
                                            <span className="fw-bold fs-4">{e.proveedor?e.proveedor.descripcion:null}</span>
                                        </td>  
                                        <td className="">
                                            
                                            <span onClick={()=>{if (e.aprobado) { selectFactToDistribuirFun(e.id, e.id_sucursal) }}} className={(returnCondicion(e.condicion))+(" w-100 btn fs-2 pointer fw-bolder text-light ")}> 
                                                {e.numfact}
                                            </span>
                                        </td>  
                                        <td className=" ">
                                            <button className={"btn w-100 fw-bolder fs-3"} style={{backgroundColor:colorSucursal(e.sucursal? e.sucursal.codigo:"")}}>
                                                {e.sucursal? e.sucursal.codigo:""}
                                            </button>
                                        </td>
                                        <td className=" ">
                                            <span className={(e.monto<0? "text-danger": "text-success")+(" fs-3 fw-bold ")}>{moneda(e.monto)}</span>
                                        </td>
                                        <td>
                                            <span className="fs-3">{e.items?e.items.length:null}</span>
                                        </td>
                                    </>    
                                </tr>:null}
                            </tbody>
                        )
                        : null : null
                    } 
                    
                </table>
            </div>  
        :null}

        {subviewDistribuir==="distribuir"?
            <div className="container-fluid">
                {facturaSelectAddItemsSelect?<div className="row mb-4">
                    <div className="col">
                        <div className="p-1">
                            <i className="fa fa-arrow-left fa-2x text-danger" onClick={()=>setsubviewDistribuir("selectfacttodistribuir")}></i>
                        </div>
                        <table className="table table-borderless table-striped m-0 table-distribucion">
                            <thead>
                                <tr>
                                    <th colSpan={2}>
                                        <div className="input-group">
                                            <select disabled={true} className="form-control form-control-lg" value={distribucionSelectSucursal} onChange={e=>setdistribucionSelectSucursal(e.target.value)}>
                                                <option value="">-SUCURSAL-</option>
                                                {sucursales.map(e=>
                                                    <option key={e.id} value={e.id}>{e.codigo}</option>
                                                )}
                                            </select>
                                            {/* <button className="btn btn-success" onClick={()=>addlistdistribucionselect()}><i className="fa fa-plus"></i></button> */}
                                        </div>
                                    </th>
                                    <th colSpan={5}></th>
                                </tr>
                                <tr>
                                    <th className="bg-ct">
                                        CT
                                        {/* <button className="btn btn-sinapsis pull-right" onClick={()=>autorepartircantidades("general",null)}>AUTO REPARTIR</button> */}
                                    </th>
                                    <th>BARRAS</th>
                                    <th>DESCRIPCION</th>
                                    <th>BASE F</th>
                                    <th className="bg-base">BASE</th>
                                    <th className="bg-venta">VENTA</th>
                                    <th className="text-right">SUBTOTAL BASE F</th>

                                </tr>
                            </thead>
                            {facturaSelectAddItemsSelect.items?
                                facturaSelectAddItemsSelect.items.map(item=>
                                    <tbody key={item.id}>
                                            <tr>
                                                {item.producto?
                                                    <>
                                                        <td></td>
                                                        <td>{item.producto.codigo_barras}</td>
                                                        <td>{item.producto.descripcion}</td>
                                                        <td onClick={()=>modItemFact(item.id, "basef")} >{moneda(item.basef)}</td>
                                                        <td onClick={()=>modItemFact(item.id, "base")} className="bg-base">{moneda(item.base)}</td>
                                                        <td onClick={()=>modItemFact(item.id, "venta")} className="bg-venta">{moneda(item.venta)}</td>
                                                        <td className="text-right">{moneda(item.basef*item.cantidad)}</td>
                                                    </>
                                                :null}
                                            </tr>
                                            <tr>
                                                <td onClick={()=>modItemFact(item.id, "cantidad")} className="bg-ct fs-2 text-center align-middle">{item.cantidad}</td>
                                                <td colSpan={(6)}>
                                                    <table className="">
                                                        <tbody>
                                                            <tr>
                                                                {listdistribucionselect.filter(e=>e.id_item==item.id).map(sucursalid=>
                                                                    <th>
                                                                        <button onClick={()=>dellistdistribucionselect(sucursalid.id_sucursal)} className={"btn w-100 fw-bolder"} style={{backgroundColor:colorSucursal(sucursalid.id_sucursal? getSucursalById(sucursalid.id_sucursal):"")}}>
                                                                            {getSucursalById(sucursalid.id_sucursal)}
                                                                        </button>
                                                                        
                                                                    </th>
                                                                )}
                                                            </tr>
                                                            <tr>
                                                                {listdistribucionselect.filter(e=>e.id_item==item.id).map(sucursalid=>
                                                                    <th> <input type="text" className="form-control fs-3 text-success" placeholder={"Ct. "+getSucursalById(sucursalid.id_sucursal)} value={sucursalid.cantidad} onChange={event=>changeInputDistribuirpedido(item.id,sucursalid.id_sucursal,number(event.target.value,7))} /> </th>
                                                                )}
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                    </tbody>
                                ):null
                            }
                            <tbody>
                                <tr>
                                    <td colSpan={6} className="p-0 text-right">
                                        {listdistribucionselect.length?
                                            <button className="btn btn-success" onClick={()=>sendlistdistribucionselect()}><i className="fa fa-save"></i></button>
                                        :null}
                                    </td>
                                    <td className="text-right p-3">
                                        <span className="fs-1 fw-bolder text-sinapsis mt-2">{moneda(facturaSelectAddItemsSelect.sumitems)}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div className="col-2">
                        <div className="h-100 d-flex justify-content-center align-items-end ">
                            <div className="text-center">
                                <div>
                                    <span className="text-muted fst-italic">{facturaSelectAddItemsSelect.created_at}</span>
                                </div>
                                <div>
                                    <span className="fs-3 fw-bolder">{facturaSelectAddItemsSelect.proveedor?facturaSelectAddItemsSelect.proveedor.descripcion:null}</span>
                                </div>
                                <div>
                                    <span className={(returnCondicion(facturaSelectAddItemsSelect.condicion))+(" btn fs-2 pointer fw-bolder text-light me-1 ")}> 
                                        {facturaSelectAddItemsSelect.numfact}
                                    </span>
                                    <button className={"btn fw-bolder fs-2"} style={{backgroundColor:colorSucursal(facturaSelectAddItemsSelect.sucursal? facturaSelectAddItemsSelect.sucursal.codigo:"")}}>
                                        {facturaSelectAddItemsSelect.sucursal?facturaSelectAddItemsSelect.sucursal.codigo:null}
                                    </button>
                                </div>
                                <div className="p-3">
                                    <span className="fs-1 fw-bolder text-danger mt-2">{moneda(facturaSelectAddItemsSelect.monto)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>:null}
            </div>
        :null}
    </>
    )
}