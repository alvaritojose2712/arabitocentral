import { useEffect, useState } from "react";
export default function Efectivo({
    children,
    subviewpanelsucursales,
    setsubviewpanelsucursales,

    getsucursalDetallesData,
    subViewCuentasxPagar,
    setsubViewCuentasxPagar,
}){

    /* useEffect(()=>{
        getsucursalDetallesData()
    },[
        subviewpanelsucursales,
    ]) */

    useEffect(()=>{
        setsubviewpanelsucursales("cuentasporpagar")
        setsubViewCuentasxPagar("proveedor")
    },[])

    const [toggleClientesBtn,settoggleClientesBtn] = useState(false)
    return (
        <>
            <div className="d-flex justify-content-center">
                <div className="btn-group m-1">
                    <span className={(subViewCuentasxPagar=="disponible"?"btn-sinapsis":"")+(" btn")} onClick={()=>{setsubViewCuentasxPagar("disponible");settoggleClientesBtn(false)}}>EFECTIVO</span>
                    <span className={(subViewCuentasxPagar=="banco"?"btn-sinapsis":"")+(" btn")} onClick={()=>{setsubViewCuentasxPagar("banco");settoggleClientesBtn(false)}}>BANCO</span>
                    <span className={(subViewCuentasxPagar=="proveedor"?"btn-sinapsis":"")+(" btn")} onClick={()=>{setsubViewCuentasxPagar("proveedor");settoggleClientesBtn(false)}}>Proveedor</span>
                    <span className={(subViewCuentasxPagar=="detallado" ? "btn-sinapsis":"")+(" btn")} onClick={() => {setsubViewCuentasxPagar("detallado");settoggleClientesBtn(false)}}>General</span>
                   {/*  <button className={("btn btn-sm ")+(subviewpanelsucursales=="aprobacioncajafuerte"?"btn-sinapsis":"")} onClick={()=>setsubviewpanelsucursales("aprobacioncajafuerte")}>Aprobación</button> */}
                   {/*  <div className="dropdown btn">
                        <button className={(toggleClientesBtn ? "btn btn-sinapsis" : null)+(" btn btn-sm dropdown-toggle")} type="button" onClick={() => {settoggleClientesBtn(!toggleClientesBtn); setsubviewpanelsucursales("cuentasporpagar")}}>
                            Cuentas Por Pagar
                        </button>
                        <ul className={("dropdown-menu ")+ (toggleClientesBtn?"show":null)} onMouseLeave={()=>settoggleClientesBtn(false)}>
                        
                            <li>
                            </li>
                            <li>
                            </li>
                            <li>

                            </li>
                        </ul>
                    </div> */}
                </div>
            </div>
            {children}
        </>
    )
}