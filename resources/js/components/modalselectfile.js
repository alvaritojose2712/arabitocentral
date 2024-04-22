export default function Modalselectfile({
    numfact_select_imagen,
    setselectFilecxp,
    colorSucursal,
    showFilescxp,
}){
    return (
        numfact_select_imagen?<div className="boton-fijo-inferiorizq">
            <div className={"container-fluid shadow card fs-3"}>
                <b>IMAGEN SELECCIONADA <i className="fa fa-times text-danger" onClick={()=>setselectFilecxp(null)}></i></b>
                <div className="text-center">
                    <div>
                        <span className="text-muted fst-italic">{numfact_select_imagen.created_at}</span>
                    </div>
                    <div>
                        <span className="fs-3 fw-bolder">{numfact_select_imagen.proveedor?numfact_select_imagen.proveedor.descripcion:null}</span>
                    </div>
                    <div>
                        <span className={("btn-sinapsis")+(" btn fs-2 pointer fw-bolder text-light me-1 ")}> 
                            {numfact_select_imagen.numfact}
                        </span>
                        <button className={"btn fw-bolder fs-2"} style={{backgroundColor:colorSucursal(numfact_select_imagen.sucursal? numfact_select_imagen.sucursal.codigo:"")}}>
                            {numfact_select_imagen.sucursal?numfact_select_imagen.sucursal.codigo:null}
                        </button>
                    </div>
                </div>
                
                <div className="text-center">
                    <div>
                        <img src={numfact_select_imagen.ruta} width={600} onClick={()=>showFilescxp(numfact_select_imagen.ruta)} className="pointer"/>
                    </div>
                    <span>{numfact_select_imagen.ruta}</span>
                </div>
            </div>
        </div>:""
    )
}