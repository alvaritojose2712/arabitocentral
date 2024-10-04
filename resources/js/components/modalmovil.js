import { useEffect, useState } from "react"

export default function Modalmovil({
    id_sucursal_select,
    x,
    y,
    setmodalmovilshow,
    modalmovilshow,
    linkproductocentralsucursal,
    inputbuscarcentralforvincular,
    modalmovilRef,
    margin=42,
    
    getProductos,
    productos,
    
    sucursales=[],
    id_sucursal_select_internoModal,
    setid_sucursal_select_internoModal,

    setproductosInventarioModal,
    InvnumModal,
    setInvnumModal,
    InvorderColumnModal,
    setInvorderColumnModal,
    InvorderByModal,
    setInvorderByModal,

    qBuscarInventarioModal,
    setqBuscarInventarioModal,
    id_sucursal_select_interno,
    setid_sucursal_select_interno,
    idselectproductoinsucursalforvicular,
}) {

    useEffect(()=>{
        
        if (inputbuscarcentralforvincular) {
            if (inputbuscarcentralforvincular.current) {
                inputbuscarcentralforvincular.current.focus()
            }
        }
        if (modalmovilRef) {
            if (modalmovilRef.current) {
                modalmovilRef.current?.scrollIntoView({ block: "nearest", behavior: 'smooth' });
            }
        }

    },[y])
    return (
        <div className="modalmovil" style={{top:y+margin,left:x}} ref={modalmovilRef} >
            <div className="text-center p-3">
                <i className="fa fa-times text-danger" onClick={()=>setmodalmovilshow(false)}></i>
            </div>
            <form className="input-group" onSubmit={event=>{event.preventDefault();getProductos(null,(id_sucursal_select?id_sucursal_select:id_sucursal_select_interno))}}>
                <input type="text" className="form-control" placeholder="Buscar en Local..." onChange={event=>setqBuscarInventarioModal(event.target.value)} value={qBuscarInventarioModal} />
                
                {sucursales.length?
                    <select className="form-control" value={id_sucursal_select_interno} onChange={event=>setid_sucursal_select_internoModal(event.target.value)}>
                        <option value={""}>-SUCURSAL-</option>
                        {sucursales.map(e=>
                            <option key={e.id} value={e.id}>{e.codigo}</option>
                        )}
                    </select>
                :null}
                <select className="form-control" value={InvnumModal} onChange={event=>setInvnumModal(event.target.value)}>
                    <option value="100">100</option>
                    <option value="300">300</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                    <option value="2000">2000</option>
                </select>
            </form>
            
            <table className="table">
                <thead>
                    <tr>
                        <td></td>
                        <th>ALTERNO</th>
                        <th>BARRAS</th>
                        <th>UNIDAD</th>
                        <th>DESCRIPCIÓN</th>
                        <th>BASE</th>
                        <th>VENTA</th>
                        <th>CT</th>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                {productos.length?productos.filter(e=>{
                    if (id_sucursal_select) {
                        return e.id_sucursal==id_sucursal_select
                    }else{
                        if (!id_sucursal_select_internoModal) {
                           return e.id_sucursal!=13 
                        }else{
                            
                            return e.id_sucursal==id_sucursal_select_internoModal 
                        }
                    }
                }).map(e=>
                    <tr key={e.id} data-id={e.id} className="pointer align-middle">
                        
                        
                        <td> 
                            {idselectproductoinsucursalforvicular.vinculados?
                                idselectproductoinsucursalforvicular.vinculados.filter(vin=>(vin.id_sucursal_fore==e.id_sucursal && vin.idinsucursal_fore==e.idinsucursal)).length?
                                <button className="btn btn-warning" >
                                    <i className="fa fa-link fa-2x"></i> <br /> #{e.idinsucursal}
                                </button>
                                :
                                <button className="btn btn-outline-success" onClick={()=>linkproductocentralsucursal(e.idinsucursal,e.id_sucursal)}>
                                    <i className="fa fa-link fa-2x"></i> <br /> #{e.idinsucursal}
                                </button>
                            :null}

                            {id_sucursal_select?
                                <button className="btn btn-outline-success" onClick={()=>linkproductocentralsucursal(e.idinsucursal,e.id_sucursal)}>
                                    <i className="fa fa-link fa-2x"></i> <br /> #{e.idinsucursal}
                                </button>
                            :null}
                        </td>
                        <td>{e.codigo_proveedor}</td>
                        <td>{e.codigo_barras}</td>
                        <td>{e.unidad}</td>
                        <td>{e.descripcion}</td>
                        <td>{e.precio_base}</td>
                        <td className="text-success">{e.precio}</td>
                        <td>{e.cantidad}</td>
                        <td>
                            <button className={"btn w-100 fw-bolder fs-5"} style={{backgroundColor:e.sucursal.background}}>
                                {e.sucursal.codigo}
                            </button>
                        </td>
                    </tr>
                ):null}
                </tbody>
            </table>
        </div>
    )
}