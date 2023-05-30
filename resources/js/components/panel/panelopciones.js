import "../../../css/panelopciones.css";
import cierresImg from "../../../images/cierres.jpg";
import gastosImg from "../../../images/gastos.jpg";
import inventarioImg from "../../../images/inventario.jpg";
export default function PanelOpciones({
    viewmainPanel,
    setviewmainPanel,
}){

    return(
        <div className="container-fluid">
            <div className="row">
                <div className="col d-flex justify-content-center">
                    <div className="circle" onClick={()=>setviewmainPanel("inventario")}>
                        <div className="circle_title">
                            <h2>Inventario</h2>
                            <h3>Módulo</h3>
                        </div>
                        <div className="circle_inner" style={{
                            backgroundImage:"url('"+inventarioImg+"')",
                            backgroundSize:"cover"
                        }}>
                            
                        </div>
                        <div className="content_shadow"></div>
                    </div>
                </div>
                <div className="col d-flex justify-content-center">
                    <div className="circle" onClick={()=>setviewmainPanel("cierres")}>
                    <div className="circle_title">
                        <h2>Cierres</h2>
                        <h3>Módulo</h3>
                    </div>
                    <div className="circle_inner" style={{
                        backgroundImage:"url('"+cierresImg+"')",
                        backgroundSize:"cover"
                    }}>
                    </div>
                    <div className="content_shadow"></div>
                    </div>
                </div>
                <div className="col d-flex justify-content-center">
                    <div className="circle" onClick={()=>setviewmainPanel("gastos")}>
                    <div className="circle_title">
                        <h2>Gastos</h2>
                        <h3>Módulo</h3>
                    </div>
                    <div className="circle_inner"style={{
                        backgroundImage:"url('"+gastosImg+"')",
                        backgroundSize:"cover"
                    }}>
                    
                    </div>
                    <div className="content_shadow"></div>
                    </div>
                </div>
            </div>
        </div>
    )
}
