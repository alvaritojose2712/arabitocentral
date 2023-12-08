export default function NavInventario({
    subViewInventario,
    setsubViewInventario,
}){
    return (
        <div className="btn-group mb-3">
            <button className={("btn btn")+(subViewInventario=="gestion"?"":"-outline")+("-success")} onClick={()=>setsubViewInventario("gestion")}>GESTIÓN</button>
            <button className={("btn btn")+(subViewInventario=="departamentos"?"":"-outline")+("-success")} onClick={()=>setsubViewInventario("departamentos")}>DEPARTAMENTOS</button>
            <button className={("btn btn")+(subViewInventario=="catgeneral"?"":"-outline")+("-success")} onClick={()=>setsubViewInventario("catgeneral")}>CATEGORÍA GENERAL</button>
            <button className={("btn btn")+(subViewInventario=="marcas"?"":"-outline")+("-success")} onClick={()=>setsubViewInventario("marcas")}>MARCAS</button>
            
        </div>
    )
}