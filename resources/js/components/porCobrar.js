import { useEffect } from "react";
import FechasMain from './panel/fechasmain'

export default function PorCobrar({
    moneda,
    sucursalSelect,
    setsucursalSelect,
    sucursalDetallesData,
    getSucursales,
    sucursales,
    qestatusaprobaciocaja,
    setqestatusaprobaciocaja,
    aprobarCreditoFun,
    subviewpanelsucursales,
    setsubviewpanelsucursales,
    fechasMain1,
    fechasMain2,
    setfechasMain1,
    setfechasMain2,
    getsucursalDetallesData,
    
}){
    

    useEffect(()=>{
        getsucursalDetallesData()
    },[
        subviewpanelsucursales,
        fechasMain1,
        fechasMain2,
        sucursalSelect,
        qestatusaprobaciocaja,
    ])

    useEffect(()=>{
        setsubviewpanelsucursales("porcobrar")
        getSucursales()
    },[

    ])

    return (
        <>
            <FechasMain
                fechasMain1={fechasMain1}
                fechasMain2={fechasMain2}
                setfechasMain1={setfechasMain1}
                setfechasMain2={setfechasMain2}
            />
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
                sucursalDetallesData.aprobacioncreditosdata?sucursalDetallesData.aprobacioncreditosdata.length
                ? sucursalDetallesData.aprobacioncreditosdata.map( (e,i) =>
                    <div 
                    key={e.id}
                    className={(!e.estatus?"bg-sinapsis-light":"bg-light")+" text-secondary card mb-3 pointer shadow"}>
                        <div className="card-header flex-row justify-content-between">
                            <div className="d-flex justify-content-between">
                                <div className="w-50">
                                    SOLICITA {e.sucursal.codigo}
                                </div>
                                <div className="w-50 text-right">
                                    {
                                        e.saldo!=0?
                                            <>
                                                <span className="h6 text-muted font-italic fs-3">Monto $ <b>{moneda(e.saldo)}</b></span>
                                            </>
                                        :null
                                    }

                                </div>
                            </div>
                        </div>
                        <div className="text-center">
                            <span className="card-title ">
                                <small className="fst-italic fs-2">{e.cliente.nombre} ({e.cliente.identificacion})</small><br/>
                            </span>
                        </div> 
                        <div className="text-center">
                            DEUDA <span className="text-danger fs-2">{moneda(e.deuda)}</span>
                        </div>
                        <div className="text-center">
                            ÚLTIMO PAGO <span className="text-danger fs-4">{e.fecha_ultimopago}</span>
                        </div>
                        <div className="text-center">
                            <small className="text-muted">{e.created_at}</small>
                        </div>
                        <div className="card-body d-flex justify-content-between">
                            <button className="btn btn-sm btn-danger" onClick={()=>aprobarCreditoFun(e.id,"delete")}><i className="fa fa-times"></i></button>
                            <button className="btn btn-sm btn-success" onClick={()=>aprobarCreditoFun(e.id,"aprobar")}>{e.estatus==0?"APROBAR":"REVERSAR"} <i className="fa fa-check"></i></button>
                        </div>
                            
                    </div>
                )
                : null : null
                }
            </div>
        </>
    )
}