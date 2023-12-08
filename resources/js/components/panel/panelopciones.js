import "../../../css/panelopciones.css";
import cierresImg from "../../../images/cierres.jpg";
import gastosImg from "../../../images/gastos.jpg";
import inventarioImg from "../../../images/inventario.jpg";
export default function PanelOpciones({
    viewmainPanel,
    setviewmainPanel,
    opciones,
}){

    
    return(
        <div className="container-fluid">
            <div className="row">

                {opciones.map((opcion,i)=>
                    <div key={i} className="col d-flex justify-content-center">
                        <div className="circle" onClick={()=>setviewmainPanel(opcion.route)}>
                            <div className="circle_title">
                                <h2>{opcion.name}</h2>
                                <h3>Módulo</h3>
                            </div>
                            <div className="circle_inner">
                                {opcion.name[0]}
                            </div>
                            <div className="content_shadow"></div>
                        </div>
                    </div> 
                )}
            </div>
        </div>
    )
}
