export default function Cuentasporpagar({
    moneda,
    getsucursalDetallesData,
    setsucursalDetallesData,
    sucursalDetallesData,
    getSucursales,
    sucursales,
    number,

    setqcuentasPorPagar,
    qcuentasPorPagar,
    selectCuentaPorPagarProveedorDetalles,
    selectCuentaPorPagarId,
    setSelectCuentaPorPagarId,

    setviewmainPanel,
}){
    return (
        <div>
            <form onSubmit={getsucursalDetallesData} className="input-group mb-2">
                <input type="text" className="form-control" placeholder="Buscar proveedor" onChange={e=>setqcuentasPorPagar(e.target.value)} value={qcuentasPorPagar} />
                <button className="btn btn-success"><i className="fa fa-search"></i></button>
            </form>
            {
                sucursalDetallesData.cuentasporpagar?sucursalDetallesData.cuentasporpagar.length
                ? sucursalDetallesData.cuentasporpagar.map( (e,i) =>
                    <div 
                    key={e.id}
                    onClick={()=>selectCuentaPorPagarProveedorDetalles(e.id)}
                    className={("bg-light")+" text-secondary card mb-3 pointer shadow"}>
                        <div className="card-header flex-row justify-content-between">
                            <div className="d-flex justify-content-between">
                                <div className="w-50">
                                    <b>{e.rif}</b><br/>
                                    <small className="fst-italic">{e.descripcion}</small>
                                </div>
                                <div className="w-50 text-right">
                                    {
                                        e.balance!=0?
                                            <>
                                                <span className={"h3 text-"+(e.balance<0?"danger":"success")}>$ <b>{moneda(e.balance)}</b></span>
                                            </>
                                        :null
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                )
                : null : null
                
            } 

            <button className="btn boton-fijo-inferiorizq btn-sinapsis" onClick={()=>setviewmainPanel("proveedores")} type="button">
                <i className="fa fa-plus"></i>
            </button>          
        </div>
    )
}