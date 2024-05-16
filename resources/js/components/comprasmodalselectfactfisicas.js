import { useEffect } from "react";

export default function ComprasCargarFactsFisica({
    selectFilecxp,
    setselectFilecxp,

    delFilescxp,
    getFilescxp,
    showFilescxp,

    dataFilescxp,
    setdataFilescxp,

    qnumfactFilescxp,
    setqnumfactFilescxp,

    qid_proveedorFilescxp,
    setqid_proveedorFilescxp,

    qid_sucursalFilescxp,
    setqid_sucursalFilescxp,

    qfechaFilescxp,
    setqfechaFilescxp,

    proveedoresList,
    sucursales,
    setviewmainPanel,
    colorSucursal,
    seleccionarFilecxpFun,
}){
    useEffect(()=>{
        getFilescxp()
    },[])
    
    return <>
        <div className="container">
            <div className="text-danger pointer mb-2" onClick={()=>setviewmainPanel("cargarfactsdigitales")}><i className="fa fa-arrow-left fa-2x"></i></div>
            <form className="input-group" onSubmit={event=>{event.preventDefault();getFilescxp()}}>
                <input type="text" className="form-control" onChange={event=>setqnumfactFilescxp(event.target.value)} value={qnumfactFilescxp} placeholder="Buscar Numfact..." />
                <input type="date" className="form-control" onChange={event=>setqfechaFilescxp(event.target.value)} value={qfechaFilescxp} />
                <select className="form-control" value={qid_proveedorFilescxp} onChange={e=>setqid_proveedorFilescxp(e.target.value)}>
                    <option value="">-TODOS LOS PROVEEDORES-</option>
                    {proveedoresList.map(e=>
                        <option key={e.id} value={e.id}>{e.descripcion}-{e.rif}</option>
                    )}
                </select>
                <select className="form-control form-control-lg" value={qid_sucursalFilescxp} onChange={e=>setqid_sucursalFilescxp(e.target.value)}>
                    <option value="">-SUCURSAL-</option>
                    {sucursales.map(e=>
                        <option key={e.id} value={e.id}>{e.codigo}</option>
                    )}
                </select>
                <button className="btn btn-success"><i className="fa fa-search"></i></button>
            </form>
            <table className="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>FECHA</th>
                        <th>NUM</th>
                        <th>PROVEEDOR</th>
                        <th>SUCURSAL</th>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    {dataFilescxp.cuentasporpagar_fisicas?dataFilescxp.cuentasporpagar_fisicas.length?dataFilescxp.cuentasporpagar_fisicas.map(e=>
                        <tr key={e.id} className={e.estado?"bg-success-superlight":"bg-danger-superlight"}>
                            <td className="align-middle">
                                <button className="btn btn-warning fs-2" onClick={()=>seleccionarFilecxpFun(e.id)}>
                                    <i className="fa fa-link"></i>
                                    {e.id}
                                </button>
                            </td>
                            <td className="align-middle">{e.created_at}</td>
                            <td className="align-middle">
                                <span className="w-100 btn fs-2 pointer fw-bolder btn-sinapsis">
                                    {e.numfact}
                                </span>
                            </td>
                            <th className="align-middle fs-4">{e.proveedor?e.proveedor.descripcion:null}</th>
                            <td className="align-middle">
                                <button className={"btn w-100 fw-bolder fs-3"} style={{backgroundColor:colorSucursal(e.sucursal? e.sucursal.codigo:"")}}>
                                    {e.sucursal? e.sucursal.codigo:""}
                                </button>
                            </td>
                            <td className="text-center">
                                <img src={e.ruta} width={200} onClick={()=>showFilescxp(e.ruta)} className="pointer"/>
                            </td>
                            <td>
                                <button className="btn btn-danger" onClick={()=>delFilescxp(e.id)} data-numfact={e.numfact}><i className="fa fa-times"></i></button>
                            </td>
                        </tr>
                    ):null:null}
                </tbody>
            </table>
        </div>
    </>
}