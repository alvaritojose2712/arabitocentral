export default function Comprasmenufactsdigital({
    viewmainPanel,
    setviewmainPanel,
}){
    return <div className="text-center">
        <div className="btn-group mb-2">
            <button className={("fs-4 btn btn")+(viewmainPanel=="compras"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("compras")}> <i className="fa fa-arrow-left"></i> </button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="cargarfactsdigitales"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("cargarfactsdigitales")}>FACTURAS 1</button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="cargarfactsitems"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("cargarfactsitems")}>ITEMS 2</button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="comprasrevision"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("comprasrevision")}>REVISIÓN 3</button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="distribuirfacts"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("distribuirfacts")}>DISTRIBUIR 4</button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="pedidos"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("pedidos")}>PEDIDOS 5</button>
        </div>
    </div>
}