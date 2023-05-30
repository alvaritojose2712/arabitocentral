export default function Sucursallistcierre({
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
                                    <i className="fa fa-user"></i> <b>{e.numventastotal?e.numventastotal:null}</b> 
                                </button>
                            </div>
                        </div>
                        <div className="row mb-1">
                            <div className="col">
                                <button className="btn m-1 btn-outline-success w-100">
                                    D <br /><b>{e.debitototal?e.debitototal:null} </b>
                                </button>
                            </div>
                            <div className="col">
                                <button className="btn m-1 btn-outline-success w-100">
                                    E <br /><b>{e.efectivototal?e.efectivototal:null} </b>
                                </button>
                            </div>
                            <div className="col">
                                <button className="btn m-1 btn-outline-success w-100">
                                    T <br /><b>{e.transferenciatotal?e.transferenciatotal:null} </b>
                                </button>
                            </div>
                            <div className="col">
                                <button className="btn m-1 btn-outline-info w-100">
                                    Total <b>{e.total?e.total:null} </b>
                                </button>
                            </div>
                        </div>
                        <div className="row">
                            
                            <div className="col d-flex justify-content-end">
                                <button className="btn m-1 btn-success">
                                    <i className="fa fa-money"></i> <b>{e.gananciatotal?e.gananciatotal:null} </b>
                                </button>
                                <button className="btn m-1 btn-outline-success">
                                    <b>{e.porcentajetotal?e.porcentajetotal:null}</b> %
                                </button>
                                 
                            </div>
                        </div>
                    </div>  
                </div>  
            ):null}
        </div>
    )
}
