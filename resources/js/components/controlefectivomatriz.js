import { useEffect } from "react";
import ModalNuevoEfectivo  from "./modalNuevoEfectivo";
export default function ControlEfectivoMatriz({
    controlefecQ,    
    setcontrolefecQ,
    controlefecQDesde,    
    setcontrolefecQDesde,
    controlefecQHasta,    
    setcontrolefecQHasta,
    controlefecData,    
    controlefecSelectGeneral,    
    setcontrolefecSelectGeneral,
    controlefecNewConcepto,    
    setcontrolefecNewConcepto,
    controlefecNewFecha,
    setcontrolefecNewFecha,
    controlefecNewCategoria,    
    setcontrolefecNewCategoria,
    controlefecNewMonto,    
    setcontrolefecNewMonto,
    getControlEfec,    
    setControlEfec,    
    setcontrolefecQCategoria, 
    controlefecQCategoria,
    number,
    moneda,
    controlefecNewMontoMoneda,
    setcontrolefecNewMontoMoneda,
    categoriasCajas,
    getcatsCajas,
    delCaja,
    personalNomina,
    getNomina,
    setopenModalNuevoEfectivo,
    openModalNuevoEfectivo,
    verificarMovPenControlEfec,
    verificarMovPenControlEfecTRANFTRABAJADOR,
    allProveedoresCentral,
    getAllProveedores,
    getAlquileres,
    alquileresData,
    sucursalesCentral,
    transferirpedidoa,
    settransferirpedidoa,
    getSucursales,
    reversarMovPendientes,
    aprobarRecepcionCaja,
    dolar,
    peso,
    formatAmount,
    qbuscarcat,
    setqbuscarcat,
    colorsGastosCat,

    selectdepositobanco,
    bancodepositobanco,
    setbancodepositobanco,
    opcionesMetodosPago,
    fechadepositobanco,
    setfechadepositobanco,
    depositarmatrizalbanco,
    setselectdepositobanco,
    colorSucursal,
    setConciliarMovCajaMatriz,
}){ 

    useEffect(()=>{
        getcatsCajas()
        getNomina()
        getAlquileres()
        getSucursales()
    },[]);
    
    useEffect(()=>{
        getControlEfec()
        setcontrolefecQCategoria("")

    },[
        controlefecSelectGeneral,
        controlefecQDesde,
        controlefecQHasta,
    ])


    let catselect = categoriasCajas.filter(e=>e.id==controlefecNewCategoria).length?categoriasCajas.filter(e=>e.id==controlefecNewCategoria)[0].nombre:""

    
    
    const getCatFun = (id_cat) => {
        let catfilter = categoriasCajas.filter(e=>e.id==id_cat)
        if (catfilter.length) {
            return catfilter[0].nombre
        }

        return "ERROR"
    }

    const getCatGeneralFun = (id_cat) => {

        let catgeneralList = [
            {color:"#ffceb4", nombre:"PAGO A PROVEEDORES"}	,
            {color:"#00ff00", nombre:"INGRESO"}	,
            {color:"#ff9900", nombre:"GASTO"}	,
            {color:"#b45f06", nombre:"GASTO GENERAL"}	,
            {color:"#6F6A6A", nombre:"TRANSFERENCIA TRABAJADOR"}	,
            {color:"#434343", nombre:"MOVIMIENTO NULO INTERNO"}	,
            {color:"#fff2cc", nombre:"CAJA MATRIZ"}	,
            {color:"#b7b7b7", nombre:"FDI"}	,
            {color:"#6aa84f", nombre:"INGRESO EXTERNO"}	,
            {color:"#93c47d", nombre:"INGRESO INTERNO"}	,
            {color:"#999999", nombre:"TRANSFERENCIA EFECTIVO SUCURSAL"}	,
        ]

        let catfilter = categoriasCajas.filter(e=>e.id==id_cat)
        if (catfilter.length) {
            return catgeneralList[catfilter[0].catgeneral]
        }

        return {color:"", nombre:""}

    }
    const getSu = id_sucursal => {
        let fil = sucursalesCentral.filter(e=>e.id==id_sucursal)
        if (fil.length) {
            return fil[0].codigo
        }
        return "NO IDENTIFICADO"
    } 



    
    return (
        <div className="container-fluid">

          {/*   <div className="text-center p-3">
                {openModalNuevoEfectivo?
                    <button onClick={()=>setopenModalNuevoEfectivo(false)} className="btn btn-outline-sinapsis">OCULTAR NUEVO MOVIMIENTO <i className="fa fa-arrow-up"></i> </button>
                    :
                    <button onClick={()=>setopenModalNuevoEfectivo(true)} className="btn btn-outline-success ">NUEVO MOVIMIENTO <i className="fa fa-arrow-down"></i></button>
                }
            </div>

            {openModalNuevoEfectivo&&
                <ModalNuevoEfectivo
                    colorsGastosCat={colorsGastosCat }
                    qbuscarcat={qbuscarcat}
                    setqbuscarcat={setqbuscarcat}
                    formatAmount={formatAmount}
                    dolar={dolar}
                    peso={peso}
                    getSucursales={getSucursales}
                    transferirpedidoa={transferirpedidoa}
                    settransferirpedidoa={settransferirpedidoa}
                    sucursalesCentral={sucursalesCentral}
                    allProveedoresCentral={allProveedoresCentral}
                    getAllProveedores={getAllProveedores}
                    setopenModalNuevoEfectivo={setopenModalNuevoEfectivo}
                    openModalNuevoEfectivo={openModalNuevoEfectivo}
                    setControlEfec={setControlEfec}
                    catselect={catselect}
                    setcontrolefecNewConcepto={setcontrolefecNewConcepto}
                    controlefecNewFecha={controlefecNewFecha}
                    setcontrolefecNewFecha={setcontrolefecNewFecha}
                    controlefecNewConcepto={controlefecNewConcepto}
                    controlefecNewMonto={controlefecNewMonto}
                    setcontrolefecNewMonto={setcontrolefecNewMonto}
                    controlefecNewMontoMoneda={controlefecNewMontoMoneda}
                    setcontrolefecNewMontoMoneda={setcontrolefecNewMontoMoneda}
                    controlefecNewCategoria={controlefecNewCategoria}
                    setcontrolefecNewCategoria={setcontrolefecNewCategoria}

                    personalNomina={personalNomina}
                    categoriasCajas={categoriasCajas}
                    controlefecSelectGeneral={controlefecSelectGeneral}
                    setcontrolefecSelectGeneral={setcontrolefecSelectGeneral }
                    moneda={moneda}
                    number={number}
                    alquileresData={alquileresData}
                    getAlquileres={getAlquileres}
                    getNomina={getNomina}
                >

                </ModalNuevoEfectivo>
            } */}

           {/*  <div className="btn-group mb-3">
            </div> */}

            {/* <div className="mb-3 d-flex justify-content-center">
                <button className={"btn btn-outline-"+(controlefecSelectGeneral==1?"success":"sinapsis")+" btn-lg"} onClick={e=>setopenModalNuevoEfectivo(true)}>NUEVO MOVIMIENTO <i className="fa fa-plus"></i></button>
                </div> */}

            <form className="input-group mb-3" onSubmit={event=>{getControlEfec();event.preventDefault()}}>

                <button type="button" className={("btn ") + (controlefecSelectGeneral == 1 ?"btn-success":"btn-outline-success")} onClick={()=>setcontrolefecSelectGeneral(1)}>Caja Fuerte</button> 
                <button type="button" className={("btn ") + (controlefecSelectGeneral == 0 ? "btn-sinapsis" : "btn-outline-sinapsis")} onClick={() => setcontrolefecSelectGeneral(0)}>Caja Chica</button>
                {/* <button className="btn btn-warning" onClick={verificarMovPenControlEfec}>VERIFICAR PENDIENTES <i className="fa fa-clock-o"></i></button>
                <button className="btn btn-outline-danger" onClick={reversarMovPendientes}>REVERSAR PENDIENTE <i className="fa fa-times"></i></button> */}
                
                <input type="text" className="form-control fs-3"
                    placeholder="Buscar..."
                    onChange={e => setcontrolefecQ(e.target.value)}
                    value={controlefecQ} />
                <select
                    className="form-control fs-3"
                    onChange={e => setcontrolefecQCategoria(e.target.value)}
                    value={controlefecQCategoria}>
                        <option value="">-CATEGORÍA-</option>
                    {categoriasCajas.filter(e=>e.tipo==controlefecSelectGeneral).map((e,i)=>
                        <option key={i} value={e.id}>{e.nombre}</option>
                    )}

                </select>

                <input type="date" className="form-control fs-3"
                    onChange={e => setcontrolefecQDesde(e.target.value)}
                    value={controlefecQDesde} />

                <input type="date" className="form-control fs-3"
                    onChange={e => setcontrolefecQHasta(e.target.value)}
                    value={controlefecQHasta} />
               {/*  <button className="btn btn-warning" onClick={verificarMovPenControlEfecTRANFTRABAJADOR}>VERIFICAR TRANSFERENCIAS TRABAJADOR <i className="fa fa-clock-o"></i></button> */}



                <button className="btn btn-outline-secondary" type="button"><i className="fa fa-search"></i></button>
            </form>

            <table className="table">
                <thead>
                    <tr>
                        <td>ORIGEN</td>
                        <th>CREADO</th>
                        <th>FECHA</th>
                        <th>CAT GENERAL</th>
                        <th className="w-20">CATEGORÍA</th>
                        <th>DESCRIPCIÓN</th>
                        <th className="text-right">Monto DOLAR</th>
                        <th className="">Balance DOLAR</th>
                        <th className="text-right">Monto BS</th>
                        <th className="">Balance BS</th>
                        <th className="text-right">Monto PESO</th>
                        <th className="">Balance PESO</th>

                        <th className="text-right">Monto EURO</th>
                        <th className="">Balance EURO</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    {controlefecData ? controlefecData.data ? controlefecData.data.length?
                        controlefecData.data.map((e,i)=>
                        <tr key={e.id} onDoubleClick={()=>setConciliarMovCajaMatriz(e.id)} className={(e.revisado==1?"bg-success-light":"")+" pointer"}>
                            <td>
                                {e.sucursal_origen?
                                    <button className={"btn w-100 fw-bolder fs-6 "} style={{backgroundColor:colorSucursal(e.sucursal_origen.codigo)}}>
                                        {e.sucursal_origen.codigo}
                                    </button>
                                
                                :
                                    <button className={"btn w-100 fw-bolder fs-6 "} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>
                                        {e.sucursal.codigo}
                                    </button>
                                }
                            </td>
                            <td className="text-center" colSpan={2}>
                                <b className="text-muted">{e.fecha}</b>
                                <br />
                                <small className="text-muted">{e.created_at}</small>
                            </td>
                            <td className="">
                                <button className="btn w-100 btn-sm" style={{color:"black",fontWeight:"bold",backgroundColor:getCatGeneralFun(e.categoria).color}}>{getCatGeneralFun(e.categoria).nombre}</button>
                            </td>
                            <td className="w-20">{getCatFun(e.categoria)}</td>
                            <td className="">
                                {e.concepto}
                                {e.id_sucursal_destino?
                                    <div>
                                        <b>TRANSFERIR A SUCURSAL ({getSu(e.id_sucursal_destino)})</b>
                                    </div>
                                :null}

                                {e.id_sucursal_emisora?
                                    <div>
                                        <b>RECIBES DE SUCURSAL ({getSu(e.id_sucursal_emisora)})</b>
                                    </div>
                                :null}
                                {e.id_sucursal_emisora&&e.estatus==0?
                                    <div>
                                        <div className="p-2">
                                            <div className="btn-group">
                                                <button className="btn btn-success" onClick={()=>aprobarRecepcionCaja(e.idincentralrecepcion,"aprobar")}>APROBAR RECEPCIÓN</button>
                                                <button className="btn btn-danger" onClick={()=>aprobarRecepcionCaja(e.idincentralrecepcion,"rechazar")}>RECHAZAR RECEPCIÓN</button>
                                            </div>
                                        </div>
                                    </div>
                                :null}

                                {e.proveedor?
                                    <>
                                        <br />
                                        <b>({e.proveedor.descripcion})</b>
                                    </>
                                :null}

                                {e.beneficiario?
                                    <>
                                        <br />
                                        <b>({e.beneficiario.nominanombre})</b>
                                    </>
                                :null}

                            </td>
                            
                            <td className={(e.montodolar<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montodolar)}</td>
                            <td className={("")}>{moneda(e.dolarbalance)}</td>
                            
                            <td className={(e.montobs<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montobs)}</td>
                            <td className={("")}>{moneda(e.bsbalance)}</td>
                            
                            <td className={(e.montopeso<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montopeso)}</td>
                            <td className={("")}>{moneda(e.pesobalance)}</td>

                            <td className={(e.montoeuro<0? "text-danger": "text-success")+(" text-right")}>{moneda(e.montoeuro)}</td>
                            <td className={("")}>{moneda(e.eurobalance)}</td>


                            <td>
                            {(e.montobs!="0.00"&&e.montobs>0 && !e.id_sucursal_deposito)|| (e.montodolar!="0.00"&&e.montodolar>0 && !e.id_sucursal_deposito)?
                                <>
                                    {selectdepositobanco==e.id?
                                        <div className="input-group">
                                            <select className="form-control" value={bancodepositobanco}  onChange={event=>setbancodepositobanco(event.target.value)}>
                                                <option value="">-BANCO-</option>
                                                {opcionesMetodosPago.map(e=>
                                                    <option key={e.id} value={e.id}>{e.codigo}</option>
                                                )}
                                            </select>
                                            <input type="date" className="form-control" value={fechadepositobanco}  onChange={event=>setfechadepositobanco(event.target.value)} />
                                            <button className="btn btn-sinapsis" onClick={()=>depositarmatrizalbanco(e.id)}> <i className="fa fa-paper-plane"></i> </button>
                                        </div>
                                    :  
                                        <button className="btn btn-sinapsis" onClick={()=>setselectdepositobanco(e.id)}>Depositar al Banco <i className="fa fa-arrow-right"></i></button>
                                    }
                                </>
                            :null}


                            </td>
                            
                        </tr>)
                    :null:null:null}
                </tbody>
            </table>


            
        </div>
    )
}