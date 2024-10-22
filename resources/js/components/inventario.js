
import { useState, useEffect } from "react";
import Inventariogeneral from "./inventariogeneral";
import Editarinventario from "./editarinventario";
import GestionarnombresInventario from "./gestionarnombresinventario";
import Aprobapedidosanulacion from "./aprobapedidosanulacion";
import Garantias from "./panel/garantias";
import TareasSucursalesPendientes from "./tareassucursalespendientes";



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
    buscarInventario,
    qBuscarInventario,
    setQBuscarInventario,
    qBuscarInventarioSucursal,
    setqBuscarInventarioSucursal,
    productosInventario,
    type,

    setinvsuc_q,
    invsuc_q,
    invsuc_num,
    setinvsuc_num,

    invsuc_orderBy,
    setinvsuc_orderBy,

    setinvsuc_orderColumn,

    inventariogeneralData,
    getInventarioGeneral,

    changeInventarioModificarDici,
    guardarmodificarInventarioDici,

    selectIdVinculacion, 
    setselectIdVinculacion,
    qvinculacion1, 
    setqvinculacion1,
    qvinculacion2, 
    setqvinculacion2,
    qvinculacion3, 
    setqvinculacion3,
    qvinculacion4, 
    setqvinculacion4,
    qvinculacionmarca, 
    setqvinculacionmarca,
    datavinculacion1, 
    setdatavinculacion1,
    datavinculacion2, 
    setdatavinculacion2,
    datavinculacion3, 
    setdatavinculacion3,
    datavinculacion4, 
    setdatavinculacion4,
    datavinculacionmarca, 
    setdatavinculacionmarca,
    inputselectvinculacion1, 
    setinputselectvinculacion1,
    inputselectvinculacion2, 
    setinputselectvinculacion2,
    inputselectvinculacion3, 
    setinputselectvinculacion3,
    inputselectvinculacion4, 
    setinputselectvinculacion4,
    inputselectvinculacionmarca, 
    setinputselectvinculacionmarca,
    inputselectvinculacion1General, 
    setinputselectvinculacion1General,
    inputselectvinculacion2General, 
    setinputselectvinculacion2General,
    inputselectvinculacion3General, 
    setinputselectvinculacion3General,
    inputselectvinculacion4General, 
    setinputselectvinculacion4General,
    inputselectvinculacionmarcaGeneral, 
    setinputselectvinculacionmarcaGeneral,

    inputselectvinculacion5,
    setinputselectvinculacion5,
    inputselectvinculacioncat,
    setinputselectvinculacioncat,
    inputselectvinculacioncatesp,
    setinputselectvinculacioncatesp,
    inputselectvinculacionproveedor,
    setinputselectvinculacionproveedor,
    inputselectvinculacionmaxct,
    setinputselectvinculacionmaxct,
    inputselectvinculacionminct,
    setinputselectvinculacionminct,
    inputselectvinculacion5General,
    setinputselectvinculacion5General,
    inputselectvinculacioncatGeneral,
    setinputselectvinculacioncatGeneral,
    inputselectvinculacioncatespGeneral,
    setinputselectvinculacioncatespGeneral,
    inputselectvinculacionproveedorGeneral,
    setinputselectvinculacionproveedorGeneral,
    inputselectvinculacionmaxctGeneral,
    setinputselectvinculacionmaxctGeneral,
    inputselectvinculacionminctGeneral,
    setinputselectvinculacionminctGeneral,


    qvinculacion5,
    setqvinculacion5,
    qvinculaciocat,
    setqvinculaciocat,
    qvinculaciocatesp,
    setqvinculaciocatesp,
    qvinculacioproveedor,
    setqvinculacioproveedor,
    qvinculaciomaxct,
    setqvinculaciomaxct,
    qvinculaciominct,
    setqvinculaciominct,
    qvinculacion5General,
    setqvinculacion5General,
    qvinculaciocatGeneral,
    setqvinculaciocatGeneral,
    qvinculaciocatespGeneral,
    setqvinculaciocatespGeneral,
    qvinculacioproveedorGeneral,
    setqvinculacioproveedorGeneral,
    qvinculaciomaxctGeneral,
    setqvinculaciomaxctGeneral,
    qvinculaciominctGeneral,
    setqvinculaciominctGeneral,
    datavinculacion5,
    setdatavinculacion5,
    datavinculaciocat,
    setdatavinculaciocat,
    datavinculaciocatesp,
    setdatavinculaciocatesp,
    datavinculacioproveedor,
    setdatavinculacioproveedor,
    datavinculaciomaxct,
    setdatavinculaciomaxct,
    datavinculaciominct,
    setdatavinculaciominct,
    newNombre5,
    setnewNombre5,
    newNombrecat,
    setnewNombrecat,
    newNombrecatesp,
    setnewNombrecatesp,
    newNombreproveedor,
    setnewNombreproveedor,
    newNombremaxct,
    setnewNombremaxct,
    newNombreminct,
    setnewNombreminct,

    getDatinputSelectVinculacion,
    saveCuatroNombres,

    qvinculacion1General,
    qvinculacion2General,
    qvinculacion3General,
    qvinculacion4General,
    qvinculacionmarcaGeneral,

    inventarioGeneralqsucursal,
    setinventarioGeneralqsucursal,

    colorSucursal,


    selectcampobusquedaestadistica,
    setselectcampobusquedaestadistica,
    dataCamposBusquedaEstadisticas,
    selectvalorcampobusquedaestadistica,
    setselectvalorcampobusquedaestadistica,
    agregarCampoBusquedaEstadisticas,
    selectsucursalbusquedaestadistica,
    setselectsucursalbusquedaestadistica,
    agregarSucursalBusquedaEstadisticas,
    
    camposAgregadosBusquedaEstadisticas,
    sucursalesAgregadasBusquedaEstadisticas,
    setcamposAgregadosBusquedaEstadisticas,
    setsucursalesAgregadasBusquedaEstadisticas,
    sameCatValue,


    buscarNombres,
    qnombres,
    setqnombres,
    qtiponombres,
    setqtiponombres,
    datanombres,
    modNombres,

    InvorderColumn,
    InvorderBy,
    setInvorderColumn,
    setInvorderBy,
    setInvnum,
    Invnum,
    newNombres,

    dataPedidoAnulacionAprobacion,
    qdesdePedidoAnulacionAprobacion,
    qhastaPedidoAnulacionAprobacion,
    qnumPedidoAnulacionAprobacion,
    qestatusPedidoAnulacionAprobacion,
    getAprobacionPedidoAnulacion,
    setAprobacionPedidoAnulacion,
    setqdesdePedidoAnulacionAprobacion,
    setqhastaPedidoAnulacionAprobacion,
    setqnumPedidoAnulacionAprobacion,
    setqestatusPedidoAnulacionAprobacion,

    sucursalPedidoAnulacionAprobacion,
    setsucursalPedidoAnulacionAprobacion,
    moneda,
    garantiasData,
    garantiaq,
    setgarantiaq,
    garantiaqsucursal,
    setgarantiaqsucursal,
    getGarantias,

    setqTareaPendienteFecha,
    qTareaPendienteFecha,
    qTareaPendienteSucursal,
    setqTareaPendienteSucursal,
    getTareasPendientes,
    tareasPendientesData,
    qTareaPendienteEstado,
    setqTareaPendienteEstado,
    qTareaPendienteNum,
    setqTareaPendienteNum,
    number,

    listselectEliminarDuplicados,
    selectEliminarDuplicados,
    sendTareaRemoverDuplicado,
    openVincularSucursalwithCentral,

    inputbuscarcentralforvincular,
    modalmovilx,
    modalmovily,
    setmodalmovilshow,
    modalmovilshow,
    modalmovilRef,
    linkproductocentralsucursal,

    id_sucursal_select_internoModal,
    setid_sucursal_select_internoModal,
    productosInventarioModal,
    setproductosInventarioModal,
    InvnumModal,
    setInvnumModal,
    qBuscarInventarioModal,
    setqBuscarInventarioModal,
    InvorderColumnModal,
    setInvorderColumnModal,
    InvorderByModal,
    setInvorderByModal,
    buscarInventarioModal,

    inventariogeneralSelectProEsta,
    setinventariogeneralSelectProEsta,
    inventariogeneralProEsta,
    setinventariogeneralProEsta,
    getEstadiscaSelectProducto,
    idselectproductoinsucursalforvicular,
    delVinculoSucursal,

    idselectproductoinsucursalforvicularMaestro,
    setidselectproductoinsucursalforvicularMaestro,
    linkproductocentralmaestro,
    openVincularSucursalwithMaestro,
    aprobarPermisoModDici,
    delTareaPendiente
}){
    useEffect(()=>{
        getDatinputSelectVinculacion()
    },[])

    useEffect(()=>{
        buscarInventario()
    },[InvorderColumn,InvorderBy,Invnum])
    const [subviewdici, setsubviewdici] = useState("novedades")

    return(
    <div className="container-fluid">
        <div className="text-center">
            <div className="btn-group mb-2">
                <button className={("fs-5 btn btn")+(subviewdici=="pedidos"?"":"-outline")+("-primary")} onClick={()=>setsubviewdici("pedidos")}> PEDIDOS</button>
                <button className={("fs-5 btn btn")+(subviewdici=="garantias"?"":"-outline")+("-primary")} onClick={()=>setsubviewdici("garantias")}> GARANTÍAS</button>
            {/*     <button className={("fs-5 btn btn")+(subviewdici=="novedades"?"":"-outline")+("-sinapsis")} onClick={()=>setsubviewdici("novedades")}> NOVEDADES</button> */}
                <button className={("fs-5 btn btn")+(subviewdici=="inventariogeneral"?"":"-outline")+("-sinapsis")} onClick={()=>setsubviewdici("inventariogeneral")}> VINCULACIONES</button>
                <button className={("fs-5 btn btn")+(subviewdici=="editarinventario"?"":"-outline")+("-secondary")} onClick={()=>setsubviewdici("editarinventario")}> EDITAR INVENTARIO</button>
                <button className={("fs-5 btn btn")+(subviewdici=="gestionarnombres"?"":"-outline")+("-secondary")} onClick={()=>setsubviewdici("gestionarnombres")}> EDITAR NOMBRES</button>
                <button className={("fs-5 btn btn")+(subviewdici=="tareaspendientes"?"":"-outline")+("-secondary")} onClick={()=>setsubviewdici("tareaspendientes")}> TAREAS PENDIENTES</button>
            </div>
        </div>

        {subviewdici=="pedidos"?
            <Aprobapedidosanulacion 
                dataPedidoAnulacionAprobacion={dataPedidoAnulacionAprobacion}
                qdesdePedidoAnulacionAprobacion={qdesdePedidoAnulacionAprobacion}
                qhastaPedidoAnulacionAprobacion={qhastaPedidoAnulacionAprobacion}
                qnumPedidoAnulacionAprobacion={qnumPedidoAnulacionAprobacion}
                qestatusPedidoAnulacionAprobacion={qestatusPedidoAnulacionAprobacion}
                getAprobacionPedidoAnulacion={getAprobacionPedidoAnulacion}
                setAprobacionPedidoAnulacion={setAprobacionPedidoAnulacion}

                setqdesdePedidoAnulacionAprobacion={setqdesdePedidoAnulacionAprobacion}
                setqhastaPedidoAnulacionAprobacion={setqhastaPedidoAnulacionAprobacion}
                setqnumPedidoAnulacionAprobacion={setqnumPedidoAnulacionAprobacion}
                setqestatusPedidoAnulacionAprobacion={setqestatusPedidoAnulacionAprobacion}
                sucursalPedidoAnulacionAprobacion={sucursalPedidoAnulacionAprobacion}
                setsucursalPedidoAnulacionAprobacion={setsucursalPedidoAnulacionAprobacion}
                moneda={moneda}
                sucursales={sucursales}
            />
        :null}

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
            <Inventariogeneral
                idselectproductoinsucursalforvicularMaestro={idselectproductoinsucursalforvicularMaestro}
                setidselectproductoinsucursalforvicularMaestro={setidselectproductoinsucursalforvicularMaestro}
                linkproductocentralmaestro={linkproductocentralmaestro}
                openVincularSucursalwithMaestro={openVincularSucursalwithMaestro}

                buscarInventarioModal={buscarInventarioModal}
                productosInventarioModal={productosInventarioModal}
                setproductosInventarioModal={setproductosInventarioModal}
                InvnumModal={InvnumModal}
                setInvnumModal={setInvnumModal}
                qBuscarInventarioModal={qBuscarInventarioModal}
                setqBuscarInventarioModal={setqBuscarInventarioModal}
                InvorderColumnModal={InvorderColumnModal}
                setInvorderColumnModal={setInvorderColumnModal}
                InvorderByModal={InvorderByModal}
                setInvorderByModal={setInvorderByModal}
                inputbuscarcentralforvincular={inputbuscarcentralforvincular}
                modalmovilx={modalmovilx}
                modalmovily={modalmovily}
                setmodalmovilshow={setmodalmovilshow}
                modalmovilshow={modalmovilshow}
                modalmovilRef={modalmovilRef}
                id_sucursal_select_internoModal={id_sucursal_select_internoModal}
                setid_sucursal_select_internoModal={setid_sucursal_select_internoModal}

                delVinculoSucursal={delVinculoSucursal}
                selectcampobusquedaestadistica={selectcampobusquedaestadistica}
                setselectcampobusquedaestadistica={setselectcampobusquedaestadistica}
                dataCamposBusquedaEstadisticas={dataCamposBusquedaEstadisticas}
                selectvalorcampobusquedaestadistica={selectvalorcampobusquedaestadistica}
                setselectvalorcampobusquedaestadistica={setselectvalorcampobusquedaestadistica}
                agregarCampoBusquedaEstadisticas={agregarCampoBusquedaEstadisticas}
                selectsucursalbusquedaestadistica={selectsucursalbusquedaestadistica}
                setselectsucursalbusquedaestadistica={setselectsucursalbusquedaestadistica}
                agregarSucursalBusquedaEstadisticas={agregarSucursalBusquedaEstadisticas}
                camposAgregadosBusquedaEstadisticas={camposAgregadosBusquedaEstadisticas}
                sucursalesAgregadasBusquedaEstadisticas={sucursalesAgregadasBusquedaEstadisticas}
                setcamposAgregadosBusquedaEstadisticas={setcamposAgregadosBusquedaEstadisticas}
                setsucursalesAgregadasBusquedaEstadisticas={setsucursalesAgregadasBusquedaEstadisticas}

                colorSucursal={colorSucursal}
                setinvsuc_q={setinvsuc_q}
                invsuc_q={invsuc_q}
                invsuc_num={invsuc_num}
                setinvsuc_num={setinvsuc_num}
                invsuc_orderBy={invsuc_orderBy}
                setinvsuc_orderBy={setinvsuc_orderBy}
                setinvsuc_orderColumn={setinvsuc_orderColumn}

                inventarioGeneralqsucursal={inventarioGeneralqsucursal}
                setinventarioGeneralqsucursal={setinventarioGeneralqsucursal}

                inventariogeneralData={inventariogeneralData}
                getInventarioGeneral={getInventarioGeneral}
                sucursales={sucursales}

                inventariogeneralSelectProEsta={inventariogeneralSelectProEsta}
                setinventariogeneralSelectProEsta={setinventariogeneralSelectProEsta}
                inventariogeneralProEsta={inventariogeneralProEsta}
                setinventariogeneralProEsta={setinventariogeneralProEsta}
                getEstadiscaSelectProducto={getEstadiscaSelectProducto}
            />
        :null}

        {subviewdici=="editarinventario"?
            <Editarinventario
                inputbuscarcentralforvincular={inputbuscarcentralforvincular}
                modalmovilx={modalmovilx}
                modalmovily={modalmovily}
                setmodalmovilshow={setmodalmovilshow}
                modalmovilshow={modalmovilshow}
                modalmovilRef={modalmovilRef}
                linkproductocentralsucursal={linkproductocentralsucursal}
                openVincularSucursalwithCentral={openVincularSucursalwithCentral}
                sendTareaRemoverDuplicado={sendTareaRemoverDuplicado}
                listselectEliminarDuplicados={listselectEliminarDuplicados}
                selectEliminarDuplicados={selectEliminarDuplicados}
                number={number}
                setInvnum={setInvnum}
                Invnum={Invnum}
                InvorderColumn={InvorderColumn}
                setInvorderColumn={setInvorderColumn}
                InvorderBy={InvorderBy}
                setInvorderBy={setInvorderBy}
                sameCatValue={sameCatValue}
                colorSucursal={colorSucursal}
                buscarInventario={buscarInventario}
                qBuscarInventario={qBuscarInventario}
                setQBuscarInventario={setQBuscarInventario}
                qBuscarInventarioSucursal={qBuscarInventarioSucursal}
                setqBuscarInventarioSucursal={setqBuscarInventarioSucursal}
                sucursales={sucursales}
                productosInventario={productosInventario}
                type={type}
                
                changeInventarioModificarDici={changeInventarioModificarDici}
                guardarmodificarInventarioDici={guardarmodificarInventarioDici} 
                inventarioGeneralqsucursal={inventarioGeneralqsucursal}
                setinventarioGeneralqsucursal={setinventarioGeneralqsucursal}

                selectIdVinculacion={selectIdVinculacion} 
                setselectIdVinculacion={setselectIdVinculacion}
                qvinculacion1={qvinculacion1} 
                setqvinculacion1={setqvinculacion1}
                qvinculacion2={qvinculacion2} 
                setqvinculacion2={setqvinculacion2}
                qvinculacion3={qvinculacion3} 
                setqvinculacion3={setqvinculacion3}
                qvinculacion4={qvinculacion4} 
                setqvinculacion4={setqvinculacion4}
                qvinculacionmarca={qvinculacionmarca} 
                setqvinculacionmarca={setqvinculacionmarca}
                datavinculacion1={datavinculacion1} 
                setdatavinculacion1={setdatavinculacion1}
                datavinculacion2={datavinculacion2} 
                setdatavinculacion2={setdatavinculacion2}
                datavinculacion3={datavinculacion3} 
                setdatavinculacion3={setdatavinculacion3}
                datavinculacion4={datavinculacion4} 
                setdatavinculacion4={setdatavinculacion4}
                datavinculacionmarca={datavinculacionmarca} 
                setdatavinculacionmarca={setdatavinculacionmarca}
                inputselectvinculacion1={inputselectvinculacion1} 
                setinputselectvinculacion1={setinputselectvinculacion1}
                inputselectvinculacion2={inputselectvinculacion2} 
                setinputselectvinculacion2={setinputselectvinculacion2}
                inputselectvinculacion3={inputselectvinculacion3} 
                setinputselectvinculacion3={setinputselectvinculacion3}
                inputselectvinculacion4={inputselectvinculacion4} 
                setinputselectvinculacion4={setinputselectvinculacion4}
                inputselectvinculacionmarca={inputselectvinculacionmarca} 
                setinputselectvinculacionmarca={setinputselectvinculacionmarca}
                inputselectvinculacion1General={inputselectvinculacion1General} 
                setinputselectvinculacion1General={setinputselectvinculacion1General}
                inputselectvinculacion2General={inputselectvinculacion2General} 
                setinputselectvinculacion2General={setinputselectvinculacion2General}
                inputselectvinculacion3General={inputselectvinculacion3General} 
                setinputselectvinculacion3General={setinputselectvinculacion3General}
                inputselectvinculacion4General={inputselectvinculacion4General} 
                setinputselectvinculacion4General={setinputselectvinculacion4General}
                inputselectvinculacionmarcaGeneral={inputselectvinculacionmarcaGeneral} 
                setinputselectvinculacionmarcaGeneral={setinputselectvinculacionmarcaGeneral}

                inputselectvinculacion5={inputselectvinculacion5}
                setinputselectvinculacion5={setinputselectvinculacion5}
                inputselectvinculacioncat={inputselectvinculacioncat}
                setinputselectvinculacioncat={setinputselectvinculacioncat}
                inputselectvinculacioncatesp={inputselectvinculacioncatesp}
                setinputselectvinculacioncatesp={setinputselectvinculacioncatesp}
                inputselectvinculacionproveedor={inputselectvinculacionproveedor}
                setinputselectvinculacionproveedor={setinputselectvinculacionproveedor}
                inputselectvinculacionmaxct={inputselectvinculacionmaxct}
                setinputselectvinculacionmaxct={setinputselectvinculacionmaxct}
                inputselectvinculacionminct={inputselectvinculacionminct}
                setinputselectvinculacionminct={setinputselectvinculacionminct}
                inputselectvinculacion5General={inputselectvinculacion5General}
                setinputselectvinculacion5General={setinputselectvinculacion5General}
                inputselectvinculacioncatGeneral={inputselectvinculacioncatGeneral}
                setinputselectvinculacioncatGeneral={setinputselectvinculacioncatGeneral}
                inputselectvinculacioncatespGeneral={inputselectvinculacioncatespGeneral}
                setinputselectvinculacioncatespGeneral={setinputselectvinculacioncatespGeneral}
                inputselectvinculacionproveedorGeneral={inputselectvinculacionproveedorGeneral}
                setinputselectvinculacionproveedorGeneral={setinputselectvinculacionproveedorGeneral}
                inputselectvinculacionmaxctGeneral={inputselectvinculacionmaxctGeneral}
                setinputselectvinculacionmaxctGeneral={setinputselectvinculacionmaxctGeneral}
                inputselectvinculacionminctGeneral={inputselectvinculacionminctGeneral}
                setinputselectvinculacionminctGeneral={setinputselectvinculacionminctGeneral}

                qvinculacion5={qvinculacion5} 
                setqvinculacion5={setqvinculacion5}
                qvinculaciocat={qvinculaciocat} 
                setqvinculaciocat={setqvinculaciocat}
                qvinculaciocatesp={qvinculaciocatesp} 
                setqvinculaciocatesp={setqvinculaciocatesp}
                qvinculacioproveedor={qvinculacioproveedor} 
                setqvinculacioproveedor={setqvinculacioproveedor}
                qvinculaciomaxct={qvinculaciomaxct} 
                setqvinculaciomaxct={setqvinculaciomaxct}
                qvinculaciominct={qvinculaciominct} 
                setqvinculaciominct={setqvinculaciominct}
                qvinculacion5General={qvinculacion5General} 
                setqvinculacion5General={setqvinculacion5General}
                qvinculaciocatGeneral={qvinculaciocatGeneral} 
                setqvinculaciocatGeneral={setqvinculaciocatGeneral}
                qvinculaciocatespGeneral={qvinculaciocatespGeneral} 
                setqvinculaciocatespGeneral={setqvinculaciocatespGeneral}
                qvinculacioproveedorGeneral={qvinculacioproveedorGeneral} 
                setqvinculacioproveedorGeneral={setqvinculacioproveedorGeneral}
                qvinculaciomaxctGeneral={qvinculaciomaxctGeneral} 
                setqvinculaciomaxctGeneral={setqvinculaciomaxctGeneral}
                qvinculaciominctGeneral={qvinculaciominctGeneral} 
                setqvinculaciominctGeneral={setqvinculaciominctGeneral}
                datavinculacion5={datavinculacion5} 
                setdatavinculacion5={setdatavinculacion5}
                datavinculaciocat={datavinculaciocat} 
                setdatavinculaciocat={setdatavinculaciocat}
                datavinculaciocatesp={datavinculaciocatesp} 
                setdatavinculaciocatesp={setdatavinculaciocatesp}
                datavinculacioproveedor={datavinculacioproveedor} 
                setdatavinculacioproveedor={setdatavinculacioproveedor}
                datavinculaciomaxct={datavinculaciomaxct} 
                setdatavinculaciomaxct={setdatavinculaciomaxct}
                datavinculaciominct={datavinculaciominct} 
                setdatavinculaciominct={setdatavinculaciominct}
                newNombre5={newNombre5}
                setnewNombre5={setnewNombre5}
                newNombrecat={newNombrecat}
                setnewNombrecat={setnewNombrecat}
                newNombrecatesp={newNombrecatesp}
                setnewNombrecatesp={setnewNombrecatesp}
                newNombreproveedor={newNombreproveedor}
                setnewNombreproveedor={setnewNombreproveedor}
                newNombremaxct={newNombremaxct}
                setnewNombremaxct={setnewNombremaxct}
                newNombreminct={newNombreminct}
                setnewNombreminct={setnewNombreminct}

                getDatinputSelectVinculacion={getDatinputSelectVinculacion}
                saveCuatroNombres={saveCuatroNombres}

                qvinculacion1General={qvinculacion1General}
                qvinculacion2General={qvinculacion2General}
                qvinculacion3General={qvinculacion3General}
                qvinculacion4General={qvinculacion4General}
                qvinculacionmarcaGeneral={qvinculacionmarcaGeneral}
                id_sucursal_select_internoModal={id_sucursal_select_internoModal}
                setid_sucursal_select_internoModal={setid_sucursal_select_internoModal}
                productosInventarioModal={productosInventarioModal}
                setproductosInventarioModal={setproductosInventarioModal}
                InvnumModal={InvnumModal}
                setInvnumModal={setInvnumModal}
                qBuscarInventarioModal={qBuscarInventarioModal}
                setqBuscarInventarioModal={setqBuscarInventarioModal}
                InvorderColumnModal={InvorderColumnModal}
                setInvorderColumnModal={setInvorderColumnModal}
                InvorderByModal={InvorderByModal}
                setInvorderByModal={setInvorderByModal}
                buscarInventarioModal={buscarInventarioModal}
                idselectproductoinsucursalforvicular={idselectproductoinsucursalforvicular}
            />
        :null}

        {subviewdici=="tareaspendientes"?
            <TareasSucursalesPendientes
                delTareaPendiente={delTareaPendiente}
                aprobarPermisoModDici={aprobarPermisoModDici}
                sucursales={sucursales}
                setqTareaPendienteFecha={setqTareaPendienteFecha}
                qTareaPendienteFecha={qTareaPendienteFecha}
                qTareaPendienteSucursal={qTareaPendienteSucursal}
                setqTareaPendienteSucursal={setqTareaPendienteSucursal}
                getTareasPendientes={getTareasPendientes}
                tareasPendientesData={tareasPendientesData}

                qTareaPendienteEstado={qTareaPendienteEstado}
                setqTareaPendienteEstado={setqTareaPendienteEstado}
                qTareaPendienteNum={qTareaPendienteNum}
                setqTareaPendienteNum={setqTareaPendienteNum}
            />
        :null}

        {subviewdici=="gestionarnombres"?
            <GestionarnombresInventario
                buscarNombres={buscarNombres}
                qnombres={qnombres}
                setqnombres={setqnombres}
                qtiponombres={qtiponombres}
                setqtiponombres={setqtiponombres}
                datanombres={datanombres}
                modNombres={modNombres}
                newNombres={newNombres}
            />
        :null}

        {subviewdici=="garantias"?
            <Garantias
                garantiasData={garantiasData}
                garantiaq={garantiaq}
                setgarantiaq={setgarantiaq}
                garantiaqsucursal={garantiaqsucursal}
                setgarantiaqsucursal={setgarantiaqsucursal}
                getGarantias={getGarantias}
                sucursales={sucursales}
            />
        :null}






    </div>
    )
}