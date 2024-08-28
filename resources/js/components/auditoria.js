import { cloneDeep } from "lodash";

import { useEffect, useState } from "react";
import PanelOpciones from './panel/panelopciones';
import Aprobtransferencia from './aprobtransferencia';
import CuadreGeneral from './cuadregeneral';
import AuditoriaEfectivo from './auditoriaefectivo';

export default function Auditoria({
    getBancoName,
    opcionesMetodosPago,

    fechaSelectAuditoria,
    setfechaSelectAuditoria,
    
    fechaHastaSelectAuditoria,
    setfechaHastaSelectAuditoria,
    
    bancoSelectAuditoria,
    setbancoSelectAuditoria,
    qdescripcionbancosdata,
    setqdescripcionbancosdata,

    sucursales,

    sucursalSelectAuditoria,
    setsucursalSelectAuditoria,
    tipoSelectAuditoria,
    settipoSelectAuditoria,
    ingegreSelectAuditoria,
    setingegreSelectAuditoria,
    
    getMetodosPago,
    getBancosData,
    colorsGastosCat,
    getCatCajas,

    bancosdata,

    moneda,
    selectxMovimientos,
    movimientoAuditoria,
    setmovimientoAuditoria,
    subviewAuditoria,
    setsubviewAuditoria,

    setselectTrLiquidar,
    selectTrLiquidar,
    inpmontoLiquidar,
    setinpmontoLiquidar,
    inpfechaLiquidar,
    setinpfechaLiquidar,
    liquidarMov,
    orderAuditoria,
    setorderAuditoria,
    orderColumnAuditoria,
    setorderColumnAuditoria,
    selectConciliacion,
    saldoactualbancofecha,
    setsaldoactualbancofecha,
    sendsaldoactualbancofecha,
    selectConciliacionData,
    setselectConciliacionData,
    colorFun,
    colors,
    colorSucursal,
    reverserLiquidar,
    changeBank,
    subviewAuditoriaGeneral,
    setsubviewAuditoriaGeneral,

    qfiltroaprotransf,
    setqfiltroaprotransf,
    bancoqfiltroaprotransf,
    setbancoqfiltroaprotransf,
    setsubviewpanelsucursales,
    subviewpanelsucursales,
    fechasMain1,
    fechasMain2,
    sucursalSelect,
    setsucursalSelect,
    qestatusaprobaciocaja,
    setfechasMain1,
    setfechasMain2,
    aprobarTransferenciaFun,
    getSucursales,
    getsucursalDetallesData,
    sucursalDetallesData,
    setqestatusaprobaciocaja,
    permiso,

    autoliquidarTransferencia,
    fechaAutoLiquidarTransferencia,
    setfechaAutoLiquidarTransferencia,
    bancoAutoLiquidarTransferencia,
    setbancoAutoLiquidarTransferencia,

    sucursalqcuadregeneral,
    setsucursalqcuadregeneral,
    fechadesdeqcuadregeneral,
    setfechadesdeqcuadregeneral,
    fechahastaqcuadregeneral,
    setfechahastaqcuadregeneral,
    datacuadregeneral,
    getCuadreGeneral,
    number,
    formatAmount,


    getAuditoriaEfec,
    qauditoriaefectivo,
    setqauditoriaefectivo,
    sucursalqauditoriaefectivo,
    setsucursalqauditoriaefectivo,
    fechadesdeauditoriaefec,
    setfechadesdeauditoriaefec,
    fechahastaauditoriaefec,
    setfechahastaauditoriaefec,
    setqcajaauditoriaefectivo,
    qcajaauditoriaefectivo,
    dataAuditoriaEfectivo,

    setshownewmovnoreportado,
    shownewmovnoreportado,
    saveNewmovnoreportado,
    newmovnoreportadomonto,
    setnewmovnoreportadomonto,
    newmovnoreportadobanco,
    setnewmovnoreportadobanco,
    newmovnoreportadofecha,
    setnewmovnoreportadofecha,

    newmovnoreportadoref,
    setnewmovnoreportadoref,

    inpmontoNoreportado,
    setinpmontoNoreportado,
    inpfechaNoreportado,
    setinpfechaNoreportado,
    reportarMov,

    
    setshowimportliquidacion,
    showimportliquidacion,
    textimportliquidadcion,
    settextimportliquidadcion,
    procesarImportTextliquidacion,
    setdataimportliquidacion,
    dataimportliquidacion,
    showallSelectAuditoria,
    setshowallSelectAuditoria,
    setbancosdata,
}){
    useEffect(()=>{
        getMetodosPago()
        getBancosData()
        getCatCajas()
        getSucursales()
        setsubviewpanelsucursales("aprobtransferencia")
        setsubviewAuditoriaGeneral("")

    },[])

    useEffect(()=>{
        getBancosData()
    },[
        qdescripcionbancosdata,
        /* fechaSelectAuditoria,
        fechaHastaSelectAuditoria, */
        bancoSelectAuditoria,
        sucursalSelectAuditoria, 
        orderColumnAuditoria,
        orderAuditoria,
    ])


    const getCat = id => {
        
        return id
    }

    const [showautoliquidar, setshowautoliquidar] = useState(false)
  
   
    const [nummm, setnummm] = useState(0)
    
    const [selectindexliquidar, setselectindexliquidar] = useState(null)


    const [bloquesReportado, setbloquesReportado] = useState([
       // {num:2,index:1},
        //{num:2,index:5},
    ])
    const [bloquesBanco, setbloquesBanco] = useState([
       // {num:2,index:1},
       // {num:1,index:3},
    ])




    let opcionesAuditoria = [
        {
          route: "banco",
          name: "BANCO"
        },
        {
            route: "efectivo",
            name: "EFECTIVO "
          },
        {
          route: "cuadregeneral",
          name: "CONCILIACIÓN GENERAL"
        },
    
        {
          route: "aprobtransferencia",
          name: "APROBAR TRANSFERENCIAS"
        },
    
    ]

    const [movermovbancodesde, setmovermovbancodesde] = useState(null)
    const [movermovbancohasta, setmovermovbancohasta] = useState(null)

    const movermovbanco = index => {
        if (movermovbancodesde!==null) {
            if (movermovbancohasta!==null) {
                //mover
                let c = cloneDeep(dataimportliquidacion)

                let desde = c[movermovbancodesde]
                let hasta = c[movermovbancohasta]
                
                c[movermovbancohasta] = desde
                c[movermovbancodesde] = hasta

                setdataimportliquidacion(c)
                setmovermovbancodesde(null)
                setmovermovbancohasta(null)
            }else{
                setmovermovbancohasta(index)
            }
        }else{
            setmovermovbancodesde(index)
        }
    }

    const addBloque = (index,type) => {
        let num = 1
        if (num) {
            switch (type) {
                case "banco":
                    let dataclone = cloneDeep(dataimportliquidacion)


                    setdataimportliquidacion(dataclone.toSpliced(index, 0, {
                        ajuste:true
                    }))
                    //setbloquesBanco(bloquesBanco.concat(arr))    
                break;
                    case "reportado":
                        
                        let bancosdataclone = cloneDeep(bancosdata)
                        let xliquidarclone = bancosdataclone.xliquidar.toSpliced(index, 0, {
                            ajuste:true
                        })
                        bancosdataclone.xliquidar = xliquidarclone
                        setbancosdata(bancosdataclone)    
                    //setbloquesReportado(bloquesReportado.concat(arr))    
                break;
            }
        }
    }

    const delBloque = (index,type) => {
        switch (type) {
            case "banco":
                let dataclone = cloneDeep(dataimportliquidacion)
                setdataimportliquidacion(dataclone.filter((e,i)=>i!=index))  

            break;
            case "reportado":
                let bancosdataclone = cloneDeep(bancosdata)
                let xliquidarclone = bancosdataclone.xliquidar.filter((e,i)=>i!=index)
                bancosdataclone.xliquidar = xliquidarclone
                setbancosdata(bancosdataclone)  

            break;
        }
    }

    let reportBanco = [
    ]
    let sumaReporteBanco = 0
    
    let reportSistema = [
    ]
    let sumaReporteSistema = 0
    const getReportLiquidacion = () => {
        if (bancosdata.xliquidar) {
            bancosdata.xliquidar.map((e,i)=>{
                if (e.ajuste) {
                    if (dataimportliquidacion[i]) {
    
                        if (!dataimportliquidacion[i].ajuste) {
                            let ref = dataimportliquidacion[i].ref
                            let monto = dataimportliquidacion[i].monto
                            let banco = dataimportliquidacion[i].codigo
                            reportBanco.push({
                                id: i,
                                msj: "Disponible en BANCO y no reportada ",
                                monto,
                                ref,
                                banco,
                            })
                        }
                    }
                }
            })
        }

        if (dataimportliquidacion.length) {
            dataimportliquidacion.map((e,i)=>{
                if (e.ajuste) {
                    if (bancosdata.xliquidar[i]) {
    
                        if (!bancosdata.xliquidar[i].ajuste) {
                            let ref = bancosdata.xliquidar[i].loteserial
                            let monto = bancosdata.xliquidar[i].monto
                            let banco = bancosdata.xliquidar[i].banco
                            reportSistema.push({
                                id: i,
                                msj: "Reportada en SISTEMA y no en BANCO ",
                                monto,
                                ref,
                                banco,
                            })
                        }
                    }
                }
            })
        }


    }
    getReportLiquidacion()

    const getSumReport = () => {
        if (bancosdata.xliquidar) {
            let sumsistema = (bancosdata.xliquidar.filter(e=>!e.ajuste).reduce((a,b)=>{return a+parseFloat(b.monto)},0))
            let sumbanco = (dataimportliquidacion.filter(e=>!e.ajuste).reduce((a,b)=>{return a+parseFloat(b.monto)},0))

            let sumreportsistema = (reportSistema.reduce((a,b)=>{return a+parseFloat(b.monto)},0))
            let sumreportbanco = (reportBanco.reduce((a,b)=>{return a+parseFloat(b.monto)},0))

            sumaReporteSistema = sumsistema-sumreportsistema
            sumaReporteBanco =sumbanco-sumreportbanco
        }
    }
    getSumReport()

    const colorPerfect = i => {
        let banco = dataimportliquidacion[i]
        let sistema = bancosdata.xliquidar[i]
        if (banco && sistema) {
            let resta = parseFloat(banco.monto)-parseFloat(sistema.monto)

            if (resta==0 && banco.ref.indexOf(sistema.loteserial)!=-1) {
                return "bg-success"
            }

            if ((resta>=-3 && resta<=3) && banco.ref.indexOf(sistema.loteserial)!=-1) {
                return "bg-success-light"
            }

            if ((resta==0) && banco.ref.indexOf(sistema.loteserial)==-1) {
                return "bg-sinapsis-light"
            }
        }
        return ""
    }

    
    return (
        <>

        {subviewAuditoriaGeneral==""?
            <PanelOpciones
                viewmainPanel={null}
                setviewmainPanel={setsubviewAuditoriaGeneral}
                opciones={opcionesAuditoria}

            />
        :null}
        {permiso([1,2,3,15]) && subviewAuditoriaGeneral=="banco"?
            <div className="container-fluid">
                    <div className="d-flex justify-content-center">
                        <div className="btn-group m-2">
                            <button className={("btn btn-sm ")+(subviewAuditoria=="liquidar"?"btn-sinapsis":"")} onClick={()=>{setsubviewAuditoria("liquidar");getBancosData("liquidar")}}>Liquidar</button>
                            <button className={("btn btn-sm ")+(subviewAuditoria=="cuadre"?"btn-sinapsis":"")} onClick={()=>{setsubviewAuditoria("cuadre");getBancosData("cuadre")}}>Movimientos</button>
                            <button className={("btn btn-sm ")+(subviewAuditoria=="conciliacion"?"btn-sinapsis":"")} onClick={()=>{setsubviewAuditoria("conciliacion");getBancosData("conciliacion")}}>Cuadre</button>
                        </div>
                    </div>

                    {
                        subviewAuditoria=="cuadre" && bancosdata.view=="cuadre"? 
                            <>
                                <div className="row">
                                    <div className="col">

                                        <div className="form-group">
                                            <div className="input-group">
                                                <select className="form-control" value={bancoSelectAuditoria}  onChange={event=>setbancoSelectAuditoria(event.target.value)}>
                                                    <option value="">-BANCO-</option>
                                                    {opcionesMetodosPago.map(e=>
                                                        <option key={e.id} value={e.id}>{e.codigo}</option>
                                                    )}
                                                </select>
                                                <select className="form-control" value={sucursalSelectAuditoria}  onChange={event=>setsucursalSelectAuditoria(event.target.value)}>
                                                    <option value="">-SUCURSAL-</option>
                                                    {sucursales.map(e=>
                                                        <option key={e.id} value={e.id}>{e.codigo}</option>
                                                    )}
                                                </select>
                                                <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={event=>setfechaSelectAuditoria(event.target.value)}/>    
                                                <input type="date" className="form-control" value={fechaHastaSelectAuditoria} onChange={event=>setfechaHastaSelectAuditoria(event.target.value)}/>    
                                                <input type="text" className="form-control" placeholder="Buscar Descripción o Monto..." value={qdescripcionbancosdata} onChange={event=>setqdescripcionbancosdata(event.target.value)}/>    
                                                <button className="btn btn-success" onClick={()=>getBancosData()}><i className="fa fa-search"></i></button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col">
                                        <table className="table table-sm table-striped ">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th colSpan={8} className="text-center text-success">INGRESO</th>
                                                    <th colSpan={4} className="text-center text-danger">EGRESO</th>
                                                </tr>

                                                <tr>
                                                    <th className="text-center">BANCO</th>
                                                    <th colSpan={2} className="text-right bg-primary-light pointer">  
                                                        <button className="btn" style={{backgroundColor:colors["Transferencia"]}} onClick={()=>selectxMovimientos("ingreso_Transferencia", null)}>TRANSFERENCIA</button> 
                                                    </th>
                                                    <th colSpan={2} className="text-right bg-warning-light pointer"> <button className="btn" onClick={()=>selectxMovimientos("ingreso_PUNTO", null)} style={{backgroundColor:colors["PUNTO"]}}>PUNTO</button> </th>
                                                    <th colSpan={2} className="text-right bg-danger-light pointer"> <button className="btn" onClick={()=>selectxMovimientos("ingreso_BIOPAGO", null)} style={{backgroundColor:colors["BIOPAGO"]}}>BIOPAGO</button> </th>
                                                    
                                                    <th className="text-right bg-success"></th>
                                                    <th className="text-right bg-success"></th>

                                                    <th colSpan={2} className="text-right bg-primary-light pointer"> <button className="btn" onClick={()=>selectxMovimientos("egreso_Transferencia", null)} style={{backgroundColor:colors["Transferencia"]}}>TRANSFERENCIA</button> </th>
                                                    
                                                    <th className="text-center bg-danger"></th>
                                                    <th className="text-center bg-danger"></th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                {bancosdata.puntosybiopagosxbancos? Object.entries(bancosdata.puntosybiopagosxbancos).map(bancos=>
                                                    <tr key={bancos[0]}>
                                                        <th className="pointer align-middle"> 
                                                            <button className="btn w-100 fw-bolder" 
                                                            style={{
                                                                backgroundColor:colors[bancos[0]]?colors[bancos[0]][0]:"", 
                                                                color:colors[bancos[0]]?colors[bancos[0]][1]:""
                                                            }}
                                                            onClick={()=>selectxMovimientos("banco", bancos[0])}
                                                            >{bancos[0]}</button>
                                                        </th>
                                                        {bancos[1]?
                                                            <>
                                                                {
                                                                    bancos[1]["ingreso"]?
                                                                    <>
                                                                        <td className="fw-bolder text-right bg-primary-light align-middle pb-1 pt-1">
                                                                            

                                                                            {bancos[1]["ingreso"]["Transferencia"]?
                                                                            <>
                                                                                <span className="text-success">
                                                                                    {moneda(bancos[1]["ingreso"]["Transferencia"]["monto_liquidado"])} 
                                                                                </span>
                                                                                <hr className="m-0" />
                                                                                <span className="text-sinapsis">
                                                                                    {moneda(bancos[1]["ingreso"]["Transferencia"]["monto"])} 
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                            
                                                                        </td>
                                                                        <td className="align-middle pb-1 pt-1 bg-primary-light">
                                                                            {bancos[1]["ingreso"]["Transferencia"]?
                                                                            <>
                                                                                <span className="text-danger">
                                                                                    {moneda(bancos[1]["ingreso"]["Transferencia"]["monto_comision"])} <hr className="m-0" /> 
                                                                                    ({moneda(bancos[1]["ingreso"]["Transferencia"]["porcentaje"])}%)
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                        </td>


                                                                        <td className="fw-bolder text-right bg-warning-light align-middle pb-1 pt-1">
                                                                            

                                                                            {bancos[1]["ingreso"]["PUNTO"]?
                                                                            <>
                                                                                <span className="text-success">
                                                                                    {moneda(bancos[1]["ingreso"]["PUNTO"]["monto_liquidado"])} 
                                                                                </span>
                                                                                <hr className="m-0" />
                                                                                <span className="text-sinapsis">
                                                                                    {moneda(bancos[1]["ingreso"]["PUNTO"]["monto"])} 
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                            
                                                                        </td>
                                                                        <td className="align-middle pb-1 pt-1 bg-warning-light">
                                                                            {bancos[1]["ingreso"]["PUNTO"]?
                                                                            <>
                                                                                <span className="text-danger">
                                                                                    {moneda(bancos[1]["ingreso"]["PUNTO"]["monto_comision"])} <hr className="m-0" /> 
                                                                                    ({moneda(bancos[1]["ingreso"]["PUNTO"]["porcentaje"])}%)
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                        </td>


                                                                        <td className="fw-bolder text-right bg-danger-light align-middle pb-1 pt-1">
                                                                            

                                                                            {bancos[1]["ingreso"]["BIOPAGO"]?
                                                                            <>
                                                                                <span className="text-success">
                                                                                    {moneda(bancos[1]["ingreso"]["BIOPAGO"]["monto_liquidado"])} 
                                                                                </span>
                                                                                <hr className="m-0" />
                                                                                <span className="text-sinapsis">
                                                                                    {moneda(bancos[1]["ingreso"]["BIOPAGO"]["monto"])} 
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                            
                                                                        </td>
                                                                        <td className="align-middle pb-1 pt-1 bg-danger-light">
                                                                            {bancos[1]["ingreso"]["BIOPAGO"]?
                                                                            <>
                                                                                <span className="text-danger">
                                                                                    {moneda(bancos[1]["ingreso"]["BIOPAGO"]["monto_comision"])} <hr className="m-0" /> 
                                                                                    ({moneda(bancos[1]["ingreso"]["BIOPAGO"]["porcentaje"])}%)
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                        </td>

                                                                        <td className="fw-bolder text-right bg-success-superlight align-middle pb-1 pt-1 fs-4">
                                                                            

                                                                            {bancos[1]["ingreso"]?
                                                                            <>
                                                                                <span className="text-success">
                                                                                    {moneda(bancos[1]["ingreso"]["monto_liquidado"])} 
                                                                                </span>
                                                                                <hr className="m-0" />
                                                                                <span className="text-sinapsis">
                                                                                    {moneda(bancos[1]["ingreso"]["monto"])} 
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                            
                                                                        </td>
                                                                        <td className="align-middle pb-1 pt-1 bg-success-superlight">
                                                                            
                                                                            {bancos[1]["ingreso"]?
                                                                            <>
                                                                                <span className="text-danger">
                                                                                    {moneda(bancos[1]["ingreso"]["monto_comision"])} <hr className="m-0" /> 
                                                                                    ({moneda(bancos[1]["ingreso"]["porcentaje"])}%)
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                        </td>

                                                                    </>
                                                                    :null
                                                                }

                                                                {
                                                                    bancos[1]["egreso"]?
                                                                    <>
                                                                        <td className="fw-bolder text-right bg-primary-light align-middle pb-1 pt-1">
                                                                            

                                                                            {bancos[1]["egreso"]["Transferencia"]?
                                                                            <>
                                                                                <span className="text-success">
                                                                                    {moneda(bancos[1]["egreso"]["Transferencia"]["monto_liquidado"])} 
                                                                                </span>
                                                                                <hr className="m-0" />
                                                                                <span className="text-sinapsis">
                                                                                    {moneda(bancos[1]["egreso"]["Transferencia"]["monto"])} 
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                            
                                                                        </td>
                                                                        <td className="align-middle pb-1 pt-1 bg-primary-light">
                                                                            {bancos[1]["egreso"]["Transferencia"]?
                                                                            <>
                                                                                <span className="text-danger">
                                                                                    {moneda(bancos[1]["egreso"]["Transferencia"]["monto_comision"])} <hr className="m-0" /> 
                                                                                    ({moneda(bancos[1]["egreso"]["Transferencia"]["porcentaje"])}%)
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                        </td>

                                                                        <td className="fw-bolder text-right bg-danger-superlight align-middle pb-1 pt-1 fs-4">
                                                                            

                                                                            {bancos[1]["egreso"]?
                                                                            <>
                                                                                <span className="text-success">
                                                                                    {moneda(bancos[1]["egreso"]["monto_liquidado"])} 
                                                                                </span>
                                                                                <hr className="m-0" />
                                                                                <span className="text-sinapsis">
                                                                                    {moneda(bancos[1]["egreso"]["monto"])} 
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                            
                                                                        </td>
                                                                        <td className="align-middle pb-1 pt-1 bg-danger-superlight">
                                                                            {bancos[1]["egreso"]?
                                                                            <>
                                                                                <span className="text-danger">
                                                                                    {moneda(bancos[1]["egreso"]["monto_comision"])} <hr className="m-0" /> 
                                                                                    ({moneda(bancos[1]["egreso"]["porcentaje"])}%)
                                                                                </span>
                                                                            </>
                                                                            :null}
                                                                        </td>
                                                                    </>
                                                                    :null
                                                                }
                                                            </>
                                                        :console.log(bancos[1],"bancos[1]")}
                                                    </tr>
                                                ):null}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div className="row">
                                    {movimientoAuditoria.length?
                                        <div className="col">
                                            <table className="table">
                                                <thead>
                                                    <tr>
                                                        <th onClick={()=>{if(orderColumnAuditoria=="banco"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("banco")}} className="pointer">BANCO</th>
                                                        <th onClick={()=>{if(orderColumnAuditoria=="tipo"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("tipo")}} className="pointer">MÉTODO</th>
                                                        <th onClick={()=>{if(orderColumnAuditoria=="fecha"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha")}} className="pointer">FECHA REPORTADO</th>
                                                        <th onClick={()=>{if(orderColumnAuditoria=="fecha_liquidacion"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha_liquidacion")}} className="pointer">FECHA LIQUIDADO</th>
                                                        <th>TIPO</th>
                                                        <th onClick={()=>{if(orderColumnAuditoria=="id_sucursal"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("id_sucursal")}} className="pointer">SUCURSAL</th>
                                                        <th onClick={()=>{if(orderColumnAuditoria=="loteserial"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("loteserial")}} className="pointer">LOTE / REFERENCIA</th>
                                                        {/* <th onClick={()=>{if(orderColumnAuditoria=="categoria"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("categoria")}} className="pointer">CATEGORÍA</th> */}
                                                        <th onClick={()=>{if(orderColumnAuditoria=="monto"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto")}} className="pointer">MONTO BRUTO</th>
                                                        <th onClick={()=>{if(orderColumnAuditoria=="monto_liquidado"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto_liquidado")}} className="pointer">LIQUIDADO</th>
                                                        <th>COMISIÓN</th>
                                                        <th>%</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                {movimientoAuditoria.map(e=>
                                                    <tr key={e.id}>
                                                        <th>
                                                            <button className="btn w-100 fw-bolder" 
                                                            style={{
                                                                backgroundColor:colors[e.banco]?colors[e.banco][0]:"", 
                                                                color:colors[e.banco]?colors[e.banco][1]:""
                                                            }}>{e.banco}</button>
                                                                
                                                        </th>
                                                        <th>
                                                            <button className="btn w-100 fw-bolder" 
                                                            style={{backgroundColor:colors[e.tipo]}}>{e.tipo}</button> 
                                                        </th>
                                                        <th>{e.fecha}</th>
                                                        <th>{e.fecha_liquidacion}</th>
                                                        <th> <button className={("btn w-100 ")+(e.monto<0?"btn-danger":"btn-success")}>{e.monto<0?"EGRESO":"INGRESO"}</button> </th>
                                                        <th>
                                                            <button className={"btn w-100 fw-bolder "} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>{e.sucursal.codigo}</button>
                                                        </th>
                                                        <th>{e.loteserial}</th>
                                                        {/* <th>{getCat(e.categoria)}</th> */}
                                                        <th>{moneda(e.monto)}</th>
                                                        <th>{moneda(e.monto_liquidado)}</th>
                                                        <th className="text-danger">
                                                            {e.categoria!=50?
                                                                <>
                                                                    {moneda(e.monto_comision)}
                                                                </>
                                                            :null}

                                                        </th>
                                                        <th className="text-muted">
                                                            {e.categoria!=50?
                                                                <>
                                                                    {moneda(e.porcentaje)}%
                                                                </>
                                                            :null}
                                                        </th>
                                                        <th>
                                                            {e.categoria!=50?
                                                                <button className="btn btn-danger" onClick={()=>reverserLiquidar(e.id)}><i className="fa fa-arrow-left"></i></button>
                                                            :null}
                                                        </th>
                                                    </tr>
                                                )}
                                                </tbody>
                                            </table>
                                        </div>
                                    :null}
                                </div>
                            </>
                        :null
                    }

                    {
                        subviewAuditoria=="liquidar" && bancosdata.view=="liquidar"? 
                            <>
                                <div className="form-group">
                                    <div className="row">
                                        <div className="col">
                                            <div className="input-group">

                                                <select className="form-control" value={bancoSelectAuditoria}  onChange={event=>setbancoSelectAuditoria(event.target.value)}>
                                                    <option value="">-BANCO-</option>
                                                    {opcionesMetodosPago.map(e=>
                                                        <option key={e.id} value={e.id}>{e.codigo}</option>
                                                    )}
                                                </select>

                                                <select className="form-control" value={tipoSelectAuditoria}  onChange={event=>settipoSelectAuditoria(event.target.value)}>
                                                    <option value="">-MÉTODO-</option>
                                                    <option value="Transferencia">TRANSFERENCIA</option>
                                                    <option value="PUNTO">PUNTO</option>
                                                    <option value="BIOPAGO">BIOPAGO</option>
                                                </select>

                                                <select className="form-control" value={ingegreSelectAuditoria}  onChange={event=>setingegreSelectAuditoria(event.target.value)}>
                                                    <option value="">-TIPO-</option>
                                                    <option value="INGRESO">INGRESO</option>
                                                    <option value="EGRESO">EGRESO</option>
                                                </select>

                                                <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={event=>setfechaSelectAuditoria(event.target.value)}/>    
                                                <input type="date" className="form-control" value={fechaHastaSelectAuditoria} onChange={event=>setfechaHastaSelectAuditoria(event.target.value)}/>    
                                                <select className="form-control" value={sucursalSelectAuditoria}  onChange={event=>setsucursalSelectAuditoria(event.target.value)}>
                                                    <option value="">-SUCURSAL-</option>
                                                    {sucursales.map(e=>
                                                        <option key={e.id} value={e.id}>{e.codigo}</option>
                                                    )}
                                                </select>
                                                
                                                <button className={("btn btn-")+(showallSelectAuditoria==1?"success":"")} onClick={()=>setshowallSelectAuditoria(showallSelectAuditoria==1?"":1)}><i className="fa fa-eye"></i></button>
                                                <button className="btn btn-success" onClick={()=>getBancosData()}><i className="fa fa-search"></i></button>
                                            
                                            </div>

                                        </div>
                                        <div className="col-md-auto">
                                            <div className="btn-group">
                                                <button className="btn btn-sinapsis" onClick={()=>setshownewmovnoreportado(!shownewmovnoreportado)}><i className="fa fa-paper"></i> MOV. NO REPORTADO</button>
                                                <button className="btn btn-success" onClick={()=>setshowautoliquidar(!showautoliquidar)}><i className="fa fa-eye"></i> AutoLiquidar</button>
                                                <button className="btn btn-success" onClick={()=>setshowimportliquidacion(!showimportliquidacion)}>IMPORTAR BANCO</button>

                                            </div>
                                        </div>
                                    </div>
                                    {shownewmovnoreportado&&
                                        <div className="container">
                                            <form className="card p-3 m-3" onSubmit={event=>{event.preventDefault();saveNewmovnoreportado()}}>
                                                <div className="text-center">
                                                    <span className="h3">MOVIMIENTO </span> <span className=" h3 fw-bold">NO REPORTADO <i className="fa fa-exclamation-triangle"></i></span>
                                                </div>
                                                
                                                <div className="form-group">
                                                    <span className="form-label fw-bold fs-4">Referencia</span>
                                                    <input type="text" placeholder="REF" className="form-control form-control-lg" value={newmovnoreportadoref} onChange={e=>setnewmovnoreportadoref(e.target.value)} />
                                                </div>
                                                <div className="form-group">
                                                    <span className="form-label fw-bold fs-4 text-danger">Monto Bs </span>
                                                    <input type="text" className="form-control text-danger fs-2" value={newmovnoreportadomonto} onChange={e=>setnewmovnoreportadomonto(formatAmount(e.target.value,"Bs. "))} placeholder="Monto Bs" />
                                                </div>

                                                <div className="form-group">
                                                    <span className="form-label fw-bold fs-4">Banco</span>
                                                    <select className="form-control form-control-lg" 
                                                    value={newmovnoreportadobanco} 
                                                    onChange={e=>setnewmovnoreportadobanco(e.target.value)} required={true}>
                                                        <option value="">-</option>
                                                        {opcionesMetodosPago.filter(e=>e.codigo!="EFECTIVO").map(e=>
                                                            <option value={e.id} key={e.id}>{e.descripcion}</option>
                                                        )}
                                                    </select>
                                                </div>

                                                <div className="form-group">
                                                    <span className="form-label fw-bold fs-4">Fecha Liquidado</span>
                                                    <input type="date" className="form-control form-control-lg" value={newmovnoreportadofecha} onChange={e=>setnewmovnoreportadofecha(e.target.value)} />
                                                </div>

                                                <div className="text-center p-3">
                                                    <button className="btn btn-success">GUARDAR</button>
                                                </div>
                                            </form>
                                        </div>
                                    }
                                    {showautoliquidar?<div>
                                        <hr />

                                        <div className="input-group">
                                            <input type="date" value={fechaAutoLiquidarTransferencia} onChange={event=>setfechaAutoLiquidarTransferencia(event.target.value)} className="form-control" />
                                            <select className="form-control" 
                                            value={bancoAutoLiquidarTransferencia} 
                                            onChange={e=>setbancoAutoLiquidarTransferencia(e.target.value)}>
                                                <option value="">-Banco-</option>
                                                {opcionesMetodosPago.filter(e=>e.codigo!="EFECTIVO").map(e=>
                                                    <option value={e.codigo} key={e.id}>{e.descripcion}</option>
                                                )}
                                            </select>
                                            <button className="btn btn-success m-2" onClick={()=>autoliquidarTransferencia("auto")}>AUTOLIQUIDAR TRANSFERENCIAS</button>
                                            <button className="btn btn-sinapsis m-2" onClick={()=>autoliquidarTransferencia("reversar")}>REVERSAR LIQUIDACIÓN DE TRANSFERENCIAS</button>
                                        </div>

                                    </div>:null}

                                    {showimportliquidacion?<div>
                                    
                                        <textarea className="form-control" placeholder="[CODIGO BANCO] [FECHA yyyy-mm-dd] [REF] [MONTO]" value={textimportliquidadcion} onChange={event=>settextimportliquidadcion(event.target.value)}></textarea>
                                        <div className="text-center p-3">
                                            <button className="btn btn-success" onClick={()=>procesarImportTextliquidacion()}>PROCESAR <i className="fa fa-cogs"></i></button>
                                            <button className="btn btn-sinapsis" onClick={()=>setdataimportliquidacion([])}>LIMPIAR</button>
                                        </div>
                                    </div>:null}


                                </div> 
                                <hr />
                                <div className="row mt-2 mb-2">
                                    <div className="col">
                                        <table className="table">
                                            <thead>
                                                <tr>
                                                    <th colSpan={2} className="text-center">NOVEDADES SISTEMA <i className="fa fa-exclamation-triangle"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th className="text-right">TOTAL DE MOV SISTEMA</th>
                                                    <th className="text-right">{bancosdata.xliquidar.filter(e=>!e.ajuste).length}</th>
                                                </tr>
                                                <tr>
                                                    <th className="text-right">TOTAL MONTO MENOS REPORTE</th>
                                                    <th className="text-sinapsis fs-3 text-right">{moneda(sumaReporteSistema)}</th>
                                                </tr>
                                                {reportSistema.map((e,i)=>
                                                    <tr key={i}>
                                                        <td className="text-right">{e.msj} <b>*{e.banco}*</b> <b>#{e.ref}</b> <b>{moneda(e.monto)}</b></td>
                                                        <th className="text-right">ID {e.id}</th>
                                                    </tr>
                                                )}

                                            </tbody>
                                        </table>
                                    </div>
                                    <div className="col-4">
                                        <table className="table">
                                            <thead>
                                                <tr>
                                                    <th className=" text-primary">{moneda(sumaReporteBanco-sumaReporteSistema)}</th>
                                                    <th colSpan={1} className="text-center">NOVEDADES BANCO <i className="fa fa-exclamation-triangle"></i></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>{dataimportliquidacion.filter(e=>!e.ajuste).length}</th>
                                                    <th>TOTAL MOVS BANCO</th>
                                                </tr>
                                                <tr>
                                                    <th className="text-sinapsis fs-3">{moneda(sumaReporteBanco)}</th>
                                                    <th>TOTAL MONTO MENOS REPORTE</th>
                                                </tr>
                                                {reportBanco.map((e,i)=>
                                                    <tr key={i}>
                                                        <th>ID {e.id}</th>
                                                        <td>{e.msj} <b>*{e.banco}*</b> <b>#{e.ref}</b> <b>{moneda(e.monto)}</b></td>
                                                    </tr>
                                                )}

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div className="row">
                                    <div className="col">
                                        <table className="table">
                                            <thead>
                                                <tr className="h-70px">
                                                    <th onClick={()=>{if(orderColumnAuditoria=="banco"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("banco")}} className="pointer w-10">BANCO</th>
                                                    <th onClick={()=>{if(orderColumnAuditoria=="tipo"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("tipo")}} className="pointer w-10">MÉTODO</th>
                                                    <th onClick={()=>{if(orderColumnAuditoria=="debito_credito"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("debito_credito")}} className="pointer w-10">DÉB/CRÉD</th>
                                                    <th onClick={()=>{if(orderColumnAuditoria=="fecha"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha")}} className="pointer w-10">REPORTADO</th>
                                                    <th onClick={()=>{if(orderColumnAuditoria=="fecha_liquidacion"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha_liquidacion")}} className="pointer w-10">LIQUIDADO</th>
                                                    <th>TIPO</th>
                                                    <th onClick={()=>{if(orderColumnAuditoria=="id_sucursal"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("id_sucursal")}} className="pointer w-10">SUCURSAL</th>
                                                    <th onClick={()=>{if(orderColumnAuditoria=="loteserial"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("loteserial")}} className="pointer w-10">REF</th>
                                                    {/* <th onClick={()=>{if(orderColumnAuditoria=="categoria"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("categoria")}} className="pointer w-10">CATEGORÍA</th> */}
                                                    <th onClick={()=>{if(orderColumnAuditoria=="monto_liquidado"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto_liquidado")}} className="pointer w-10">
                                                        LIQUIDADO

                                                        <br />
                                                        <span className="text-success fs-4">
                                                            {bancosdata.xliquidar?
                                                                moneda(bancosdata.xliquidar.filter(e=>!e.ajuste).reduce((a,b)=>{return a+parseFloat(b.monto_liquidado)},0))
                                                            :null}
                                                        </span>
                                                    </th>
                                                    <th onClick={()=>{if(orderColumnAuditoria=="monto"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto")}} className="pointer text-right">
                                                        REPORTADO
                                                        <br />
                                                        <span className="text-sinapsis fs-4">
                                                            {bancosdata.xliquidar?
                                                                moneda(bancosdata.xliquidar.filter(e=>!e.ajuste).reduce((a,b)=>{return a+parseFloat(b.monto)},0))
                                                            :null}
                                                        </span>

                                                    </th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {bancosdata.xliquidar.map((e,i)=>
                                                <>
                                                
                                                    {e.ajuste?
                                                        <tr onDoubleClick={()=>delBloque(i,"reportado")} className="pointer">
                                                            <td colSpan={11} className=" text-center h-70px ">
                                                            </td>
                                                            <th>
                                                                {i}
                                                            </th>
                                                        </tr>
                                                    :
                                                    <tr key={e.id}className={(e.fecha_liquidacion?"bg-success-light":"")+" h-70px"} >
                                                        <th className="w-10">
                                                            <button onDoubleClick={()=>changeBank(e.id,"banco")} className="btn w-100 fw-bolder" 
                                                            style={{
                                                                backgroundColor:colors[e.banco]?colors[e.banco][0]:"", 
                                                                color:colors[e.banco]?colors[e.banco][1]:""
                                                            }}>{e.banco}</button>
                                                            
                                                        </th>
                                                        <th className="w-10">
                                                            <button className="btn w-100 fw-bolder" 
                                                            style={{backgroundColor:colors[e.tipo]}}>{e.tipo}</button> 
                                                        </th>
                                                        <th className="w-10" onDoubleClick={()=>changeBank(e.id,"debito_credito")} >
                                                            <span >{e.debito_credito}</span> 
                                                        </th>
                                                        <th className="w-10">
                                                            {e.fecha?e.fecha:""}
                                                            {e.categoria==66?
                                                                <>
                                                                    <br />
                                                                    <button className="btn btn-warning">*NO REPORTADO* <i className="fa fa-exclamation-triangle"></i></button>
                                                                </>
                                                            :null}
                                                        </th>
                                                        <th className="w-10">{e.fecha_liquidacion}</th>
                                                        <th className="w-10"><button className={("btn w-100 ")+(e.monto<0?"btn-danger":"btn-success")}>{e.monto<0?"EGRESO":"INGRESO"}</button></th>
                                                        <th className="w-10"> 
                                                            <button className="btn w-100 fw-bolder" style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>{e.sucursal.codigo}</button>
                                                        </th>
                                                        <th className="w-10">{e.loteserial}</th>
                                                            {/* <th className="w-10">
                                                                {e.categoria==66?
                                                                    <>
                                                                        <button className="btn btn-warning">*NO REPORTADO* <i className="fa fa-exclamation-triangle"></i></button>
                                                                        <br />
                                                                    </>
                                                                :null}
                                                                {getCat(e.categoria)}
                                                            </th> */}
                                                        <th className="w-10">
                                                            <span className="pointer text-success"
                                                                onClick={()=>{
                                                                    setselectTrLiquidar(selectTrLiquidar===i?"":i)
                                                                    if (e.tipo=="Transferencia") {
                                                                        setinpmontoLiquidar(e.monto)
                                                                        setinpfechaLiquidar(e.fecha)
                                                                    }
                                                                }}
                                                            >
                                                                {moneda(e.monto_liquidado)}
                                                            </span>
                                                            {selectTrLiquidar===i && e.fecha && e.categoria!=66?
                                                                <div className="input-group-vertical">
                                                                    <input type="text" className="form-control" value={inpmontoLiquidar} placeholder="Monto Reportado" onChange={event=>setinpmontoLiquidar(event.target.value)}/>
                                                                    <input type="date" className="form-control" value={inpfechaLiquidar} onChange={event=>setinpfechaLiquidar(event.target.value)}/>
                                                                    <button className="btn btn-warning w-100" onClick={()=>liquidarMov(e.id)}>LIQUIDAR <i className="fa fa-send"></i></button>
                                                                </div>
                                                            :null}
                                                        </th>
                                                        <th className="w-10 text-right">
                                                            <span 
                                                            onDoubleClick={()=>changeBank(e.id,"monto")} className="pointer text-sinapsis">{moneda(e.monto)}</span> 
                                                        </th>
                                                        <th className="w-10">


                                                            {selectTrLiquidar===i && !e.fecha && e.categoria==66?
                                                                <div className="input-group-vertical">
                                                                    <input type="text" className="form-control" value={inpmontoNoreportado} placeholder="Monto Reportado" onChange={event=>setinpmontoNoreportado(event.target.value)}/>
                                                                    <input type="date" className="form-control" value={inpfechaNoreportado} onChange={event=>setinpfechaNoreportado(event.target.value)}/>
                                                                    <button className="btn btn-danger w-100" onClick={()=>reportarMov(e.id)}>REPORTAR <i className="fa fa-send"></i></button>
                                                                </div>
                                                            :null}
                                                        </th>
                                                        <th className="">
                                                            <span className="pointer" onClick={()=>addBloque(i,"reportado")}>
                                                                {i}
                                                            </span>
                                                        </th>
                                                    </tr>}

                                                    
                                                </>  
                                                )}
                                            </tbody>
                                        </table>
                                    </div>
                                    {dataimportliquidacion.length?
                                        <div className="col-4">
                                            <table className="table">
                                                <thead>
                                                    <tr className="h-70px">
                                                        <th></th>
                                                        <th colSpan={4}>
                                                            IMPORTACIÓN BANCO

                                                            <br />
                                                            <span className="text-sinapsis fs-4">
                                                                {bancosdata.xliquidar?
                                                                    moneda(dataimportliquidacion.filter(e=>!e.ajuste).reduce((a,b)=>{return a+parseFloat(b.monto)},0))
                                                                :null}
                                                            </span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {dataimportliquidacion.sort((a,b) =>{ 
                                                        let field;

                                                        if (orderColumnAuditoria=="monto") {
                                                            field = "monto"
                                                            if (orderAuditoria=="asc") {
                                                                return parseFloat(a[field]) - parseFloat(b[field])
                                                            }else{
                                                                return parseFloat(b[field]) - parseFloat(a[field])
                                                            }
                                                        }else if (orderColumnAuditoria=="banco") {
                                                            field = "banco"
                                                            return 
                                                        }
                                                    } ).map((e,i)=>
                                                        <>
                                                            {e.ajuste?
                                                                <tr onDoubleClick={()=>delBloque(i,"banco")} className="pointer">
                                                                    <th>
                                                                        {i}
                                                                    </th>
                                                                    <td colSpan={11} className=" text-center h-70px ">
                                                                    </td>
                                                                </tr>
                                                            :
                                                                <tr key={i} className={"h-70px "}>
                                                                    <th className={colorPerfect(i)}>
                                                                        <span className="pointer" onClick={()=>addBloque(i,"banco")}>
                                                                            {i}
                                                                        </span>
                                                                    </th>
                                                                    <th className="text-sinapsis">{moneda(e.monto)}</th>
                                                                        {bancosdata.xliquidar?
                                                                            parseFloat(e.monto)&&bancosdata.xliquidar[i]?  
                                                                                <th className={((parseFloat(e.monto)-parseFloat(bancosdata.xliquidar[i].monto))<-3 || (parseFloat(e.monto)-parseFloat(bancosdata.xliquidar[i].monto))>3?"text-danger":"text-success")+""}>
                                                                                    {moneda(parseFloat(e.monto)-parseFloat(bancosdata.xliquidar[i].monto))}
                                                                                </th>
                                                                            : "---"
                                                                        :null}
                                                                    <td>
                                                                        <button onDoubleClick={()=>movermovbanco(i)} className="btn w-100 fw-bolder" 
                                                                        style={{
                                                                            backgroundColor:colors[e.codigo]?  movermovbancodesde==i? "#FFFF00" : movermovbancohasta==i ? "#ADFF2F":colors[e.codigo][0]   :"", 
                                                                            color:colors[e.codigo]?   colors[e.codigo][1]   :""
                                                                        }}>{e.codigo}</button>
                                                                    </td>
                                                                    <td>{e.fecha}</td>
                                                                    <td>
                                                                        {e.ref}

                                                                        {bancosdata.xliquidar?
                                                                            bancosdata.xliquidar[i]?
                                                                                e.ref.indexOf(bancosdata.xliquidar[i].loteserial)!=-1?  
                                                                                    <span className="text-success fw-bold ms-1">
                                                                                        {bancosdata.xliquidar[i].loteserial}
                                                                                    </span>
                                                                                : ""
                                                                            :null
                                                                        :null}
                                                                    </td>
                                                                </tr>
                                                            }
                                                        </>
                                                    )}
                                                </tbody>
                                            </table>
                                        </div>
                                    :null}
                                </div>
                            </>
                        :null
                    }

                    {
                        subviewAuditoria=="conciliacion" && bancosdata.view=="conciliacion"? 
                            <>
                                <div className="form-group">
                                    <div className="input-group">
                                        
                                        <select className="form-control" value={bancoSelectAuditoria}  onChange={event=>setbancoSelectAuditoria(event.target.value)}>
                                            <option value="">-BANCO-</option>
                                            {opcionesMetodosPago.map(e=>
                                                <option key={e.id} value={e.id}>{e.codigo}</option>
                                            )}
                                        </select>
                                        <select className="form-control" value={sucursalSelectAuditoria}  onChange={event=>setsucursalSelectAuditoria(event.target.value)}>
                                            <option value="">-SUCURSAL-</option>
                                            {sucursales.map(e=>
                                                <option key={e.id} value={e.id}>{e.codigo}</option>
                                            )}
                                        </select>
                                        <input type="date" className="form-control" value={fechaSelectAuditoria} onChange={event=>setfechaSelectAuditoria(event.target.value)}/>    
                                        <input type="date" className="form-control" value={fechaHastaSelectAuditoria} onChange={event=>setfechaHastaSelectAuditoria(event.target.value)}/>    
                                        <button className="btn btn-success" onClick={()=>getBancosData()}><i className="fa fa-search"></i></button>
                                    
                                    </div>
                                </div> 
                                <table className="table">
                                    <thead>
                                        <tr>
                                            <th>BANCO</th>
                                            <th>FECHA</th>
                                            <th className="bg-success-light">CUADRE REAL</th>
                                            <th className="">YA REPORTADO</th>
                                            <th>SALDO INCIAL</th>
                                            <th>INGRESO</th>
                                            <th>NO REPORTADO <i className="fa fa-exclamation-triangle"></i></th>
                                            <th>EGRESO</th>
                                            
                                            <th className="bg-success-light">CUADRE DIGITAL</th>
                                            <th className="text-right">CONCILIACIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {bancosdata.xfechaCuadre.map((e,i)=>
                                            <tr key={i} onClick={()=>selectConciliacion(e.banco,e.fecha)}>
                                                <th
                                                    style={{
                                                        backgroundColor:e.background, 
                                                        color:e.color
                                                    }}
                                                    onClick={()=>selectxMovimientos("banco", e.banco)}
                                                >
                                                    {e.banco_codigo}
                                                
                                                </th>
                                                <th>{e.fecha}</th>
                                                <th className="bg-success-light">
                                                    {selectConciliacionData == e.banco+"-"+e.fecha?
                                                        <div className="input-group">
                                                            
                                                            <input type="text" placeholder="Saldo ACTUAL" size={5} className="form-control" value={saldoactualbancofecha} onChange={event=>setsaldoactualbancofecha(event.target.value)} />
                                                            <button className="btn btn-warning" onClick={()=>sendsaldoactualbancofecha(e.id_banco,e.fecha)}><i className="fa fa-send"></i></button>
                                                        </div>
                                                    :e.guardado?moneda(e.guardado.saldo_real_manual):"----"
                                                    }
                                                </th>
                                                <th className="">{moneda(e.sireportadasum)}</th>
                                                <td className="bg-warning-light">
                                                    <b>{moneda(e.inicial)}</b>
                                                    <br />
                                                    {e.fecha_inicial}
                                                </td>
                                                <th className="bg-success-light">{moneda(e.ingreso)}</th>
                                                <th className="">{moneda(e.noreportadasum)}</th>
                                                <th className="bg-danger-light">{moneda(e.egreso)}</th>
                                                
                                                <th className="bg-success-light">{moneda(e.balance)}</th>
                                                <th className={(e.cuadre>-200 && e.cuadre<200?"bg-success text-light":"bg-danger text-light")+" fs-3 text-right"}>{moneda(e.cuadre)}</th>

                                            </tr>
                                        )}
                                    </tbody>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td colSpan={8} className={(bancosdata.sum>-200 && bancosdata.sum<200?"bg-success text-light":"bg-danger text-light")+" fs-1 text-right"}>{moneda(bancosdata.sum)}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </>
                        :null
                    }
                    {
                        bancosdata.movsnoreportadosTotal?
                            bancosdata.movsnoreportadosTotal.length?
                                <div className="card m-5 p-5">
                                    <h1 className="text-center">NO REPORTADOS <i className="fa fa-2x fa-exclamation-triangle"></i></h1>
                                    <table className="table">
                                        <thead>
                                            <tr>
                                                <th onClick={()=>{if(orderColumnAuditoria=="banco"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("banco")}} className="pointer">BANCO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="tipo"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("tipo")}} className="pointer">MÉTODO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="debito_credito"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("debito_credito")}} className="pointer">DÉBITO/CRÉDITO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="fecha"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha")}} className="pointer">FECHA REPORTADO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="fecha_liquidacion"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("fecha_liquidacion")}} className="pointer">FECHA LIQUIDADO</th>
                                                <th>TIPO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="id_sucursal"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("id_sucursal")}} className="pointer">SUCURSAL</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="loteserial"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("loteserial")}} className="pointer">LOTE / REFERENCIA</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="categoria"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("categoria")}} className="pointer">CATEGORÍA</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="monto"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto")}} className="pointer">MONTO BRUTO</th>
                                                <th onClick={()=>{if(orderColumnAuditoria=="monto_liquidado"){setorderAuditoria(orderAuditoria==="desc"?"asc":"desc")};setorderColumnAuditoria("monto_liquidado")}} className="pointer">LIQUIDADO</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {bancosdata.movsnoreportadosTotal.map((e,i)=>
                                                <tr key={e.id}>
                                                    <th>
                                                        <button onDoubleClick={()=>changeBank(e.id,"banco")} className="btn w-100 fw-bolder" 
                                                        style={{
                                                            backgroundColor:colors[e.banco]?colors[e.banco][0]:"", 
                                                            color:colors[e.banco]?colors[e.banco][1]:""
                                                        }}>{e.banco}</button>
                                                        
                                                    </th>
                                                    <th>
                                                        <button className="btn w-100 fw-bolder" 
                                                        style={{backgroundColor:colors[e.tipo]}}>{e.tipo}</button> 
                                                    </th>
                                                    <th className="pointer" >
                                                        <span >{e.debito_credito}</span> 
                                                    </th>
                                                    <th>
                                                        {e.fecha?
                                                            e.fecha
                                                            :
                                                            <button className="btn btn-warning">*NO REPORTADO* <i className="fa fa-exclamation-triangle"></i></button>
                                                        }
                                                    </th>
                                                    <th>{e.fecha_liquidacion}</th>
                                                    <th><button className={("btn w-100 ")+(e.monto<0?"btn-danger":"btn-success")}>{e.monto<0?"EGRESO":"INGRESO"}</button></th>
                                                    <th> 
                                                        <button className="btn w-100 fw-bolder" style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>{e.sucursal.codigo}</button>
                                                    </th>
                                                    <th>{e.loteserial}</th>
                                                    <th>
                                                        {e.categoria==66?
                                                            <>
                                                                <button className="btn btn-warning">*NO REPORTADO* <i className="fa fa-exclamation-triangle"></i></button>
                                                                <br />
                                                            </>
                                                        :null}
                                                    </th>
                                                    <th>
                                                        <span onDoubleClick={()=>changeBank(e.id,"monto")} className="pointer">{moneda(e.monto)}</span> 
                                                    </th>
                                                    <th>{moneda(e.monto_liquidado)}</th>
                                                    <th>
                                                        
                                                    </th>
                                                </tr>
                                            )}
                                        </tbody>
                                        <tbody>
                                            <tr>
                                                <th colSpan={13} className="fs-2 bg-danger text-light text-right">
                                                    {moneda(bancosdata.movsnoreportadosTotalsum)}
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            :null
                        :null
                    }

            </div>
        :null}

        {permiso([1,2,3]) && subviewAuditoriaGeneral=="efectivo"?
            <AuditoriaEfectivo
                getAuditoriaEfec={getAuditoriaEfec}
                qauditoriaefectivo={qauditoriaefectivo}
                setqauditoriaefectivo={setqauditoriaefectivo}
                sucursalqauditoriaefectivo={sucursalqauditoriaefectivo}
                setsucursalqauditoriaefectivo={setsucursalqauditoriaefectivo}
                sucursales={sucursales}
                fechadesdeauditoriaefec={fechadesdeauditoriaefec}
                setfechadesdeauditoriaefec={setfechadesdeauditoriaefec}
                fechahastaauditoriaefec={fechahastaauditoriaefec}
                setfechahastaauditoriaefec={setfechahastaauditoriaefec}
                setqcajaauditoriaefectivo={setqcajaauditoriaefectivo}
                qcajaauditoriaefectivo={qcajaauditoriaefectivo}
                dataAuditoriaEfectivo={dataAuditoriaEfectivo}
                colorsGastosCat={colorsGastosCat}
                moneda={moneda}
            />
        :null}

        {permiso([1,2,3]) && subviewAuditoriaGeneral=="cuadregeneral"?

            <CuadreGeneral
                sucursalqcuadregeneral={sucursalqcuadregeneral}
                setsucursalqcuadregeneral={setsucursalqcuadregeneral}
                fechadesdeqcuadregeneral={fechadesdeqcuadregeneral}
                setfechadesdeqcuadregeneral={setfechadesdeqcuadregeneral}
                fechahastaqcuadregeneral={fechahastaqcuadregeneral}
                setfechahastaqcuadregeneral={setfechahastaqcuadregeneral}
                datacuadregeneral={datacuadregeneral}
                getCuadreGeneral={getCuadreGeneral}
                moneda={moneda}
                number={number}
                formatAmount={formatAmount}
                sucursales={sucursales}
                colorSucursal={colorSucursal}
                colorsGastosCat={colorsGastosCat}
                colors={colors}
            />   
        
        :null}
        {permiso([1,2,3,6]) && subviewAuditoriaGeneral=="aprobtransferencia"?
            <Aprobtransferencia
                setsucursalSelect={setsucursalSelect}
                sucursalSelect={sucursalSelect}
                sucursales={sucursales}
                qestatusaprobaciocaja={qestatusaprobaciocaja}
                setqestatusaprobaciocaja={setqestatusaprobaciocaja}
                sucursalDetallesData={sucursalDetallesData}
                colorSucursal={colorSucursal}
                colors={colors}
                moneda={moneda}
                getBancoName={getBancoName}
                aprobarTransferenciaFun={aprobarTransferenciaFun}
                getsucursalDetallesData={getsucursalDetallesData}
                subviewpanelsucursales={subviewpanelsucursales}
                fechasMain1={fechasMain1}
                fechasMain2={fechasMain2}
                setfechasMain1={setfechasMain1}
                setfechasMain2={setfechasMain2}
                qfiltroaprotransf={qfiltroaprotransf}
                setqfiltroaprotransf={setqfiltroaprotransf}
                bancoqfiltroaprotransf={bancoqfiltroaprotransf}
                setbancoqfiltroaprotransf={setbancoqfiltroaprotransf}
                opcionesMetodosPago={opcionesMetodosPago}
            />
        :null}
       </>

    )
}