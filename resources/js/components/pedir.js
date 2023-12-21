export default function Pedir({
    productos,
    getProductos,
    moneda,
    qProductosMain,
    setQProductosMain,
    
    openSelectProvNewPedCompras,
    setopenSelectProvNewPedComprasCheck,
    openSelectProvNewPedComprasCheck,

    NewPedComprasSelectProd,
    setNewPedComprasSelectProd,
    subViewCompras,
    setsubViewCompras,

    precioxproveedor,
    selectPrecioxProveedorProducto,
    selectPrecioxProveedorProveedor,

    setselectPrecioxProveedorProducto,
    setselectPrecioxProveedorProveedor,
    
    selectPrecioxProveedorSave,
    
    qBuscarProveedor,
    setQBuscarProveedor,
    proveedoresList,
    getProveedores,

    selectPrecioxProveedorPrecio,
    setselectPrecioxProveedorPrecio,
    getPrecioxProveedor,
}){

    let nameproveedorFilter = proveedoresList.filter(provee=>provee.id==selectPrecioxProveedorProveedor)
    let nameproveedor = "--No seleccionado--"
    if (nameproveedorFilter.length) {
        nameproveedor = nameproveedorFilter[0].descripcion
    }

    let nameproductoFilter = productos.filter(pro=>pro.id==selectPrecioxProveedorProducto)
    let nameproducto = "--No seleccionado--"
    if (nameproductoFilter.length) {
        nameproducto = nameproductoFilter[0].descripcion
    }

    
    const setselectPrecioxProveedorProductoFun = (id) => {
        setselectPrecioxProveedorProducto(id)
        getPrecioxProveedor(id)
    }

    return (
        <div>
            <div className="d flex justify-content-center">
                <div className="btn-group">
                    <button className="btn btn-outline-success" onClick={()=>setsubViewCompras("resumen")}>Resumen</button>
                    <button className="btn btn-outline-success" onClick={()=>setsubViewCompras("precioxproveedor")}>Precio por Proveedor</button>
                </div>
            </div>
            <hr />


            {subViewCompras=="resumen"?
                <div className='container-fluid'>
                    <div className="col">
                        <h4>Productos</h4>
                        <form onSubmit={e=>{e.preventDefault(); getProductos() }}>
                            <input
                                type="text"
                                className="form-control"
                                value={qProductosMain}
                                placeholder="Buscar... Presiona (ESC)"
                                onChange={(e) => setQProductosMain(e.target.value)}
                            />
                        </form>

                        
                        <table className="tabla-facturacion">
                            <thead>
                                <tr>
                                    <th className="cell2 pointer"
                                        data-valor="codigo_proveedor"
                                        >Cod.
                                    </th>
                                    <th className="cell4 pointer"
                                        data-valor="descripcion"
                                        >Desc.
                                    </th>
                                    <th className="cell1 pointer"
                                        data-valor="cantidad"
                                        >DEPOSITO Ct.
                                    </th>
                                    <th className="cell1 pointer"
                                        data-valor="unidad"
                                        >Unidad
                                    </th>
                                    <th className="cell2 pointer"
                                        data-valor="precio"
                                        >{/* Precio */}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {productos ? productos.length ? productos.map((e, i) =>

                                    <tr data-index={i} tabIndex="-1" className="" key={e.id}>
                                        <td data-index={i} onClick={(event)=>openSelectProvNewPedCompras(event)} className="pointer cell3">{e.codigo_barras}</td>

                                        <td data-index={i} onClick={(event)=>openSelectProvNewPedCompras(event)} className='pointer text-left pl-5 cell3'>
                                            {e.descripcion}
                                        </td>
                                        <td className="cell1">
                                            <button className='formShowProductos btn btn-sinapsis btn-sm w-50'>
                                                {e.cantidad.replace(".00", "")}
                                            </button>
                                        </td>
                                        <td className="cell1">{e.unidad}</td>
                                        <td className="cell2">
                                        {/*  <div className="container-fluid">
                                                <div className="row">
                                                    <div className="col-5 m-0 p-0">
                                                        <div className='btn-group w-100 h-100'>
                                                            <button type="button" className='m-0 btn-sm btn btn-success text-light fs-4 fw-bold'>
                                                                {moneda(e.precio)}
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div className="col m-0 p-0">
                                                        <div className='btn-group-vertical w-100 h-100'>
                                                            <button type="button" className='m-0 btn-sm btn btn-secondary text-light fw-bold fs-6'>Bs. {moneda(e.bs)} </button>
                                                            <button type="button" className='m-0 btn-sm btn btn-secondary text-light fw-bold'>Cop. {moneda(e.cop)}</button>
                                                        </div>
                                                    </div>

                                                </div>
                                                
                                            </div> */}
                                        </td>
                                    </tr>

                                ) : null : null}
                            </tbody>
                        </table>

                        {/* <div className="table-phone">
                            {
                                productos.length
                                    ? productos.map((e, i) =>
                                        <div
                                            key={e.id}
                                            data-index={i} onClick={openSelectProvNewPedCompras}
                                            className={(false ? "bg-sinapsis-light" : "bg-light") + " text-secondary card mb-3 pointer shadow"}>
                                            <div className="card-header flex-row justify-content-between">
                                                <div className="d-flex justify-content-between">
                                                    <div className="w-50">
                                                        <small className="fst-italic">{e.codigo_barras}</small><br />
                                                        <small className="fst-italic">{e.codigo_proveedor}</small><br />


                                                    </div>
                                                    <div className="w-50 text-right">

                                                        <span className="h6 text-muted font-italic">Bs. {moneda(e.bs)}</span>
                                                        <br />
                                                        <span className="h6 text-muted font-italic">COP. {moneda(e.cop)}</span>
                                                        <br />
                                                        <span className="h3 text-success">{moneda(e.precio)}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="card-body d-flex justify-content-between">
                                                <div className="">
                                                    <span
                                                        className="card-title "
                                                    ><b>{e.descripcion}</b></span>
                                                </div>
                                                <p className="card-text p-1">
                                                    Ct. <b className="h3">{e.cantidad}</b>
                                                </p>
                                            </div>
                                        </div>
                                    )
                                    : <div className='h3 text-center text-dark mt-2'><i>¡Sin resultados!</i></div>
                            }
                        </div> */}
                    </div>

                    {openSelectProvNewPedComprasCheck&& 
                        <div className="col">
                            <h4>Proveedores</h4>


                        </div>
                    }
                </div>  
            :null}


            {subViewCompras=="precioxproveedor"?

                <div className="container-fluid">
                    <div className="row">
                        <div className="col-4">
                            <h4>Productos</h4>
                            <form onSubmit={e=>{e.preventDefault(); getProductos() }}>
                                <input
                                    type="text"
                                    className="form-control"
                                    value={qProductosMain}
                                    placeholder="Buscar... Presiona (ESC)"
                                    onChange={(e) => setQProductosMain(e.target.value)}
                                />
                            </form>
                            <table className="tabla-facturacion">
                                <thead>
                                    <tr>
                                        <th className="cell2 pointer"
                                            data-valor="codigo_proveedor"
                                            >CODIGO
                                        </th>
                                        <th className="cell4 pointer"
                                            data-valor="descripcion"
                                            >DESCRIPCIÓN
                                        </th>
                                        <th className="cell1 pointer"
                                            data-valor="cantidad"
                                            >DEPOSITO CT
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {productos ? productos.length ? productos.map((e, i) =>

                                        <tr data-index={i} tabIndex="-1" key={e.id} className={selectPrecioxProveedorProducto==e.id?"bg-sinapsis-light":null}>
                                            <td data-index={i} onClick={(event)=>setselectPrecioxProveedorProductoFun(e.id)} className="pointer cell3">{e.codigo_barras}</td>
                                            

                                            <td data-index={i} onClick={(event)=>setselectPrecioxProveedorProductoFun(e.id)} className='pointer text-left pl-5 cell3'>
                                                {e.descripcion+" "+e.marca.descripcion}
                                            </td>
                                            <td className="cell1">
                                                <button className='formShowProductos btn btn-sinapsis btn-sm w-50'>
                                                    {e.cantidad.replace(".00", "")}
                                                </button>
                                            </td>
                                        </tr>

                                    ) : null : null}
                                </tbody>
                            </table>
                        </div>

                        <div className="col-4">
                            <h4>Proveedores</h4>
                            <form onSubmit={e=>{e.preventDefault(); getProveedores() }}>
                                <input
                                    type="text"
                                    className="form-control"
                                    value={qBuscarProveedor}
                                    placeholder="Buscar... Presiona (ESC)"
                                    onChange={(e) => setQBuscarProveedor(e.target.value)}
                                />
                            </form>
                            <table className="tabla-facturacion">
                                <thead>
                                    <tr>
                                        <th className="cell2 pointer"
                                            data-valor="codigo_proveedor"
                                            >RIF
                                        </th>
                                        <th className="cell4 pointer"
                                            data-valor="descripcion"
                                            >NOMBRE
                                        </th>
                                        <th className="cell1 pointer"
                                            data-valor="cantidad"
                                            >UBICACION
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {proveedoresList ? proveedoresList.length ? proveedoresList.map((e, i) =>
                                        <tr data-index={i} tabIndex="-1" className={selectPrecioxProveedorProveedor==e.id?"bg-sinapsis-light":null} key={e.id}>
                                            <td data-index={i} onClick={()=>setselectPrecioxProveedorProveedor(e.id)} className="pointer cell3">{e.rif}</td>

                                            <td data-index={i} onClick={()=>setselectPrecioxProveedorProveedor(e.id)} className='pointer text-left pl-5 cell3'>
                                                {e.descripcion}
                                            </td>
                                            
                                            <td className="cell1">{e.direccion}</td>
                                            
                                        </tr>

                                    ) : null : null}
                                </tbody>
                            </table>
                        </div>

                        <div className="col-3">
                            <h4>Precio por Proveedor</h4>

                            <div className="input-group">
                                <button className="btn">Pendiente</button>
                                <button className="btn btn-success">{nameproducto}</button>
                                <button className="btn btn-primary">{nameproveedor}</button>
                                <input type="text" className="form-control" placeholder='Precio' value={selectPrecioxProveedorPrecio} onChange={(e)=>setselectPrecioxProveedorPrecio(e.target.value)} />
                                <button className="btn btn-secondary" onClick={selectPrecioxProveedorSave}><i className="fa fa-send"></i></button>
                                
                            </div>
                            <table className="tabla-facturacion">
                                <thead>
                                    <tr>
                                        <th className="cell2 pointer"
                                            data-valor="codigo_proveedor"
                                            >PRODUCTO
                                        </th>
                                        <th className="cell4 pointer"
                                            data-valor="descripcion"
                                            >PROVEEDOR
                                        </th>
                                        <th className="cell1 pointer"
                                            data-valor="cantidad"
                                            >PRECIO
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {precioxproveedor ? precioxproveedor.length ? precioxproveedor.map((e, i) =>
                                        <tr data-index={i} tabIndex="-1" className="" key={e.id}>
                                            <td data-index={i} className="pointer cell3">{e.producto.descripcion}</td>

                                            <td data-index={i} className='pointer text-left pl-5 cell3'>
                                                {e.proveedor.descripcion}
                                            </td>
                                            
                                            <td className="cell1">{e.precio}</td>
                                        </tr>
                                    ) : null : null}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            :null}
        </div>
    )
}