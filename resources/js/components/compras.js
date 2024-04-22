import { useEffect } from 'react';

export default function Compras({
    setviewmainPanel,
    permiso,
}) {

    
    return (
        <>

            <div className="row">
                {permiso([1]) && <>
                    {/* <div className="col d-flex justify-content-center">
                        <div className="circle" onClick={()=>setviewmainPanel("pedir")}>
                            <div className="circle_title">
                                <h2>PEDIR</h2>
                                <h3>Módulo</h3>
                            </div>
                            <div className="circle_inner">
                                P
                            </div>
                            <div className="content_shadow"></div>
                        </div>
                    </div> 
                    <div className="col d-flex justify-content-center">
                        <div className="circle" onClick={()=>setviewmainPanel("enviar")}>
                            <div className="circle_title">
                                <h2>ENVIAR</h2>
                                <h3>Módulo</h3>
                            </div>
                            <div className="circle_inner">
                                E
                            </div>
                            <div className="content_shadow"></div>
                        </div>
                    </div>  */}
                    <div className="col d-flex justify-content-center">
                        <div className="circle" onClick={()=>setviewmainPanel("proveedores")}>
                            <div className="circle_title">
                                <h2>PROVEEDORES</h2>
                                <h3>Módulo</h3>
                            </div>
                            <div className="circle_inner">
                                P
                            </div>
                            <div className="content_shadow"></div>
                        </div>
                    </div> 
                </>}
                {permiso([1,10]) && <>
                    <div className="col d-flex justify-content-center">
                        <div className="circle" onClick={()=>setviewmainPanel("cargarfactsdigitales")}>
                            <div className="circle_title">
                                <h2>PROCESAR FACTURAS</h2>
                                <h3>Módulo</h3>
                            </div>
                            <div className="circle_inner">
                                P
                            </div>
                            <div className="content_shadow"></div>
                        </div>
                    </div> 
                </>}

                {permiso([1,9,10]) && <>
                    <div className="col d-flex justify-content-center">
                        <div className="circle" onClick={()=>setviewmainPanel("comprascargarfactsfisicas")}>
                            <div className="circle_title">
                                <h2>CARGAR FACTURAS</h2>
                                <h3>Módulo</h3>
                            </div>
                            <div className="circle_inner">
                                C
                            </div>
                            <div className="content_shadow"></div>
                        </div>
                    </div> 
                </>}

                
                

            </div>    
        </>


    )
}