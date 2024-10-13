export default function Comprasmenufactsdigital({
    viewmainPanel,
    setviewmainPanel,
}){
    return <div className="text-center">
        <div className="btn-group mb-2">
            <button className={("fs-4 btn btn")+(viewmainPanel=="compras"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("compras")}> <i className="fa fa-arrow-left"></i> </button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="cargarfactsdigitales"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("cargarfactsdigitales")}>FACTURAS <span className="badge bg-secondary">1</span></button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="cargarfactsitems"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("cargarfactsitems")}>ITEMS <span className="badge bg-secondary">2</span></button>
           {/*  <button className={("fs-2 btn btn")+(viewmainPanel=="comprasrevision"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("comprasrevision")}>REVISIÓN <span className="badge bg-secondary">3</span></button> */}
            <button className={("fs-2 btn btn")+(viewmainPanel=="distribuirfacts"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("distribuirfacts")}>DISTRIBUIR <span className="badge bg-secondary">3</span></button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="pedidos"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("pedidos")}>TRANSFERENCIAS <span className="badge bg-secondary">4</span></button>
        </div>
    </div>
}