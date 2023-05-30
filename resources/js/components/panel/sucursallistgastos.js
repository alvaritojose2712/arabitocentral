export default function SucursalListGastos({
    sucursalListData,
    sucursalSelect,
    setsucursalSelect,
}){
    return(
        <div className="">
            {sucursalListData.length?sucursalListData.map(e=>
                <div className="card mb-3 p-2 pointer" key={e.id} onClick={()=>setsucursalSelect(e.id)}>
                    <div className="container-fluid">
                        <div className="row">
                            <div className="col">
                                {e.codigo} - {e.nombre}
                            </div>
                            <div className="col d-flex justify-content-end">
                                <button className="btn m-1 btn-outline-arabito">
                                    <i className="fa fa-user"></i> <b>{e.gastototal?e.gastototal:null}</b> 
                                </button>
                            </div>
                        </div>
                        <div className="row mb-1">
                            <div className="col">
                                <button className="btn m-1 btn-outline-success w-100">
                                    Nómina <br /><b>{e.nomina?e.nomina:null} </b>
                                </button>
                            </div>
                        </div>
                    </div>  
                </div>  
            ):null}
        </div>
    )
}