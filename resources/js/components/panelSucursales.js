import { useEffect } from "react"
import SucursalDetallesCierres from './panel/sucursaldetallescierres'
import SucursalResumencierres from './panel/SucursalResumencierres'

import SucursalDetallesinvetario from './panel/sucursaldetallesinvetario'
import Puntosyseriales from './panel/Puntosyseriales'
import Controldeefectivo from './panel/Controldeefectivo'
import NominasSucursal from './panel/nominasucursal'
import Fallas from './panel/fallas'
import Creditos from './panel/creditos'


export default function PanelSucursales({
    controlefecSelectCat,
    setcontrolefecSelectCat,
    children,
    sucursales,
    sucursalSelect,
    setsucursalSelect,
    subviewpanelsucursales,
    setsubviewpanelsucursales,
    fechasMain1,
    fechasMain2,
    getSucursales,
    getsucursalListData,
    getsucursalDetallesData,
    changeLiquidacionPagoElec,

    sucursalDetallesData,

    invsuc_itemCero,
    setinvsuc_itemCero,
    invsuc_q,
    setinvsuc_q,
    invsuc_exacto,
    setinvsuc_exacto,
    invsuc_num,
    setinvsuc_num,
    invsuc_orderColumn,
    setinvsuc_orderColumn,
    invsuc_orderBy,
    setinvsuc_orderBy,

    controlefecSelectGeneral,
    setcontrolefecSelectGeneral,
    moneda,

    filtronominaq,
    setfiltronominaq,
    filtronominacargo,
    setfiltronominacargo,
    getPersonalCargos,
    cargosData,

    fechaSelectAuditoria,
    setfechaSelectAuditoria,
    BancoSelectAuditoria,
    setBancoSelectAuditoria,
    SaldoInicialSelectAuditoria,
    setSaldoInicialSelectAuditoria,
    SaldoActualSelectAuditoria,
    setSaldoActualSelectAuditoria,
    getCatGeneralFun,
    getCatCajas,
    user,
    permiso,
    controlefecQDescripcion,
    setcontrolefecQDescripcion,


}) {
    useEffect(() => {
        getSucursales()
    }, [])
    /*  useEffect(() => {
            getsucursalListData()
        }, [subviewpanelsucursales,fechasMain1,fechasMain2]) */

    useEffect(() => {
        getsucursalDetallesData()
    }, [
        sucursalSelect,
        subviewpanelsucursales,
        fechasMain1,
        fechasMain2,
        controlefecSelectGeneral,
        controlefecSelectCat,
    ])

    try {
   
        return (
            <div className="container-fluid">
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

                <div className="row">
                    <div className="col table-responsive mb-2">
                        {children}
                        
                    </div>
                </div>
                <div className="row">
                    <div className="col table-responsive mb-2 pb-2 pt-2 d-flex justify-content-center">
                        <div className="btn-group w-100">
                                <>
                                    <button className={("btn btn") + (subviewpanelsucursales == "resumencierres" ? "" : "-outline") + ("-success")} onClick={() => setsubviewpanelsucursales("resumencierres")}>RESUMEN</button>
                                    <button className={("btn btn") + (subviewpanelsucursales == "cierres" ? "" : "-outline") + ("-success")} onClick={() => setsubviewpanelsucursales("cierres")}>Cierres</button>
                                </>
                                <>
                                    <button className={("btn btn") + (subviewpanelsucursales == "controldeefectivo" ? "" : "-outline") + ("-success")} onClick={() => setsubviewpanelsucursales("controldeefectivo")}>Efectivo</button>
                                </>
                            

                                <>
                                    <button className={("btn btn") + (subviewpanelsucursales == "puntosyseriales" ? "" : "-outline") + ("-success")} onClick={() => setsubviewpanelsucursales("puntosyseriales")}>PagoElectrónicos</button>
                                    <button className={("btn btn") + (subviewpanelsucursales == "creditos" ? "" : "-outline") + ("-success")} onClick={() => setsubviewpanelsucursales("creditos")}>Créditos</button>
                                    <button className={("btn btn") + (subviewpanelsucursales == "inventario" ? "" : "-outline") + ("-sinapsis")} onClick={() => setsubviewpanelsucursales("inventario")}>Inventario</button>
                                    <button className={("btn btn") + (subviewpanelsucursales == "estadisticas" ? "" : "-outline") + ("-sinapsis")} onClick={() => setsubviewpanelsucursales("estadisticas")}>Estadísticas</button>
                                    <button className={("btn btn") + (subviewpanelsucursales == "fallas" ? "" : "-outline") + ("-danger")} onClick={() => setsubviewpanelsucursales("fallas")}>Fallas</button>
                                    
                                    <button className={("btn btn") + (subviewpanelsucursales == "nomina" ? "" : "-outline") + ("-info")} onClick={() => setsubviewpanelsucursales("nomina")}> Nómina </button>
                                </>

                        </div>

                    </div>
                </div>
                <div className="row">
                    <div className="col table-responsive mb-2">
                        
                        {permiso([1,2]) && subviewpanelsucursales == "resumencierres" ?
                            <SucursalResumencierres
                                sucursalDetallesData={sucursalDetallesData}
                                moneda={moneda}
                                

                            />
                        : null}

                        {permiso([1,2,3]) && subviewpanelsucursales == "cierres" ?
                            <SucursalDetallesCierres
                                sucursalDetallesData={sucursalDetallesData}

                            />
                        : null}

                        {permiso([1,2,3,7,8]) && subviewpanelsucursales == "inventario" ?
                            <SucursalDetallesinvetario
                                getsucursalDetallesData={getsucursalDetallesData}
                                sucursalDetallesData={sucursalDetallesData}

                                invsuc_itemCero={invsuc_itemCero}
                                setinvsuc_itemCero={setinvsuc_itemCero}
                                invsuc_q={invsuc_q}
                                setinvsuc_q={setinvsuc_q}
                                invsuc_exacto={invsuc_exacto}
                                setinvsuc_exacto={setinvsuc_exacto}
                                invsuc_num={invsuc_num}
                                setinvsuc_num={setinvsuc_num}
                                invsuc_orderColumn={invsuc_orderColumn}
                                setinvsuc_orderColumn={setinvsuc_orderColumn}
                                invsuc_orderBy={invsuc_orderBy}
                                setinvsuc_orderBy={setinvsuc_orderBy}
                            />
                            : null}

                        {permiso([1,2,3]) && subviewpanelsucursales == "puntosyseriales" ?
                            <Puntosyseriales
                                getsucursalDetallesData={getsucursalDetallesData}
                                sucursalDetallesData={sucursalDetallesData}
                                changeLiquidacionPagoElec={changeLiquidacionPagoElec}

                                fechaSelectAuditoria={fechaSelectAuditoria}
                                setfechaSelectAuditoria={setfechaSelectAuditoria}
                                BancoSelectAuditoria={BancoSelectAuditoria}
                                setBancoSelectAuditoria={setBancoSelectAuditoria}
                                SaldoInicialSelectAuditoria={SaldoInicialSelectAuditoria}
                                setSaldoInicialSelectAuditoria={setSaldoInicialSelectAuditoria}
                                SaldoActualSelectAuditoria={SaldoActualSelectAuditoria}
                                setSaldoActualSelectAuditoria={setSaldoActualSelectAuditoria}
                            />
                            : null}


                        {permiso([1,2,3,5]) && subviewpanelsucursales == "controldeefectivo" ?
                            <Controldeefectivo
                                controlefecQDescripcion={controlefecQDescripcion}
                                setcontrolefecQDescripcion={setcontrolefecQDescripcion}
                                controlefecSelectCat={controlefecSelectCat}
                                setcontrolefecSelectCat={setcontrolefecSelectCat}
                                getsucursalDetallesData={getsucursalDetallesData}
                                sucursalDetallesData={sucursalDetallesData}
                                controlefecSelectGeneral={controlefecSelectGeneral}
                                setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
                                moneda={moneda}
                                getCatGeneralFun={getCatGeneralFun}
                                getCatCajas={getCatCajas}

                            />
                            : null}

                        {permiso([1,2]) && subviewpanelsucursales == "nomina" ?
                            <NominasSucursal
                                getsucursalDetallesData={getsucursalDetallesData}
                                sucursalDetallesData={sucursalDetallesData}
                                controlefecSelectGeneral={controlefecSelectGeneral}
                                setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
                                filtronominaq={filtronominaq}
                                setfiltronominaq={setfiltronominaq}
                                filtronominacargo={filtronominacargo}
                                setfiltronominacargo={setfiltronominacargo}
                                moneda={moneda}

                                getPersonalCargos={getPersonalCargos}
                                cargosData={cargosData}
                            />
                        : null}

                        {permiso([1,2]) && subviewpanelsucursales == "fallas" ?
                            <Fallas
                                getsucursalDetallesData={getsucursalDetallesData}
                                sucursalDetallesData={sucursalDetallesData}
                                moneda={moneda}
                            />
                        : null}

                        {permiso([1,2]) && subviewpanelsucursales == "creditos" ?
                            <Creditos
                                getsucursalDetallesData={getsucursalDetallesData}
                                sucursalDetallesData={sucursalDetallesData}
                                moneda={moneda}
                            />
                        : null}
                    </div>
                </div>
            </div>
        )
    } catch (error) {
        return "CARGANDO"
    }
}
