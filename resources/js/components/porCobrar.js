import { useEffect, useState } from "react";
import FechasMain from './panel/fechasmain'
import Creditos from './panel/creditos'

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
    const [subviewcxc, setsubviewcxc] = useState("aprobacion")
    
    useEffect(()=>{
        setsubviewpanelsucursales("porcobrar")
        getSucursales()
    },[])

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
        if (subviewcxc=="aprobacion") {
            setsubviewpanelsucursales("porcobrar")
        }
        
        if (subviewcxc=="creditos") {
            setsubviewpanelsucursales("creditos")
        }
    },[subviewcxc])



    return (
         
        <>
            <div className="text-center">
                <div className="btn-group mb-2">
                    <button className={("fs-4 btn btn")+(subviewcxc=="aprobacion"?"":"-outline")+("-sinapsis")} onClick={()=>setsubviewcxc("aprobacion")}> APROBACIÓN</button>
                    <button className={("fs-2 btn btn")+(subviewcxc=="creditos"?"":"-outline")+("-sinapsis")} onClick={()=>setsubviewcxc("creditos")}> CRÉDITOS</button>
                </div>
            </div>
            {
            
                subviewcxc=="aprobacion"?
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
                                    {e.trabajador?
                                    <div>
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
                                    </div>:null}
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
                :null
            }

            {
                subviewcxc=="creditos"?
                    <>
                        <div className="row">
                            <div className="col table-responsive mb-2 pb-2 pt-2 d-flex justify-content-center">
                                <div className="btn-group w-100">
                                    <button className={("btn btn-") + (null === sucursalSelect ? "success" : "outline-success")} onClick={() => setsucursalSelect(null)}>RESUMEN</button>

                                    {sucursales.map(e =>
                                        <button key={e.id} className={("btn btn-") + (e.id == sucursalSelect ? "success" : "outline-success")} onClick={() => setsucursalSelect(e.id)}>{e.codigo}</button>
                                    )}
                                </div>
                            </div>
                        </div>
                        <Creditos
                            getsucursalDetallesData={getsucursalDetallesData}
                            sucursalDetallesData={sucursalDetallesData}
                            moneda={moneda}
                        />
                    </>

                :null
            }
        </>
    )
}