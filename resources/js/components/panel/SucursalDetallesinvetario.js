import { useEffect } from "react"

export default function SucursalDetallesinvetario({

    invsuc_itemCero,    
    setinvsuc_itemCero,

    invsuc_q,    
    setinvsuc_q,
    
    invsuc_exacto,    
    setinvsuc_exacto,
    
    invsuc_num,    
    setinvsuc_num,
    
    invsuc_orderColumn,    
    setinvsuc_orderColumn,
    
    invsuc_orderBy,    
    setinvsuc_orderBy,

    sucursalDetallesData,
    getsucursalDetallesData,

}){
   
    const getPorGanacia = (precio,base) => {
        try{
            let por = 0

            precio = parseFloat(precio)
            base = parseFloat(base)

            let dif = precio-base

            por = ((dif*100)/base).toFixed(2)
            if (por) {
                return (dif<0?"":"+")+por+"%"

            }else{
                return ""

            }
        }catch(err){
            return ""
        }
    } 

    return(
        <div>
            <div>
                <form className="input-group" onSubmit={getsucursalDetallesData}>
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
                        <th className="pointer">
                            <span onClick={() => setinvsuc_orderColumn("precio")}>Venta </span>
                        </th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("cantidad")}>Ct.</span>/ <span onClick={() => setinvsuc_orderColumn("push")}>Inventario</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("precio_base")}>Base</span></th>
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
                <tbody>

                    {sucursalDetallesData.length?sucursalDetallesData[0].codigo_barras?sucursalDetallesData.map(e=>
                        
                        <tr key={e.id}>
                            <td className="">{e.sucursal.codigo}</td>
                            <td className="">{e.idinsucursal}</td>
                            <td className="">{e.codigo_proveedor}</td>
                            <td className="">{e.codigo_barras}</td>
                            <td className="">{e.unidad}</td>
                            <td className="">{e.descripcion}</td>
                            <td className="text-success">
                                {e.precio}<br/>
                                <span className="text-success">
                                    {getPorGanacia(!e.precio?0:e.precio,!e.precio_base?0:e.precio_base)}
                                </span>
                                <br/>
                                <div className="btn-group w-100">
                                    <span className="btn btn-outline-success btn-sm" 
                                    data-id={e.id} 
                                    data-type="p1" 
                                    >P1.<br/>{e.precio1}</span>

                                    <span className="btn btn-outline-success btn-sm" 
                                    data-id={e.id} 
                                    data-type="p2" 
                                    >P2.<br/>{e.precio2}</span>
                                </div>
                            </td>
                            <th className="">{e.cantidad}</th>
                            <td className="">{e.precio_base}</td>
                            <td className=""></td>
                            <td className="">{e.iva}</td>
                            <td className="">{e.updated_at}</td>
                        </tr>
                        
                    ):null:null}
                </tbody>
            </table>
        </div>
    )
}