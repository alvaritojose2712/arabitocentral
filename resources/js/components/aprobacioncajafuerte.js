import { useEffect } from "react";
import FechasMain from './panel/fechasmain';

export default function AprobacionCajaFuerte({
    moneda,
    aprobarMovCajaFuerte,
    sucursales,
    qestatusaprobaciocaja,
    setqestatusaprobaciocaja,

    getAprobacionFlujoCaja,
    dataAprobacionFlujoCaja,
    qfechadesdeAprobaFlujCaja,
    setqfechadesdeAprobaFlujCaja,
    qfechahastaAprobaFlujCaja,
    setqfechahastaAprobaFlujCaja,
    qAprobaFlujCaja,
    setqAprobaFlujCaja,
    qCategoriaAprobaFlujCaja,
    setqCategoriaAprobaFlujCaja,

    setqSucursalAprobaFlujCaja,
    qSucursalAprobaFlujCaja,
}){
    

    return (
        <div className="">
            
            <form className="input-group mb-2" onSubmit={event=>{event.preventDefault();getAprobacionFlujoCaja()}}>
                <input type="date" className="form-control" value={qfechadesdeAprobaFlujCaja} onChange={e=>setqfechadesdeAprobaFlujCaja(e.target.value)}/>
                <input type="date" className="form-control" value={qfechahastaAprobaFlujCaja} onChange={e=>setqfechahastaAprobaFlujCaja(e.target.value)}/>
                <select className="form-control" onChange={e=>setqSucursalAprobaFlujCaja(e.target.value)} value={qSucursalAprobaFlujCaja===null?"":qSucursalAprobaFlujCaja}>
                    <option value="">-TODAS SUCURSALES-</option>    
                    {
                        sucursales.map(e=>
                            <option key={e.id} value={e.id}>{e.codigo}</option>    
                        )
                    }
                </select>
                <div className="input-group-prepend">
                    <button className={("btn btn-"+(qestatusaprobaciocaja==0?"sinapsis":""))} onClick={e=>{
                        if (qestatusaprobaciocaja==0) {
                            getAprobacionFlujoCaja()
                        }
                        setqestatusaprobaciocaja(0)
                    }}><i className="fa fa-clock-o"></i></button>
                    <button className={("btn btn-"+(qestatusaprobaciocaja==1?"success":""))} onClick={e=>{
                        if (qestatusaprobaciocaja==1) {
                            getAprobacionFlujoCaja()
                        }
                        setqestatusaprobaciocaja(1)
                    }}><i className="fa fa-check"></i></button>
                </div>
            </form>

            <table className="table">
                <tbody>
                    { 
                        dataAprobacionFlujoCaja?dataAprobacionFlujoCaja.data
                        ? dataAprobacionFlujoCaja.data.map( (e,i) =>
                            <>
                            
                                <tr key={e.id} className={(!e.estatus?"bg-sinapsis-superlight":"bg-light")+" text-secondary  mb-3 pointer"}>
                                    <td className="text-danger text-center align-middle">
                                        <i onClick={()=>aprobarMovCajaFuerte(e.id,"delete")} className="fa fa-times fa-2x"></i>
                                    </td>
                                    <th className="align-middle">
                                        <button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:e.sucursal.background,color:e.sucursal.color}}>
                                            {e.sucursal.codigo}
                                        </button>
                                    </th>
                                    <td className="align-middle">
                                        {e.tipo==0?
                                            <button className="btn btn-sinapsis">CAJA CHICA</button>
                                        :
                                            <button className="btn btn-success">CAJA FUERTE</button>
                                        }
                                    </td>
                                    <td className="align-middle">
                                        <button className={"btn w-100 fw-bolder fs-6 fst-italic shadow"} style={{backgroundColor:e.cat.background,color:e.cat.color}}>
                                            {e.cat.nombre}
                                        </button>
                                    </td>
                                    <th className="text-center align-middle">
                                        <span className="fw-bold fs-3">
                                            {e.concepto}
                                        </span>
                                        <br />

                                        {e.id_sucursal_destino?
                                            (e.sucursal_destino_aprobacion==1?
                                                    <div className="btn-group">
                                                        <button className="btn btn-danger">ENVIA {e.sucursal.codigo}</button>
                                                        <button className="btn btn-success">RECIBE {e.destino?e.destino.codigo:null}</button>
                                                    </div>
                                                :
                                                    <button className="btn btn-sm btn-warning">ESPERANDO APROBACIÓN DEL RECEPTOR <i className="fa fa-clock-o"></i></button>
                                            )
                                        :
                                            
                                            null
                                        }
                                    </th> 
                                    <td className="text-right align-middle">
                                        {
                                            e.montodolar!=0?
                                                <>
                                                    {/* <span className="h6 text-muted font-italic">BALANCE $ <b>{moneda(e.balancedolar)}</b></span>
                                                    <br/>
                                                    <br/> */}
                                                    <span className={"h3 text-"+(e.montodolar<0?"danger":"success")}>$ <b>{moneda(e.montodolar)}</b></span>
                                                </>
                                            :null
                                        }

                                        {
                                            e.montobs!=0?
                                                <>
                                                  {/*   <span className="h6 text-muted font-italic">BALANCE Bs. <b>{moneda(e.balancebs)}</b></span>
                                                    <br/>
                                                    <br/> */}
                                                    <span className={"h3 text-"+(e.montobs<0?"danger":"success")}>Bs. <b>{moneda(e.montobs)}</b></span>
                                                </>
                                            :null
                                        }

                                        {
                                            e.montoeuro!=0?
                                                <>
                                                    {/* <span className="h6 text-muted font-italic">BALANCE EURO <b>{moneda(e.balanceeuro)}</b></span>
                                                    <br/>
                                                    <br/> */}
                                                    <span className={"h3 text-"+(e.montoeuro<0?"danger":"success")}>EURO <b>{moneda(e.montoeuro)}</b></span>
                                                </>
                                            :null
                                        }

                                        {
                                            e.montopeso!=0?
                                                <>
                                                    {/* <span className="h6 text-muted font-italic">BALANCE COP <b>{moneda(e.balancepeso)}</b></span>
                                                    <br/>
                                                    <br/> */}
                                                    <span className={"h3 text-"+(e.montopeso<0?"danger":"success")}>COP <b>{moneda(e.montopeso)}</b></span>
                                                </>
                                            :null
                                        }

                                        
                                    </td>
                                    <td className="text-center align-middle">
                                        {e.id_sucursal_destino?
                                            (e.sucursal_destino_aprobacion==1?
                                                <button className={("btn btn-sm btn-")+(e.estatus==0?"sinapsis":"success")} onClick={()=>aprobarMovCajaFuerte(e.id,"aprobar")}>{e.estatus==0?"APROBAR":"REVERSAR"} <i className="fa fa-check"></i></button>
                                                :
                                                null
                                            )
                                        :
                                            <button className={("btn btn-sm btn-")+(e.estatus==0?"sinapsis":"success")} onClick={()=>aprobarMovCajaFuerte(e.id,"aprobar")}>{e.estatus==0?"APROBAR":"REVERSAR"} <i className="fa fa-check"></i></button>
                                        }
                                    </td>
                                </tr>
                                
                                {e.trabajador?
                                    <tr>
                                        <td colSpan={6}>

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
                                        </td>
                                    </tr>

                                :null}
                            </>
                        )
                        : null : null
                    }
                </tbody>
            </table>
        </div>
    )
}