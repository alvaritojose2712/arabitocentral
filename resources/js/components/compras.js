import { useEffect } from 'react';

export default function Compras({
    setviewmainPanel
}) {

    
    return (
        <>

            <div className="row">
                <div className="col d-flex justify-content-center">
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
                    <div className="circle" onClick={()=>setviewmainPanel("inventario")}>
                        <div className="circle_title">
                            <h2>RECIBIR</h2>
                            <h3>Módulo</h3>
                        </div>
                        <div className="circle_inner">
                            R
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
                </div> 
            </div>    
        </>


    )
}