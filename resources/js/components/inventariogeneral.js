export default function Inventariogeneral({
    setinvsuc_q,
    invsuc_q,
    invsuc_num,
    setinvsuc_num,
    invsuc_orderBy,
    setinvsuc_orderBy,
    setinvsuc_orderColumn,

    inventariogeneralData,
    getInventarioGeneral,

    sucursales,
}){
    return (
        <div className="container-fluid">
            <div>
                <form className="input-group" onSubmit={event=>{getInventarioGeneral();event.preventDefault()}}>
                    <input type="text" className="form-control" placeholder="Buscar...(esc)" onChange={e => setinvsuc_q(e.target.value)} value={invsuc_q} />

                    <select value={invsuc_num} onChange={e => setinvsuc_num(e.target.value)} className="form-control">
                        <option value="25">Num.25</option>
                        <option value="50">Num.50</option>
                        <option value="100">Num.100</option>
                        <option value="500">Num.500</option>
                        <option value="2000">Num.2000</option>
                        <option value="10000">Num.100000</option>
                    </select>
                    <select value={invsuc_orderBy} onChange={e => setinvsuc_orderBy(e.target.value)} className="form-control">
                        <option value="asc">Orden Asc</option>
                        <option value="desc">Orden Desc</option>
                    </select>
                    <button className="btn btn-success"><i className="fa fa-search"></i></button>
                </form>
            </div>
            <table className="table">
                <thead>
                    <tr>
                        <th className="pointer"><span>SUCURSAL</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("id")}>ID in SUCURSAL</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("codigo_proveedor")}>C. Alterno</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("codigo_barras")}>C. Barras</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("unidad")}>Unidad</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("descripcion")}>Descripción</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("cantidad")}>Ct.</span>/ <span onClick={() => setinvsuc_orderColumn("push")}>Inventario</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("precio_base")}>Base</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("precio")}>Venta </span></th>
                        <th className="pointer" >
                            <span onClick={() => setinvsuc_orderColumn("id_categoria")}>
                                Categoría
                            </span>
                            <br/>
                            <span onClick={() => setinvsuc_orderColumn("id_proveedor")}>
                                Proveedor
                            </span>
                        </th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("iva")}>IVA</span></th>
                        <th className="">ACTUALIZACIÓN</th>

                    </tr>
                </thead>

                    {inventariogeneralData?
                        inventariogeneralData.data?
                            Object.entries(inventariogeneralData.data).map(e=>
                                <tbody key={e.id}>
                                    <tr>
                                        <th colSpan={13}>
                                            {e[0]}
                                        </th>
                                    </tr>
                                    {e[1].map(ee=>
                                        <tr key={ee.id}>
                                            <td></td>
                                            <td className="">{ee.sucursal.codigo}</td>
                                            <td className="">{ee.idinsucursal}</td>
                                            <td className="">{ee.codigo_proveedor}</td>
                                            <td className="">{ee.codigo_barras}</td>
                                            <td className="">{ee.unidad}</td>
                                            <td className="">{ee.descripcion}</td>
                                            <th className="">{ee.cantidad}</th>
                                            <td className="">{ee.precio_base}</td>
                                            <td className="text-success">{ee.precio}</td>
                                            <td className=""></td>
                                            <td className="">{ee.iva}</td>
                                            <td className="">{ee.updated_at}</td>
                                        </tr>

                                    )}
                                </tbody>
                            )
                        :null
                    :null}
            </table>
        </div>
    )
}