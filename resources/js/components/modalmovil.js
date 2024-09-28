import { useEffect } from "react"

export default function Modalmovil({
    facturaSelectAddItemsSelect,
    x,
    y,
    setmodalmovilshow,
    modalmovilshow,
    getProductos,
    productos,
    linkproductocentralsucursal,
    inputbuscarcentralforvincular,
    modalmovilRef,
    margin=42
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
        <div className="modalmovil" style={{top:y+margin,left:x}} ref={modalmovilRef} onMouseLeave={()=>setmodalmovilshow(false)}>
            <form className="input-group" onSubmit={event=>{event.preventDefault();getProductos(null,facturaSelectAddItemsSelect?facturaSelectAddItemsSelect.id_sucursal:false)}}>
                <input type="text" className="form-control" placeholder="Buscar en Local..." ref={inputbuscarcentralforvincular} />
                
                <div className="input-group-prepend">
                    <span className="input-group-text">Productos Local</span>
                </div>
            </form>
            
            <table className="table">
                <thead>
                    <tr>
                        <td></td>
                        <th>C. Alterno</th>
                        <th>C. Barras</th>
                        <th>Unidad</th>
                        <th>Descripción</th>
                        <th>Base</th>
                        <th>Venta</th>
                        <th>Categoría/Proveedor</th>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                {productos.length?productos.filter(e=>e.id_sucursal==facturaSelectAddItemsSelect.id_sucursal).map(e=>
                    <tr key={e.id} data-id={e.id} className="pointer align-middle">
                        
                        
                        <td> <button className="btn btn-outline-success" onClick={()=>linkproductocentralsucursal(e.idinsucursal,e.id_sucursal)}><i className="fa fa-link fa-2x"></i> <br /> #{e.idinsucursal}</button></td>
                        <td>{e.codigo_proveedor}</td>
                        <td>{e.codigo_barras}</td>
                        <td>{e.unidad}</td>
                        <td>{e.descripcion}</td>
                        <td>{e.precio_base}</td>
                        <td className="text-success">{e.precio}</td>
                        <td>{e.id_categoria}/{e.id_proveedor}</td>
                        <td>{e.sucursal.codigo}</td>
                    </tr>
                ):null}
                </tbody>
            </table>
        </div>
    )
}