import { useEffect, useState } from "react";
export default function ModalNuevoEfectivo({
    setopenModalNuevoEfectivo,
    setControlEfec,
    catselect,
    setcontrolefecNewConcepto,
    controlefecNewFecha,
    setcontrolefecNewFecha,
    controlefecNewConcepto,
    controlefecNewMonto,
    setcontrolefecNewMonto,
    controlefecNewMontoMoneda,
    setcontrolefecNewMontoMoneda,
    controlefecNewCategoria,
    setcontrolefecNewCategoria,
    categoriasCajas,
    controlefecSelectGeneral,
    setcontrolefecSelectGeneral,
    moneda,
    number,
    personalNomina,
    allProveedoresCentral,
    alquileresData,
    getAllProveedores,
    getAlquileres,
    getNomina,
    sucursalesCentral,

    transferirpedidoa,
    settransferirpedidoa,
    dolar,
    peso,
    getSucursales,
    formatAmount,
    qbuscarcat,
    setqbuscarcat,
    colorsGastosCat,
}){
    const [showtranscajatosucursal,setshowtranscajatosucursal] = useState(false)

    const [buscadorAlquiler, setbuscadorAlquiler] = useState("")
    const [buscadorProveedor, setbuscadorProveedor] = useState("")
    const [buscadorPersonal, setbuscadorPersonal] = useState("")

    const [selectpersona, setselectpersona] = useState("")
    const [selectcargopersona, setselectcargopersona] = useState("")
    const [sumprestamos, setsumprestamos] = useState("")
    const [sumcreditos, setsumcreditos] = useState("")
    const [lastpago, setlastpago] = useState("")
    const [selectpersonapagosmespasado, setselectpersonapagosmespasado] = useState("")
    const [maxpagopersona, setmaxpagopersona] = useState(0)

    const [maxpagoalquiler, setmaxpagoalquiler] = useState(0)

   

    
    return (
        <form onSubmit={event=>{event.preventDefault();setControlEfec()}} className="container">
           

            <div className="card p-3 form-group mb-2">
                <span className="form-label fw-bold fs-4">Descripción</span>
                {catselect.indexOf("NOMINA")===-1 && catselect.indexOf("PAGO PROVEEDOR")===-1 && catselect.indexOf("ALQUILER")===-1?
                    <input type="text" className="form-control form-control-lg" value={controlefecNewConcepto} onChange={e=>setcontrolefecNewConcepto(e.target.value)} placeholder="Descripción" />

                :   
                    <>
                            {catselect.indexOf("ALQUILER")!==-1?
                            <div className="input-group">
                                <select type="text" className="form-control"
                                    value={controlefecNewConcepto} 
                                    onChange={e=>{
                                        let val = e.target.value
                                        setcontrolefecNewConcepto(val)
                                        let matchid = val.split("=")[2]
                                        let match = alquileresData.filter(e=>e.id==matchid)[0]
                                        //setcontrolefecNewMonto(match.monto)
                                        //setmaxpagoalquiler(match.monto)
                                        //setcontrolefecNewMontoMoneda("dolar")

                                    }} >
                                        <option value="">-</option>
                            
                                        {alquileresData.length?
                                            alquileresData.map(e=><option value={"PAGO ALQUILER="+e.descripcion+"="+e.id} key={e.id}>PAGO ALQUILER: {e.descripcion}</option>)
                                        :null}
                            
                                </select>
                                {/* <input type="text" className="form-control" placeholder="Buscar Alquiler..." value={buscadorAlquiler} onChange={e=>setbuscadorAlquiler(e.target.value)} /> */}

                                <button type="button" className={("btn btn-success")} onClick={()=>getAlquileres()}><i className="fa fa-search"></i></button>
                            </div>
                        :null}

                        {catselect.indexOf("PAGO PROVEEDOR")!==-1?
                            <>
                                <div className="input-group">
                                    <input type="text" className="form-control" placeholder="Buscar proveedor..." value={buscadorProveedor} onChange={e=>setbuscadorProveedor(e.target.value)} />
                                    <button type="button" className={("btn btn-success")} onClick={()=>getAllProveedores()}><i className="fa fa-search"></i></button>
                                </div>
                                <div className="card card-personal h-400px table-responsive">

                                    <ul className="list-group">
                                        {allProveedoresCentral.filter(e=>!buscadorProveedor?true: (e.descripcion.toLowerCase().indexOf(buscadorProveedor.toLowerCase())!==-1) ).map(e=>
                                                
                                            <li key={e.id} className={"list-group-item "+(controlefecNewConcepto==("PAGO PROVEEDOR="+e.descripcion+"="+e.id)?" active pointer ":"")} onClick={()=>{
                                                let val = "PAGO PROVEEDOR="+e.descripcion+"="+e.id
                                                setcontrolefecNewConcepto(val)
                                            }}>{"PAGO PROVEEDOR="+e.descripcion}</li>
                                        )}
                                    </ul>
                                </div>

                            </>
                        :null}


                        {catselect.indexOf("NOMINA QUINCENA")!==-1 || catselect.indexOf("NOMINA ADELANTO")!==-1 || catselect.indexOf("NOMINA ABONO")!==-1 || catselect.indexOf("NOMINA PRESTAMO")!==-1?
                            <>
                                <div className="input-group">
                                    <input type="text" className="form-control" placeholder="Buscar Personal..." value={buscadorPersonal} onChange={e=>setbuscadorPersonal(e.target.value)} />
                                    <button type="button" className={("btn btn-success")} onClick={()=>getNomina()}><i className="fa fa-search"></i></button>
                                </div>

                                <div className="card card-personal h-400px table-responsive">

                                    <ul className="list-group">
                                        {personalNomina.personal?personalNomina.personal.filter(e=> !buscadorPersonal?true: (e.nominanombre.toLowerCase().indexOf(buscadorPersonal.toLowerCase())!==-1) ).map(e=>{
                                            let palabra = ""
                                            if(catselect.indexOf("NOMINA QUINCENA")!==-1){palabra = "PAGO"} 
                                            if(catselect.indexOf("NOMINA ADELANTO")!==-1){palabra = "ADELANTO"} 
                                            if(catselect.indexOf("NOMINA ABONO")!==-1){palabra = "ABONO"} 
                                            if(catselect.indexOf("NOMINA PRESTAMO")!==-1){palabra = "PRESTAMO"}
                                            let desc =  palabra+"="+e.nominacedula+"="+e.nominanombre  
                                            return <li key={e.id} className={"list-group-item "+(controlefecNewConcepto==(desc)?" active pointer ":"")} onClick={()=>{
                                                setcontrolefecNewConcepto(desc)
                                               // setcontrolefecNewMonto(e.maxpagopersona>e.quincena?e.quincena:e.maxpagopersona)
                                                setmaxpagopersona(e.maxpagopersona)

                                                setsumprestamos(e.sumprestamos)
                                                setsumcreditos(e.sumCreditos)
                                                setlastpago(e.mes)
                                                setselectpersonapagosmespasado(e.mespasado)

                                                setselectpersona(e.nominanombre)
                                                
                                                setselectcargopersona(e.cargo.cargosdescripcion)
                                                

                                                //setcontrolefecNewMontoMoneda("dolar")
                                            }}>{desc}</li>

                                        }):null}
                                    </ul>
                                </div>
                            </>
                        :null}
                    </>
                }
            </div>


            <div className="card p-3 form-group mb-2">
                <span className="form-label fw-bold fs-4 text-sinapsis">Monto </span>
                <div className="row">
                    <div className="col-3">
                        <select
                            className="form-control fs-2"
                            value={controlefecNewMontoMoneda}
                            onChange={e => {
                                
                                setcontrolefecNewMontoMoneda(e.target.value)
                            }}>
                            <option value="">-</option>
                                
                            <option value="dolar">$</option>
                            <option value="peso">PESO</option>
                            <option value="bs">Bs</option>
                            <option value="euro">EURO</option>
                        </select>

                    </div>
                    <div className="col">
                        <input type="text" className="form-control text-sinapsis fs-2" 
                        value={controlefecNewMonto}
                        onChange={e =>setcontrolefecNewMonto(e.target.value)}
                        placeholder={"Monto "+controlefecNewMontoMoneda}  />
                    </div>

                </div>
            </div>

            <div className="card p-3 form-group mb-2">
                <span className="form-label fw-bold fs-4">Fecha</span>
                <input type="date" className="form-control form-control-lg" value={controlefecNewFecha} onChange={e=>setcontrolefecNewFecha(e.target.value)}  />
            </div>

            <div className="card p-3 form-group mb-2">

                <span className="form-label fw-bold fs-4">Categoría</span>
                {categoriasCajas.length?categoriasCajas.filter(e=>{
                    return e.id==controlefecNewCategoria
                }).map(e=>
                    <ul key={e.id} className="list-group mb-2 w-50">
                        <li 
                        style={{backgroundColor:colorsGastosCat(e.id,"cat","color")}} 
                        className={"list-group-item d-flex align-items-center"} 
                        onClick={()=>{setcontrolefecNewCategoria(e.id)}}>
                            <i className="fa fa-check fa-2x text-success"></i>
                            {e.nombre}
                        </li>
                    </ul>
                ):null}
                
                <input type="text" className="form-control" placeholder="Buscar CATEGORÍA..." value={qbuscarcat} onChange={event=>setqbuscarcat(event.target.value)} />
                <div className="card card-personal h-200px table-responsive">

                    <ul className="list-group">
                        {categoriasCajas.length?categoriasCajas.filter(e=>{
                            if(qbuscarcat==""){return true}
                            else{
                                if ((e.nombre.toLowerCase()).indexOf(qbuscarcat.toLowerCase())!==-1) {return true}else{return false}
                            }
                        }).filter(e=>e.tipo==controlefecSelectGeneral).map(e=>
                            <li 
                            key={e.id} 
                            style={{backgroundColor:colorsGastosCat(e.id,"cat","color")}} 
                            className={"list-group-item d-flex align-items-center"} 
                            onClick={()=>{setcontrolefecNewCategoria(e.id)}}>
                                <input className="form-check-input m-0 me-3 fs-2" readOnly={true} type="radio" checked={controlefecNewCategoria==e.id?true:false}></input>
                                {e.nombre}
                            </li>
                        ):null}
                    </ul>

                    
                </div>
            </div>

            {selectpersona?
                <div className="p-3">
                    <table className="table">
                        <thead>
                            <tr>
                                <th>NOMBRES Y APELLIDOS</th>
                                <th>CARGO</th>
                                <th>MÁXIMO A COBRAR ESTE MES</th>
                                
                                <th>PRESTAMOS TOTALES</th>
                                <th>CRÉDITOS TOTALES</th>
                                <th>PAGOS QUINCENA (MES ACTUAL)</th>
                                <th>PAGOS QUINCENA (MES PASADO)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{selectpersona}</td>
                                <td>{selectcargopersona}</td>
                                <td className="text-success">{maxpagopersona}</td>
                                <td className="text-danger fs-5">{moneda(sumprestamos)}</td>
                                <td className="text-sinapsis fs-5">{moneda(sumcreditos)}</td>
                                <td>{moneda(lastpago)}</td>
                                <td>{moneda(selectpersonapagosmespasado)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            :null}
            
            {(showtranscajatosucursal || catselect.indexOf("TRANSFERENCIA TRABAJADOR")!=-1) && controlefecSelectGeneral==1?
                catselect.indexOf("EFECTIVO ADICIONAL")==-1 && catselect.indexOf("NOMINA ABONO")==-1 && catselect.indexOf("TRASPASO A CAJA CHICA")==-1?<>
                    <div className="w-100 d-flex justify-content-center mt-3">
                        <div className="input-group w-30">
                            <select className="form-control" value={transferirpedidoa} onChange={e => settransferirpedidoa(e.target.value)}>
                                <option value="">Transferir A</option>
                                {sucursalesCentral.map(e =>
                                <option value={e.id} key={e.id}>
                                    {e.nombre}
                                </option>
                                )}
                            </select>
                            <button className="btn btn-outline-success btn-sm" type="button" onClick={()=>setControlEfec(true)}><i className="fa fa-paper-plane"></i></button>

                        </div>
                    </div>
                </>:null
            :
                <div className="mb-3 d-flex justify-content-center">
                    <button className={"btn btn-"+(controlefecSelectGeneral==1?"success":"sinapsis")+" btn-lg"}><i className="fa fa-save"></i> GUARDAR</button>
                </div>
            }

        </form>
    )
}