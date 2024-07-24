import FechasMain from './panel/fechasmain'
import { useEffect } from "react";

export default function Aprobtransferencia({
    setsucursalSelect,
    sucursalSelect,
    sucursales,
    qestatusaprobaciocaja,
    setqestatusaprobaciocaja,
    sucursalDetallesData,
    colorSucursal,
    colors,
    moneda,
    getBancoName,
    aprobarTransferenciaFun,
    getsucursalDetallesData,
    subviewpanelsucursales,
    fechasMain1,
    fechasMain2,
    setfechasMain1,
    setfechasMain2,
    qfiltroaprotransf,
    setqfiltroaprotransf,
    bancoqfiltroaprotransf,
    setbancoqfiltroaprotransf,
    opcionesMetodosPago,
}){
    useEffect(()=>{
        getsucursalDetallesData(null, "aprobtransferencia")
    },[
        fechasMain1,
        fechasMain2,
        sucursalSelect,
        bancoqfiltroaprotransf,
        qestatusaprobaciocaja,
    ])

    return (
        <div className="container">
            <FechasMain
                fechasMain1={fechasMain1}
                fechasMain2={fechasMain2}
                setfechasMain1={setfechasMain1}
                setfechasMain2={setfechasMain2}
            />
            <div className="">
                <form className="input-group mb-2" onSubmit={event=>{event.preventDefault();getsucursalDetallesData(null, "aprobtransferencia")}}>
                    <button type='button' className={("btn btn-"+(qestatusaprobaciocaja==0?"sinapsis":""))} onClick={e=>{setqestatusaprobaciocaja(0);if(qestatusaprobaciocaja==0){getsucursalDetallesData()}}}><i className="fa fa-clock-o"></i></button>
                    <button type='button' className={("btn btn-"+(qestatusaprobaciocaja==1?"success":""))} onClick={e=>{setqestatusaprobaciocaja(1);if(qestatusaprobaciocaja==1){getsucursalDetallesData()}}}><i className="fa fa-check"></i></button>
                    
                    <select className="form-control" value={bancoqfiltroaprotransf}  onChange={event=>setbancoqfiltroaprotransf(event.target.value)}>
                        <option value="">-BANCO-</option>
                        {opcionesMetodosPago.map(e=>
                            <option key={e.id} value={e.codigo}>{e.codigo}</option>
                        )}
                    </select>
                    <select className="form-control" onChange={e=>setsucursalSelect(e.target.value)} value={sucursalSelect===null?"":sucursalSelect}>
                        <option value="">-TODAS SUCURSALES-</option>    
                        {
                            sucursales.map(e=>
                                <option key={e.id} value={e.id}>{e.codigo}</option>    
                            )
                        }
                    </select>
                    <input type="text" className="form-control" value={qfiltroaprotransf} placeholder='Buscar por Ref o Monto...' onChange={event=>setqfiltroaprotransf(event.target.value)}/>    
                    <button className={"btn btn-success"} type='submit'><i className="fa fa-search"></i></button>


                </form>
                { 
                sucursalDetallesData.aprobaciontransferenciasdata?sucursalDetallesData.aprobaciontransferenciasdata.length
                ? sucursalDetallesData.aprobaciontransferenciasdata.map( (e,i) =>
                    <div 
                    key={e.id}
                    className={(!e.estatus?"bg-sinapsis-light":"bg-light")+" text-secondary card mb-3 pointer shadow border border-dark"}>
                        <div className="card-header flex-row justify-content-between">
                            <div className="d-flex justify-content-between">
                                <div className="w-50">
                                    <button className="btn fw-bolder" style={{
                                        backgroundColor:colorSucursal(e.sucursal.codigo),
                                    }}>{e.sucursal.codigo}</button>
                                    
                                </div>
                                <div className="w-50 text-right">
                                    {
                                        e.saldo!=0?
                                            <>
                                                <span className="h6 text-muted font-italic fs-3">Monto <b>{moneda(e.saldo)}</b></span>
                                                <br />
                                                {e.montoretencion?
                                                    <span className="h6 text-muted font-italic fs-3">Retención <b>{moneda(e.montoretencion)}</b></span>
                                                :null}
                                                <br />
                                                {e.montoretencion?
                                                    <span className="h6 text-muted font-italic fs-3">Total Transferido <b>{moneda(parseFloat(e.montoretencion)+parseFloat(e.saldo))}</b></span>
                                                :null}
                                            </>
                                        :null
                                    }

                                </div>
                            </div>
                        </div>
                        <div className="text-center">
                            <span className="card-title ">
                                <small className="fst-italic fs-2">
                                <b>{e.loteserial}</b>
                                <br /> 
                                <button className="btn fw-bolder m-1" 
                                style={{
                                    backgroundColor:colors[e.banco]?colors[e.banco][0]:"", 
                                    color:colors[e.banco]?colors[e.banco][1]:""
                                }}>{getBancoName(e.banco)}</button>
                                </small><br/>
                            </span>
                        </div> 
                        <div className="card-body d-flex justify-content-between">
                            <i onClick={()=>aprobarTransferenciaFun(e.id,"delete")} className="fa fa-times text-danger"></i>
                            <span className="text-success" onClick={()=>aprobarTransferenciaFun(e.id,"aprobar")}>{e.estatus==0?"APROBAR":"REVERSAR"} <i className="fa fa-check"></i></span>
                        </div>
                        <div className="text-center text-muted">
                            <small>{e.created_at}</small>
                        </div>
                            
                    </div>
                )
                : null : null
                }
            </div>
        </div>
    )
}