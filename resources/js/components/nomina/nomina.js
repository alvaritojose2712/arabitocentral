export default function Nomina({
    subViewNominaGestion,
    setsubViewNominaGestion,
    children,
}){
    return(
    <div>
        <div className="d-flex justify-content-center">
            <div className="btn-group m-2">
                <button className={("btn ")+(subViewNominaGestion=="personal"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setsubViewNominaGestion("personal")}>Personal</button>
                <button className={("btn ")+(subViewNominaGestion=="cargos"?"btn-sinapsis":"btn-outline-sinapsis")} onClick={()=>setsubViewNominaGestion("cargos")}>Cargos</button>
            </div>
        </div>
        {children}
    </div>
    )
}