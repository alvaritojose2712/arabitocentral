export default function Comprasmenufactsdigital({
    viewmainPanel,
    setviewmainPanel,
}){
    return <div className="text-center">
        <div className="btn-group mb-2">
            <button className={("fs-2 btn btn")+(viewmainPanel=="cargarfactsdigitales"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("cargarfactsdigitales")}>FACTURAS</button>
            <button className={("fs-2 btn btn")+(viewmainPanel=="cargarfactsitems"?"":"-outline")+("-sinapsis")} onClick={()=>setviewmainPanel("cargarfactsitems")}>ITEMS</button>
        </div>
    </div>
}