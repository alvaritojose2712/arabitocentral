import { useEffect } from "react";
export default function Efectivo({
    children,
    subviewpanelsucursales,
    setsubviewpanelsucursales,

    getsucursalDetallesData,
    fechasMain1,
    fechasMain2,
    sucursalSelect,
    qestatusaprobaciocaja,
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
    return (
        <>
            <div className="d-flex justify-content-center">
                <div className="btn-group m-2">
                    <button className={("btn btn-sm ")+(subviewpanelsucursales=="aprobacioncajafuerte"?"btn-sinapsis":"")} onClick={()=>setsubviewpanelsucursales("aprobacioncajafuerte")}>Aprobación</button>
                    <button className={("btn btn-sm ")+(subviewpanelsucursales=="cuentasporpagar"?"btn-sinapsis":"")} onClick={()=>setsubviewpanelsucursales("cuentasporpagar")}>Cuentas Por Pagar</button>
                </div>
            </div>
            {children}
        </>
    )
}