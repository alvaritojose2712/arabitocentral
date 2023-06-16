export default function Index({
    children,
    subViewNomina,
    setsubViewNomina,
}){
    return(
        <div>
            <div className="btn-group mb-2">
                <button className={("btn ")+(subViewNomina=="pagos"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setsubViewNomina("pagos")}>Pagos</button>
                <button className={("btn ")+(subViewNomina=="gestion"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setsubViewNomina("gestion")}>Gestionar</button>
            </div>
            {children}
        </div>
    )
}