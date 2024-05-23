
import { useState, useEffect } from "react";
import Inventariogeneral from "./inventariogeneral";
export default function Inventario({
    qInventarioNovedades,
    setqInventarioNovedades,
    qFechaInventarioNovedades,
    setqFechaInventarioNovedades,
    qFechaHastaInventarioNovedades,
    setqFechaHastaInventarioNovedades,
    qSucursalInventarioNovedades,
    setqSucursalInventarioNovedades,

    resolveInventarioNovedades,
    inventarioNovedadesData,
    getInventarioNovedades,
    delInventarioNovedades,

    sucursales,

    setinvsuc_q,
    invsuc_q,
    invsuc_num,
    setinvsuc_num,
    invsuc_orderBy,
    setinvsuc_orderBy,
    setinvsuc_orderColumn,
    inventariogeneralData,
    getInventarioGeneral,
    colorSucursal,
}){

    const [subviewdici, setsubviewdici] = useState("novedades")

    return(
    <div className="container-fluid">
        <div className="text-center">
            <div className="btn-group mb-2">
                <button className={("fs-4 btn btn")+(subviewdici=="novedades"?"":"-outline")+("-sinapsis")} onClick={()=>setsubviewdici("novedades")}> NOVEDADES</button>
                <button className={("fs-2 btn btn")+(subviewdici=="inventariogeneral"?"":"-outline")+("-sinapsis")} onClick={()=>setsubviewdici("inventariogeneral")}> INVENTARIO</button>
            </div>
        </div>

        {subviewdici=="novedades"?
            <>
                <form onSubmit={event=>{getInventarioNovedades();event.preventDefault()}}>
                    <div className="input-group">
                        <input type="text" className="form-control" placeholder="Buscar responsable..." value={qInventarioNovedades} onChange={event=>setqInventarioNovedades(event.target.value)} />
                        <select className="form-control form-control-lg" value={qSucursalInventarioNovedades} onChange={e=>setqSucursalInventarioNovedades(e.target.value)}>
                            <option value="">-SUCURSAL-</option>
                            {sucursales.map(e=>
                                <option key={e.id} value={e.id}>{e.codigo}</option>
                            )}
                        </select>
                        <input type="date" className="form-control" value={qFechaInventarioNovedades} onChange={event=>setqFechaInventarioNovedades(event.target.value)} />
                        <input type="date" className="form-control" value={qFechaHastaInventarioNovedades} onChange={event=>setqFechaHastaInventarioNovedades(event.target.value)} />
                        <button className="btn btn-success"><i className="fa fa-search"></i></button>
                    </div>
                </form>
                <table className="table">
                    <thead>
                        <tr>
                            <th>SUCURSAL</th>
                            <th></th>
                            <th className="text-center">REF</th>
                            <th className="cell1 pointer"><span >C. Barras</span></th>
                            <th className="cell1 pointer"><span >C. Alterno</span></th>
                            <th className="cell2 pointer"><span >Descripción</span></th>
                            <th className="cell05 pointer"><span >Ct.</span></th>
                            <th className="cell1 pointer"><span >Base</span></th>
                            <th className="cell15 pointer">Venta</th>
                            <th className="text-center">RESPONSABLE</th>
                            <th className="text-center">MOTIVO</th>
                        </tr>
                    </thead>
                        {
                            inventarioNovedadesData?
                                inventarioNovedadesData.data?
                                    inventarioNovedadesData.data.map(e=>
                                        <tbody key={e.id}>
                                            <tr>
                                                <td rowSpan={2}>
                                                    <button className={"btn w-100 fw-bolder fs-3"} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>
                                                        {e.sucursal.codigo}
                                                    </button>    
                                                </td>
                                                <td rowSpan={2} className="align-middle">
                                                    <div className="btn-group">
                                                        {!e.estado?
                                                            <button className="btn btn-success" onClick={()=>resolveInventarioNovedades(e.id)}>RESOLVER</button>
                                                            :
                                                            <button className="btn btn-warning" onClick={()=>resolveInventarioNovedades(e.id)}>REVERSAR</button>
                                                        }
                                                    </div>
                                                </td>
                                                <td className="align-middle" rowSpan={2}> <button className="btn btn-success">{e.idinsucursal}</button> </td>
                                                <td className="bg-danger-light">{e.codigo_barras_old}</td>
                                                <td className="bg-danger-light">{e.codigo_proveedor_old}</td>
                                                <td className="bg-danger-light">{e.descripcion_old}</td>
                                                <td className="bg-danger-light">{e.cantidad_old}</td>
                                                <td className="bg-danger-light">{e.precio_base_old}</td>
                                                <td className="bg-danger-light">{e.precio_old}</td>
                                                <td className="bg-warning-light align-middle text-center" rowSpan={2}>{e.responsable}</td>
                                                <td className="bg-warning-light align-middle text-center" rowSpan={2}>{e.motivo}</td>
                                                <td className="bg-warning-light align-middle text-center" rowSpan={2}>{e.estado? <i className="fa fa-2x fa-check text-success"></i>: <i className="fa fa-2x fa-times text-danger"></i> }</td>
                                                <td rowSpan={2}><button className="btn btn-danger" onClick={()=>delInventarioNovedades(e.id)}><i className="fa fa-trash"></i></button></td>
                                                
                                            </tr>

                                            <tr className="bg-success-light trpaddingbottom">

                                                <td className="bg-success-light">{e.codigo_barras}</td>
                                                <td className="bg-success-light">{e.codigo_proveedor}</td>
                                                <td className="bg-success-light">{e.descripcion}</td>
                                                <td className="bg-success-light">{e.cantidad}</td>
                                                <td className="bg-success-light">{e.precio_base}</td>
                                                <td className="bg-success-light">{e.precio}</td>
                                                
                                            </tr>
                                        </tbody>
                                    )
                                :null
                            :null

                        }
                </table>
            </>
        :null}

        {subviewdici=="inventariogeneral"?
            <>
                <Inventariogeneral
                    colorSucursal={colorSucursal}
                    setinvsuc_q={setinvsuc_q}
                    invsuc_q={invsuc_q}
                    invsuc_num={invsuc_num}
                    setinvsuc_num={setinvsuc_num}
                    invsuc_orderBy={invsuc_orderBy}
                    setinvsuc_orderBy={setinvsuc_orderBy}
                    setinvsuc_orderColumn={setinvsuc_orderColumn}
                    inventariogeneralData={inventariogeneralData}
                    getInventarioGeneral={getInventarioGeneral}
                    sucursales={sucursales}
                />
            </>
        :null}
    </div>
    )
}