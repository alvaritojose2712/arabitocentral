import { useEffect } from "react"
import SucursalDetallesCierres from './panel/sucursaldetallescierres'
import SucursalDetallesinvetario from './panel/sucursaldetallesinvetario'
import Puntosyseriales from './panel/Puntosyseriales'
import Controldeefectivo from './panel/Controldeefectivo'



export default function PanelSucursales({
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

}){
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
    ])


    return (
        <div className="container-fluid">
            <div className="row">
                <div className="col-1">
                    <ul className="list-group">
                        <li className={("list-group-item pointer ")+("resumen"==sucursalSelect?"bg-sinapsis":"")} onClick={()=>setsucursalSelect("resumen")}>RESUMEN</li>

                        {sucursales.map(e=>
                            <li key={e.id} className={("list-group-item pointer ")+(e.id==sucursalSelect?"bg-sinapsis":"")} onClick={()=>setsucursalSelect(e.id)}>{e.nombre}</li>
                        )}
                    </ul>
                </div>

                <div className="col-10">
                    {children}
                    <div className="btn-group mt-2">
                        <button className={("btn btn")+(subviewpanelsucursales=="cierres"?"":"-outline")+("-success")} onClick={()=>setsubviewpanelsucursales("cierres")}> Cierres </button>
                        <button className={("btn btn")+(subviewpanelsucursales=="puntosyseriales"?"":"-outline")+("-success")} onClick={()=>setsubviewpanelsucursales("puntosyseriales")}> Puntos de Venta </button>
                        <button className={("btn btn")+(subviewpanelsucursales=="inventario"?"":"-outline")+("-success")} onClick={()=>setsubviewpanelsucursales("inventario")}> Inventario </button>
                        <button className={("btn btn")+(subviewpanelsucursales=="estadisticas"?"":"-outline")+("-success")} onClick={()=>setsubviewpanelsucursales("estadisticas")}> Estadísticas </button>
                        <button className={("btn btn-light")}></button>
                        <button className={("btn btn")+(subviewpanelsucursales=="controldeefectivo"?"":"-outline")+("-success")} onClick={()=>setsubviewpanelsucursales("controldeefectivo")}> Control de Efectivo </button>
                        <button className={("btn btn")+(subviewpanelsucursales=="creditos"?"":"-outline")+("-success")} onClick={()=>setsubviewpanelsucursales("creditos")}> Créditos </button>
                        <button className={("btn btn-light")}></button>
                        <button className={("btn btn")+(subviewpanelsucursales=="nomina"?"":"-outline")+("-success")} onClick={()=>setsubviewpanelsucursales("nomina")}> Nómina </button>
                    </div>


                    {subviewpanelsucursales=="cierres"?
                        <SucursalDetallesCierres
                            sucursalDetallesData={sucursalDetallesData}                        
        
                        /> 
                    :null}

                    {subviewpanelsucursales=="inventario"?
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
                    :null}

                    {subviewpanelsucursales=="puntosyseriales"?
                        <Puntosyseriales
                            getsucursalDetallesData={getsucursalDetallesData}
                            sucursalDetallesData={sucursalDetallesData}                        
                        /> 
                    :null}


                    {subviewpanelsucursales=="controldeefectivo"?
                        <Controldeefectivo
                            getsucursalDetallesData={getsucursalDetallesData}
                            sucursalDetallesData={sucursalDetallesData}   
                            controlefecSelectGeneral={controlefecSelectGeneral}
                            setcontrolefecSelectGeneral={setcontrolefecSelectGeneral}
                            moneda={moneda}                  
                        /> 
                    :null}


                </div>
            </div>
        </div>
    )
}