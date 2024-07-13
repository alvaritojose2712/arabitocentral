import { useEffect, useState } from "react";
import CajaMatriz from './cajamatriz'
import ControlEfectivoMatriz from './controlefectivomatriz'

export default function EfectivoDisponibleSucursales({
    efectivoDisponibleSucursalesData,
    setefectivoDisponibleSucursalesData,
    getDisponibleEfectivoSucursal,
    moneda,
    colorSucursal,


    datacajamatriz,
    colorsGastosCat,
    depositarmatrizalbanco,
    getCajaMatriz,
    qcajamatriz,
    setqcajamatriz,
    sucursalqcajamatriz,
    setsucursalqcajamatriz,
    fechadesdecajamatriz,
    setfechadesdecajamatriz,
    fechahastacajamatriz,
    setfechahastacajamatriz,
    sucursales,

    bancodepositobanco,
    setbancodepositobanco,
    fechadepositobanco,
    setfechadepositobanco,
    selectdepositobanco,
    setselectdepositobanco,
    opcionesMetodosPago,

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
    controlefecNewCategoria,    
    setcontrolefecNewCategoria,
    controlefecNewMonto,    
    setcontrolefecNewMonto,
    getControlEfec,    
    setControlEfec,    
    setcontrolefecQCategoria, 
    controlefecQCategoria,
    number,
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
    
}){
    const [subviewefectivoadministracion, setsubviewefectivoadministracion] = useState("todos")
    useEffect(()=>{
        getDisponibleEfectivoSucursal()
    },[])
    return (
        <div className="container-fluid">
            <div className="d-flex justify-content-center">
                <div className="btn-group m-2">
                    <button className={("btn btn-sm ")+(subviewefectivoadministracion=="movdesucursalamatriz"?"btn-sinapsis":"")} onClick={()=>{setsubviewefectivoadministracion("movdesucursalamatriz")}}>Transferencias a Matriz</button>
                    <button className={("btn btn-sm ")+(subviewefectivoadministracion=="cajasucursales"?"btn-sinapsis":"")} onClick={()=>{setsubviewefectivoadministracion("cajasucursales")}}>Caja Sucursales</button>
                    <button className={("btn btn-sm ")+(subviewefectivoadministracion=="controlefectivomatriz"?"btn-sinapsis":"")} onClick={()=>{setsubviewefectivoadministracion("controlefectivomatriz")}}>Caja Matriz</button>
                </div>
            </div>
            {
             subviewefectivoadministracion=="cajasucursales" ?
                <table className="table table-striped">
                    <thead>
                        <tr>
                            <th>SUCURSAL</th>
                            <th>CREADO</th>
                            <th>CARGADO</th>
                            <th>CONCEPTO</th>
                            <th className="text-right">Monto DOLAR</th>
                            <th className="">Balance DOLAR</th>
                            <th className="text-right">Monto BS</th>
                            <th className="">Balance BS</th>
                            <th className="text-right">Monto PESO</th>
                            <th className="">Balance PESO</th>

                            <th className="text-right">Monto EURO</th>
                            <th className="">Balance EURO</th>
                            <th>
                                <button className="btn btn-success" type="button" onClick={()=>getDisponibleEfectivoSucursal()}><i className="fa fa-search"></i></button>
                            </th>
                        </tr>
                        {
                            efectivoDisponibleSucursalesData.dolarbalance?
                                <tr>
                                    <td colSpan={5}></td>
                                    <td className="bg-info fs-5">{moneda(efectivoDisponibleSucursalesData.dolarbalance)}</td>
                                    <td></td>
                                    <td className="bg-info fs-5">{moneda(efectivoDisponibleSucursalesData.bsbalance)}</td>
                                    <td></td>
                                    <td className="bg-info fs-5">{moneda(efectivoDisponibleSucursalesData.pesobalance)}</td>
                                    <td></td>
                                    <td className="bg-info fs-5">{moneda(efectivoDisponibleSucursalesData.eurobalance)}</td>
                                </tr>
                            :null
                        }
                    </thead>
                    <tbody>
                        {efectivoDisponibleSucursalesData.data? 
                            efectivoDisponibleSucursalesData.data.map(e=>
                                <tr key={e.id}>
                                    <td>
                                        <button className={"btn w-100 fw-bolder "} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>{e.sucursal.nombre}</button>
                                    </td>
                                    <td>{e.fecha}</td>
                                    <td>{e.created_at}</td>
                                    <td>{e.concepto}</td>

                                    <td className={(e.montodolar<0? "text-danger": "text-dark")+(" text-right")}>{moneda(e.montodolar)}</td>
                                    <td className={("bg-warning-light text-success fs-5")}>{moneda(e.dolarbalance)}</td>
                                    
                                    <td className={(e.montobs<0? "text-danger": "text-dark")+(" text-right")}>{moneda(e.montobs)}</td>
                                    <td className={("bg-warning-light text-success fs-5")}>{moneda(e.bsbalance)}</td>
                                    
                                    <td className={(e.montopeso<0? "text-danger": "text-dark")+(" text-right")}>{moneda(e.montopeso)}</td>
                                    <td className={("bg-warning-light text-success fs-5")}>{moneda(e.pesobalance)}</td>

                                    <td className={(e.montoeuro<0? "text-danger": "text-dark")+(" text-right")}>{moneda(e.montoeuro)}</td>
                                    <td className={("bg-warning-light text-success fs-5")}>{moneda(e.eurobalance)}</td>
                                </tr>
                            ):null
                        }
                    </tbody>
                </table>
             :null 
            }

            {
                subviewefectivoadministracion=="movdesucursalamatriz" ?
                <CajaMatriz
                    sucursales={sucursales}
                    datacajamatriz={datacajamatriz}
                    colorsGastosCat={colorsGastosCat}
                    moneda={moneda}
                    depositarmatrizalbanco={depositarmatrizalbanco}
                    getCajaMatriz={getCajaMatriz}
                    qcajamatriz={qcajamatriz}
                    setqcajamatriz={setqcajamatriz}
                    sucursalqcajamatriz={sucursalqcajamatriz}
                    setsucursalqcajamatriz={setsucursalqcajamatriz}
                    fechadesdecajamatriz={fechadesdecajamatriz}
                    setfechadesdecajamatriz={setfechadesdecajamatriz}
                    fechahastacajamatriz={fechahastacajamatriz}
                    setfechahastacajamatriz={setfechahastacajamatriz}

                    bancodepositobanco={bancodepositobanco}
                    setbancodepositobanco={setbancodepositobanco}
                    fechadepositobanco={fechadepositobanco}
                    setfechadepositobanco={setfechadepositobanco}
                    selectdepositobanco={selectdepositobanco}
                    setselectdepositobanco={setselectdepositobanco}
                    opcionesMetodosPago={opcionesMetodosPago}
                />
                :null 
            }

            {
                subviewefectivoadministracion=="controlefectivomatriz" ?
                <ControlEfectivoMatriz
                    controlefecQ={controlefecQ}    
                    setcontrolefecQ={setcontrolefecQ}
                    controlefecQDesde={controlefecQDesde}    
                    setcontrolefecQDesde={setcontrolefecQDesde}
                    controlefecQHasta={controlefecQHasta}    
                    setcontrolefecQHasta={setcontrolefecQHasta}
                    controlefecData={controlefecData}    
                    controlefecSelectGeneral={controlefecSelectGeneral}    
                    setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
                    controlefecNewConcepto={controlefecNewConcepto}    
                    setcontrolefecNewConcepto={setcontrolefecNewConcepto}
                    controlefecNewCategoria={controlefecNewCategoria}    
                    setcontrolefecNewCategoria={setcontrolefecNewCategoria}
                    controlefecNewMonto={controlefecNewMonto}    
                    setcontrolefecNewMonto={setcontrolefecNewMonto}
                    getControlEfec={getControlEfec}    
                    setControlEfec={setControlEfec}    
                    setcontrolefecQCategoria={setcontrolefecQCategoria} 
                    controlefecQCategoria={controlefecQCategoria}
                    number={number}
                    controlefecNewMontoMoneda={controlefecNewMontoMoneda}
                    setcontrolefecNewMontoMoneda={setcontrolefecNewMontoMoneda}
                    categoriasCajas={categoriasCajas}
                    getcatsCajas={getcatsCajas}
                    delCaja={delCaja}
                    personalNomina={personalNomina}
                    getNomina={getNomina}
                    setopenModalNuevoEfectivo={setopenModalNuevoEfectivo}
                    openModalNuevoEfectivo={openModalNuevoEfectivo}
                    verificarMovPenControlEfec={verificarMovPenControlEfec}
                    verificarMovPenControlEfecTRANFTRABAJADOR={verificarMovPenControlEfecTRANFTRABAJADOR}
                    allProveedoresCentral={allProveedoresCentral}
                    getAllProveedores={getAllProveedores}
                    getAlquileres={getAlquileres}
                    alquileresData={alquileresData}
                    sucursalesCentral={sucursalesCentral}
                    transferirpedidoa={transferirpedidoa}
                    settransferirpedidoa={settransferirpedidoa}
                    getSucursales={getSucursales}
                    reversarMovPendientes={reversarMovPendientes}
                    aprobarRecepcionCaja={aprobarRecepcionCaja}
                    dolar={dolar}
                    peso={peso}
                    moneda={moneda}
                />
                :null 
            }





        </div>
    )
}