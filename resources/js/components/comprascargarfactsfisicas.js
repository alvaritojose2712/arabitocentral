import { useEffect, useState } from "react";
export default function Comprascargarfacts({
    proveedoresList,
    setfactInpImagen,
    factInpProveedor,              
    setfactInpProveedor,
   
    sucursales,
    factInpImagen,  

    factNumfact,              
    setfactNumfact,
    
    sendComprasFats,              
}){
    const [buscadorProveedor, setbuscadorProveedor] = useState("")

    useEffect(()=>{
            let fil = proveedoresList.find(e=>e.descripcion.toLowerCase().indexOf(buscadorProveedor.toLowerCase())!=-1)
            if (fil) {
                if (fil && buscadorProveedor!="") {
                    let id = fil.id
                    setfactInpProveedor(id)
                }
            }
        },[buscadorProveedor])
    return <>
        <form onSubmit={sendComprasFats} className="container">
            <span className="fs-4 fw-bolder">COMPRAS - CARGAR FACTURAS</span>

            <div className="row mb-2">
                <div className="col">
                    <span className="label-text">
                        <i className="fa fa-search text-success"></i> PROVEEDOR
                    </span>
                    <input className="form-control" onChange={event=>setbuscadorProveedor(event.target.value)} value={buscadorProveedor}/>

                </div>
                <div className="col">
                    <span className="label-text">
                        PROVEEDOR
                    </span>
                    <select className="form-control" onChange={event=>setfactInpProveedor(event.target.value)} value={factInpProveedor}>
                        <option value="">-TODOS LOS PROVEEDORES-</option>
                        {proveedoresList.map(e=>
                            <option key={e.id} value={e.id}>{e.descripcion}-{e.rif}</option>
                        )}
                    </select>
                </div>
            </div>

            <div className="row mb-1">
                <div className="col">
                    <b className="label-text">
                        NÚMERO DE FACTURA
                    </b>
                    <input className="form-control fs-2 text-success" placeholder="Número completo de Factura..." onChange={event=>setfactNumfact(event.target.value)} value={factNumfact}/>
                </div>
            </div>

            <div className="form-group mb-2">
                <label htmlFor="formFile" className="form-label">Adjunte FOTO NITIDA, COMPLETA Y CENTRADA DE LA FACTURA</label>
                <input type="file" required={true} className="form-control" id="formFile" onChange={event=>setfactInpImagen(event.target.files[0])}/>
            </div>

            <div className="row mb-1">
                <div className="col text-center">
                    <button className="btn btn-success btn-lg">ENVIAR</button>
                </div>
            </div>
        </form>
    </>
}