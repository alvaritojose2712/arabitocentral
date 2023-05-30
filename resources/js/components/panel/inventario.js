export default function Inventario({
    children,

    subViewInventario,
    setsubViewInventario,
}){

    return(
        <div>
            <div className="btn-group">
                <button className="btn btn-outline-sinapsis" onClick={()=>setsubViewInventario("gestion")}>Gestión</button>
                <button className="btn btn-outline-sinapsis" onClick={()=>setsubViewInventario("garantia")}>Garantías</button>
                <button className="btn btn-outline-sinapsis" onClick={()=>setsubViewInventario("pedidos")}>Pedidos</button>
                <button className="btn btn-outline-sinapsis" onClick={()=>setsubViewInventario("fallas")}>Fallas</button>
            </div>
            {children}
        </div>
    )
}

