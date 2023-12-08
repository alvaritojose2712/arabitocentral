import { useEffect } from "react";
export default function Gestioninventario({
    setporcenganancia,
    productosInventario,
    qBuscarInventario,
    setQBuscarInventario,
    type,

    changeInventario,

    Invnum,
    setInvnum,
    InvorderColumn,
    setInvorderColumn,
    InvorderBy,
    setInvorderBy,
    inputBuscarInventario, 
    guardarNuevoProductoLote,

    proveedoresList,
    number,
    refsInpInvList,
    buscarInventario,
    categorias,
    marcas,
    catGenerals,

    getMarcas,
    getCatGenerals,
    getCategorias,
}){

    useEffect(()=>{
        getMarcas()
        getCatGenerals()
        getCategorias()
    },[])
    return (
        <div className="container-fluid p-0">
            <form className="input-group" onSubmit={e=>{e.preventDefault();buscarInventario()}}>
                <div className="btn btn-success text-light" onClick={() => changeInventario(null, null, null, "add")}><i className="fa fa-plus"></i></div>
                    <input type="text" ref={inputBuscarInventario} className="form-control" placeholder="Buscar...(esc)" onChange={e => setQBuscarInventario(e.target.value)} value={qBuscarInventario} />

                    <select value={Invnum} onChange={e => setInvnum(e.target.value)}>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="2000">2000</option>
                    </select>
                    <select value={InvorderBy} onChange={e => setInvorderBy(e.target.value)}>
                        <option value="asc">Asc</option>
                        <option value="desc">Desc</option>
                    </select>
                    
                <div className="btn btn-success text-light" onClick={guardarNuevoProductoLote}><i className="fa fa-send"></i> (f1)</div>
            </form>
            
            <form ref={refsInpInvList} onSubmit={e=>e.preventDefault()}>
                <table className="table">
                    <thead>
                        <tr>
                            <th className="cell05 pointer"><span onClick={() => setInvorderColumn("id")}>ID</span></th>
                            <th className="cell1 pointer"><span onClick={() => setInvorderColumn("codigo_proveedor")}>C. Alterno</span></th>
                            <th className="cell1 pointer"><span onClick={() => setInvorderColumn("codigo_barras")}>C. Barras</span></th>
                            <th className="cell05 pointer"><span onClick={() => setInvorderColumn("unidad")}>Unidad</span></th>
                            <th className="cell2 pointer"><span onClick={() => setInvorderColumn("descripcion")}>Descripción</span></th>
                            <th className="cell15 pointer" >
                                <span onClick={() => setInvorderColumn("id_marca")}>Marca</span><br/>
                            </th>
                            <th className="cell05 pointer"><span onClick={() => setInvorderColumn("cantidad")}>Ct.</span></th>
                            <th className="cell1 pointer"><span onClick={() => setInvorderColumn("precio_base")}>Base</span></th>
                            <th className="cell15 pointer"><span onClick={() => setInvorderColumn("precio")}>Venta</span></th>

                            <th className="cell15 pointer" >
                                <span onClick={() => setInvorderColumn("id_categoria")}>Departamento</span><br/>
                            </th>

                            <th className="cell15 pointer" >
                                <span onClick={() => setInvorderColumn("id_catgeneral")}>Cat General</span><br/>
                            </th>
                            <th className="cell05 pointer"><span onClick={() => setInvorderColumn("iva")}>IVA</span></th>
                            <th className="cell1"></th>

                        </tr>
                    </thead>
                    <tbody>
                        {productosInventario.length?productosInventario.map((e,i)=>
                            <tr key={i} className="pointer" onDoubleClick={() => changeInventario(null, i, e.id, "update")}>
                                <td className="cell05">
                                    {e.id}
                                </td>
                                {type(e.type)?
                                <>
                                    <td className="cell1">{e.codigo_proveedor}</td>
                                    <td className="cell1">{e.codigo_barras}</td>
                                    <td className="cell05">{e.unidad}</td>
                                    <td className="cell2">{e.descripcion}</td>
                                    <td className="cell15">{e.marca.descripcion}</td>
                                    <th className="cell05">{e.cantidad}</th>
                                    <td className="cell1">{e.precio_base}</td>
                                    <td className="cell15 text-success">{e.precio}</td>
                                    <td className="cell15">{e.categoria.descripcion}</td>
                                    <td className="cell15">{e.catgeneral.descripcion}</td>
                                    <td className="cell05">{e.iva}</td>
                                </>

                                :
                                <>
                                    <td className="cell1">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.codigo_proveedor?"":e.codigo_proveedor}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "codigo_proveedor")}
                                            placeholder="codigo_proveedor..." />

                                    </td>
                                    <td className="cell1">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.codigo_barras?"":e.codigo_barras}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "codigo_barras")}
                                            placeholder="codigo_barras..." />

                                    </td>
                                    <td className="cell05">
                                        <select
                                            disabled={type(e.type)}
                                            className="form-control form-control-sm"
                                            value={!e.unidad?"":e.unidad}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "unidad")}
                                        >
                                            <option value="">--Select--</option>
                                            <option value="UND">UND</option>
                                            <option value="PAR">PAR</option>
                                            <option value="JUEGO">JUEGO</option>
                                            <option value="PQT">PQT</option>
                                            <option value="MTR">MTR</option>
                                            <option value="KG">KG</option>
                                            <option value="GRS">GRS</option>
                                            <option value="LTR">LTR</option>
                                            <option value="ML">ML</option>
                                        </select>
                                    </td>
                                    <td className="cell2">
                                        <textarea type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.descripcion?"":e.descripcion}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "descripcion")}
                                            placeholder="descripcion..."></textarea>

                                    </td>
                                    <td className="cell15">
                                        <select
                                            disabled={type(e.type)} 
                                            className="form-control form-control-sm"
                                            value={!e.id_marca?"":e.id_marca}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "id_marca")}
                                        >
                                            <option value="">--Select--</option>
                                            {marcas.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                            
                                        </select>
                                       
                                    </td>
                                    <td className="cell05">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.cantidad?"":e.cantidad}
                                            onChange={e => changeInventario(number(e.target.value), i, e.id, "changeInput", "cantidad")}
                                            placeholder="cantidad..." />

                                    </td>
                                    <td className="cell1">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.precio_base?"":e.precio_base}
                                            onChange={e => changeInventario(number(e.target.value), i, e.id, "changeInput", "precio_base")}
                                            placeholder="Costo..." />



                                    </td>
                                    <td className="cell15">
                                        <div className="input-group">
                                            <input type="text"
                                                disabled={type(e.type)} className="form-control form-control-sm"
                                                value={!e.precio?"":e.precio}
                                                onChange={e => changeInventario(number(e.target.value), i, e.id, "changeInput", "precio")}
                                                placeholder="Final..." />
                                            <span className="btn btn-sm" onClick={()=>setporcenganancia("list",e.precio_base,(precio)=>{
                                                    changeInventario(precio, i, e.id, "changeInput", "precio")
                                                })}>%</span>
                                        </div>

                                    </td>

                                    <td className="cell15">
                                        <select
                                            disabled={type(e.type)} 
                                            className="form-control form-control-sm"
                                            value={!e.id_categoria?"":e.id_categoria}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "id_categoria")}
                                        >
                                            <option value="">--Select--</option>
                                            {categorias.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                            
                                        </select>
                                       
                                    </td>

                                    <td className="cell15">
                                        <select
                                            disabled={type(e.type)} 
                                            className="form-control form-control-sm"
                                            value={!e.id_catgeneral?"":e.id_catgeneral}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "id_catgeneral")}
                                        >
                                            <option value="">--Select--</option>
                                            {catGenerals.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                            
                                        </select>
                                       
                                    </td>
                                    <td className="cell05">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.iva?"":e.iva}
                                            onChange={e => changeInventario(number(e.target.value,2), i, e.id, "changeInput", "iva")}
                                            placeholder="iva..." />

                                    </td>
                                </>
                                }
                                    <td className="cell1">
                                        <div className='d-flex justify-content-between'>
                                            {!e.type ?
                                                <>
                                                    <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, e.id, "delMode")}><i className="fa fa-trash"></i></span>
                                                    <span className="btn-sm btn btn-warning" onClick={() => changeInventario(null, i, e.id, "update")}><i className="fa fa-pencil"></i></span>
                                                </>
                                                : null}
                                            {e.type === "new" ?
                                                <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, e.id, "delNew")}><i className="fa fa-times"></i></span>
                                                : null}
                                            {e.type === "update" ?
                                                <span className="btn-sm btn btn-warning" onClick={() => changeInventario(null, i, e.id, "delModeUpdateDelete")}><i className="fa fa-times"></i></span>
                                                : null}
                                            {e.type === "delete" ?
                                                <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, e.id, "delModeUpdateDelete")}><i className="fa fa-arrow-left"></i></span>
                                                : null}
                                        </div>
                                    </td>
                                
                            </tr>
                        ):<tr>
                            <td>Sin resultados</td>
                        </tr>}
                    </tbody>
                </table>
            </form>
        </div>    
    )
}