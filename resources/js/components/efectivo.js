import { useEffect, useState } from "react";
export default function Efectivo({
    children,
    subviewpanelsucursales,
    setsubviewpanelsucursales,

    getsucursalDetallesData,
    fechasMain1,
    fechasMain2,
    sucursalSelect,
    qestatusaprobaciocaja,
    subViewCuentasxPagar,
    setsubViewCuentasxPagar,
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

    const [toggleClientesBtn,settoggleClientesBtn] = useState(false)
    return (
        <>
            <div className="d-flex justify-content-center">
                <div className="btn-group m-1">
                    <button className={("btn btn-sm ")+(subviewpanelsucursales=="aprobacioncajafuerte"?"btn-sinapsis":"")} onClick={()=>setsubviewpanelsucursales("aprobacioncajafuerte")}>Aprobación</button>
                    <div className="dropdown btn">
                        <button className={(toggleClientesBtn ? "btn btn-sinapsis" : null)+(" btn btn-sm dropdown-toggle")} type="button" onClick={() => {settoggleClientesBtn(!toggleClientesBtn); setsubviewpanelsucursales("cuentasporpagar")}}>
                            Cuentas Por Pagar
                        </button>
                        <ul className={("dropdown-menu ")+ (toggleClientesBtn?"show":null)} onMouseLeave={()=>settoggleClientesBtn(false)}>
                        
                            <li>
                            <span className={(subViewCuentasxPagar=="disponible"?"btn btn-sinpasis":"btn")+(" p-3 pointer dropdown-item")} onClick={()=>{setsubViewCuentasxPagar("disponible");settoggleClientesBtn(false)}}>Efectivo Disponible</span>
                            </li>
                            <li>
                            <span className={(subViewCuentasxPagar=="proveedor"?"btn btn-sinpasis":"btn")+(" p-3 pointer dropdown-item")} onClick={()=>{setsubViewCuentasxPagar("proveedor");settoggleClientesBtn(false)}}>Proveedor</span>
                            </li>
                            <li>
                            <span className={(subViewCuentasxPagar=="detallado" ? "btn btn-sinpasis":"btn") + (" p-3 pointer dropdown-item")} onClick={() => {setsubViewCuentasxPagar("detallado");settoggleClientesBtn(false)}}>General</span>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            {children}
        </>
    )
}