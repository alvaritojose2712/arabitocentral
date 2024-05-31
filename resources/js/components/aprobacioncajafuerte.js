import { useEffect } from "react";
export default function AprobacionCajaFuerte({
    moneda,
    getsucursalDetallesData,
    sucursalSelect,
    setsucursalSelect,
    setsucursalDetallesData,
    sucursalDetallesData,

    getSucursales,
    sucursales,
    qestatusaprobaciocaja,
    setqestatusaprobaciocaja,
    aprobarMovCajaFuerte,
    
}){
    useEffect(()=>{
        getSucursales()
    },[

    ])

    return (
        <div className="">
            <div className="input-group mb-2">
                <select className="form-control" onChange={e=>setsucursalSelect(e.target.value)} value={sucursalSelect===null?"":sucursalSelect}>
                    <option value="">-TODAS SUCURSALES-</option>    
                    {
                        sucursales.map(e=>
                            <option key={e.id} value={e.id}>{e.codigo}</option>    
                        )
                    }
                </select>
                <div className="input-group-prepend">
                    <button className={("btn btn-"+(qestatusaprobaciocaja==0?"sinapsis":""))} onClick={e=>setqestatusaprobaciocaja(0)}><i className="fa fa-clock-o"></i></button>
                    <button className={("btn btn-"+(qestatusaprobaciocaja==1?"success":""))} onClick={e=>setqestatusaprobaciocaja(1)}><i className="fa fa-check"></i></button>
                </div>
            </div>
            { 
            sucursalDetallesData.aprobacionfuertedata?sucursalDetallesData.aprobacionfuertedata.length
            ? sucursalDetallesData.aprobacionfuertedata.map( (e,i) =>
                <div 
                key={e.id}
                className={(!e.estatus?"bg-sinapsis-light":"bg-light")+" text-secondary card mb-3 pointer shadow"}>
                    <div className="card-header flex-row justify-content-between">
                        <div className="d-flex justify-content-between">
                            <div className="w-50">
                                SOLICITA {e.sucursal.codigo}
                                <small className="fst-italic"><h5>{e.tipo==1?"CAJA FUERTE":"CAJA CHICA"}</h5></small><br/>
                                <small className="fst-italic">{e.cat?e.cat.nombre:null}</small><br/>

                                
                            </div>
                            <div className="w-50 text-right">
                                {
                                    e.montodolar!=0?
                                        <>
                                            <span className="h6 text-muted font-italic">BALANCE $ <b>{moneda(e.balancedolar)}</b></span>
                                            <br/>
                                            <br/>
                                            <span className={"h3 text-"+(e.montodolar<0?"danger":"success")}>$ <b>{moneda(e.montodolar)}</b></span>
                                        </>
                                    :null
                                }

                                {
                                    e.montobs!=0?
                                        <>
                                            <span className="h6 text-muted font-italic">BALANCE Bs. <b>{moneda(e.balancebs)}</b></span>
                                            <br/>
                                            <br/>
                                            <span className={"h3 text-"+(e.montobs<0?"danger":"success")}>Bs. <b>{moneda(e.montobs)}</b></span>
                                        </>
                                    :null
                                }

                                {
                                    e.montoeuro!=0?
                                        <>
                                            <span className="h6 text-muted font-italic">BALANCE EURO <b>{moneda(e.balanceeuro)}</b></span>
                                            <br/>
                                            <br/>
                                            <span className={"h3 text-"+(e.montoeuro<0?"danger":"success")}>EURO <b>{moneda(e.montoeuro)}</b></span>
                                        </>
                                    :null
                                }

                                {
                                    e.montopeso!=0?
                                        <>
                                            <span className="h6 text-muted font-italic">BALANCE COP <b>{moneda(e.balancepeso)}</b></span>
                                            <br/>
                                            <br/>
                                            <span className={"h3 text-"+(e.montopeso<0?"danger":"success")}>COP <b>{moneda(e.montopeso)}</b></span>
                                        </>
                                    :null
                                }

                                
                            </div>
                        </div>
                    </div>
                    <div className="text-center">
                        <span className="card-title ">
                            <b>{e.concepto}</b>
                        </span>
                    </div> 
                    {e.trabajador?
                        <table className="table">
                            <thead>
                                <tr>
                                    <th>CARGO</th>
                                    <td>{e.trabajador.cargo.cargosdescripcion}</td>
                                </tr>
                                <tr>
                                    <th>MÁXIMO A COBRAR ESTE MES</th>
                                    <td className="text-success">{e.trabajador.maxpagopersona}</td>
                                </tr>
                                <tr>
                                    <th>PRESTAMOS TOTALES</th>
                                    <td className="text-danger fs-5">{moneda(e.trabajador.sumprestamos)}</td>
                                </tr>
                                <tr>
                                    <th>CRÉDITOS TOTALES</th>
                                    <td className="text-sinapsis fs-5">{moneda(e.trabajador.sumCreditos)}</td>
                                </tr>
                                <tr>
                                    <th>PAGOS QUINCENA (MES ACTUAL)</th>
                                    <td>{moneda(e.trabajador.mes)}</td>
                                </tr>
                                <tr>
                                    <th>PAGOS QUINCENA (MES PASADO)</th>
                                    <td>{moneda(e.trabajador.mespasado)}</td>
                                </tr>
                            </thead>
                        </table>
                    :null}
                    <div className="card-body d-flex justify-content-between">
                        <button className="btn btn-sm btn-danger" onClick={()=>aprobarMovCajaFuerte(e.id,"delete")}><i className="fa fa-times"></i></button>
                        {e.id_sucursal_destino?
                            (e.sucursal_destino_aprobacion==1?

                                <>
                                    <div className="btn-group">
                                        <button className="btn btn-danger">ENVIA {e.sucursal.codigo}</button>
                                        <button className="btn btn-success">RECIBE {e.destino?e.destino.codigo:null}</button>
                                    </div>
                                    <button className="btn btn-sm btn-success" onClick={()=>aprobarMovCajaFuerte(e.id,"aprobar")}>{e.estatus==0?"APROBAR":"REVERSAR"} <i className="fa fa-check"></i></button>
                                </>
                                :
                                <button className="btn btn-sm btn-warning">ESPERANDO APROBACIÓN DEL RECEPTOR <i className="fa fa-clock-o"></i></button>
                                
                            )
                        :
                            
                            <button className="btn btn-sm btn-success" onClick={()=>aprobarMovCajaFuerte(e.id,"aprobar")}>{e.estatus==0?"APROBAR":"REVERSAR"} <i className="fa fa-check"></i></button>
                        }
                           
                    </div>
                        
                </div>
            )
            : null : null
            }
        </div>
    )
}