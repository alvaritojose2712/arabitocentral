export default function Nominahome({
    children,
    subViewNomina,
    setsubViewNomina,
}){
    return(
        <div>
            <div className="btn-group mb-2">
                <button className={("btn ")+(subViewNomina=="pagos"?"btn-sinapsis":"")} onClick={()=>setsubViewNomina("pagos")}>Pagos</button>
                <button className={("btn ")+(subViewNomina=="gestion"?"btn-sinapsis":"")} onClick={()=>setsubViewNomina("gestion")}>Gestionar</button>
            </div>
            {children}
        </div>
    )
}