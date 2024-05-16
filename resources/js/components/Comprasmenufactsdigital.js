export default function Comprasmenufactsdigital({
    viewmainPanel,
    setviewmainPanel,
}){
    return <div className="text-center">
        <div className="btn-group mb-2">
            <button className={("fs-4 btn btn")+(viewmainPanel=="compras"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("compras")}> <i className="fa fa-arrow-left"></i> </button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="cargarfactsdigitales"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("cargarfactsdigitales")}>FACTURAS <i className="fa fa-paper-plane-o"></i></button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="cargarfactsitems"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("cargarfactsitems")}>ITEMS <i className="fa fa-list-alt"></i></button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="distribuirfacts"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("distribuirfacts")}>DISTRIBUIR <i className="fa fa-truck"></i> </button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="pedidos"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("pedidos")}>PEDIDOS <i className="fa fa-paper-plane"></i></button>
        </div>
    </div>
}