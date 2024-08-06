import { useState } from "react";

export default function CargargastosBancos({
    controlefecNewMontoMoneda,
    setcontrolefecNewMontoMoneda,

    gastosDescripcion,
    setgastosDescripcion,
    gastosMonto_dolar,
    setgastosMonto_dolar,
    gastosMonto,
    setgastosMonto,
    gastosTasa,
    setgastosTasa,
    comisionpagomovilinterban,
    setcomisionpagomovilinterban,
    gastosBanco,
    setgastosBanco,
    gastosFecha,
    setgastosFecha,
    qbuscarcat,
    setqbuscarcat,
    qNomina,
    setqNomina,
    gastosBeneficiario,
    setgastosBeneficiario,
    qSucursal,
    setqSucursal,
    opcionesMetodosPago,
    categoriasCajas,
    modeEjecutor,
    setmodeEjecutor,
    nominaData,
    listBeneficiario,
    getSucursales,
    sucursales,
    saveNewGasto,
    modeMoneda,
    setmodeMoneda,
    colorsGastosCat,
    iscomisiongasto,
    setiscomisiongasto,
    moneda,
    removeMoneda,
    gastosCategoria,
    formatAmount,
    setgastosCategoria,
    getPersonal,
    addBeneficiarioList,


    setcontrolbancoQ,
    controlbancoQ,
    setcontrolbancoQCategoria,
    controlbancoQCategoria,
    setcontrolbancoQDesde,
    controlbancoQDesde,
    setcontrolbancoQHasta,
    controlbancoQHasta,
    controlbancoQBanco,
    setcontrolbancoQBanco,
    controlbancoQSiliquidado,
    setcontrolbancoQSiliquidado,
    movBancosData,
    getMovBancos,
    controlbancoQSucursal,
    setcontrolbancoQSucursal,
    colors,
    colorSucursal,
    number
    
}){
    const [newregistro, setnewregistro] = useState(false)

    const setComisionFija = () => {
        let total = parseFloat(removeMoneda(gastosMonto))
        let fijo = parseFloat(window.prompt("ESCRIBA EL MONTO FIJO DE COMISIÓN"))
        if (total && fijo && gastosTasa) {
            setcomisionpagomovilinterban(((fijo*100)/total).toFixed(4))
        }
        

    }

    const setcontrolefecNewMontoMonedaFun = () => {
        if (gastosBanco=="EFECTIVO") {
            if (controlefecNewMontoMoneda=="") {
                setcontrolefecNewMontoMoneda("dolar")
            }
            if (controlefecNewMontoMoneda=="dolar") {
                setcontrolefecNewMontoMoneda("bs")
            }
            if (controlefecNewMontoMoneda=="bs") {
                setcontrolefecNewMontoMoneda("peso")
            }
            if (controlefecNewMontoMoneda=="peso") {
                setcontrolefecNewMontoMoneda("dolar")
            }
        }
    }
    return (
    <div className="container-fluid">
       {/*  <div className="text-center p-3">
            {newregistro?
                <button onClick={()=>setnewregistro(false)} className="btn btn-outline-sinapsis">OCULTAR NUEVO MOVIMIENTO <i className="fa fa-arrow-up"></i> </button>
                :
                <button onClick={()=>setnewregistro(true)} className="btn btn-outline-success ">NUEVO MOVIMIENTO <i className="fa fa-arrow-down"></i></button>
            }
        </div> */}

        
        <div className="container mb-3">
            <div className="was-validated">
                <div className="card p-3 form-group mb-2">
                    <span className="form-label fw-bold fs-4">Descripción</span>
                    <input type="text" className="form-control form-control-lg" value={gastosDescripcion} onChange={e=>setgastosDescripcion(e.target.value)} placeholder="Descripción" required={true}/>
                </div>

                <div className="card p-3 form-group mb-2">
                    <div className="row">
                        <div className="col">
                            <span className="form-label fw-bold fs-4">Método</span>
                            <select className="form-control form-control-lg" 
                            value={gastosBanco} 
                            onChange={e=>{
                                let val = e.target.value
                                setgastosBanco(val)

                                if (val==="EFECTIVO") {
                                    setcontrolefecNewMontoMoneda("dolar")
                                    setgastosTasa(1)
                                }else{
                                    setcontrolefecNewMontoMoneda("")
                                    setgastosTasa("")

                                }
                            }} required={true}>
                                <option value="">-</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                                {opcionesMetodosPago.map(e=>
                                    <option value={e.codigo} key={e.id}>{e.descripcion}</option>
                                )}
                            </select>
                        </div>
                        <div className="col">
                            <span className="form-label fw-bold fs-4">Fecha</span>
                            <input type="date" className="form-control form-control-lg" value={gastosFecha} onChange={e=>setgastosFecha(e.target.value)} required={true} />
                        </div>
                    </div>
                </div>

                {modeMoneda=="dolar"?
                    <div className="card p-3 form-group mb-2">
                        <span className="form-label fw-bold fs-4 text-success">Monto $</span>
                        <div className="input-group">
                            <input type="text" className="form-control text-success fs-2" value={gastosMonto_dolar} onChange={e=>setgastosMonto_dolar(formatAmount(e.target.value,"$ "))} placeholder="Monto $" required={true}/>
                            {/* <button className="btn btn-sinapsis" type="button" onClick={()=>setmodeMoneda("bs")}><i className="fa fa-refresh"></i> Bs</button> */}
                        </div>
                    </div>
                :null}

                {modeMoneda=="bs"?
                    <div className="card p-3 form-group mb-2">
                        <div className="mb-3">
                            <span className="fw-bold fs-4 text-sinapsis">
                                Monto
                            </span>
                            <span className="fw-bold fs-3 pointer text-success" onClick={()=>setcontrolefecNewMontoMonedaFun()}>
                                {controlefecNewMontoMoneda==""?" BANCO":
                                <>
                                    {" EFECTIVO "+controlefecNewMontoMoneda.toUpperCase()} <i className="fa fa-refresh"></i>
                                </>
                                } 
                            </span>

                        </div>
                        <div className="row">
                            <div className="col">
                                <div className="input-group">
                                    {/* <select
                                        className="form-control fs-2"
                                        value={controlefecNewMontoMoneda}
                                        onChange={e => {
                                            let val = e.target.value
                                            if (gastosBanco=="EFECTIVO" && val!="") {
                                                setcontrolefecNewMontoMoneda(val)
                                            }
                                        }}>
                                        <option value="">BANCO</option>
                                        <option value="dolar">DOLAR</option>
                                        <option value="peso">PESO</option>
                                        <option value="bs">BOLÍVAR</option>
                                        <option value="euro">EURO</option>
                                    </select> */}
                                    <input type="text" className="form-control text-sinapsis fs-2" value={gastosMonto} onChange={e=>setgastosMonto(formatAmount(e.target.value,""))} 
                                    placeholder={"Monto" + (controlefecNewMontoMoneda==""?" BANCO":" EFECTIVO "+controlefecNewMontoMoneda.toUpperCase())}
                                    required={true} />
                                </div>
                            </div>
                            <div className="col-3">
                                <div className="input-group">
                                    <input type="text" className="form-control text-sinapsis fs-2" value={gastosTasa} 
                                    disabled={gastosBanco=="EFECTIVO"?true:false}
                                    onChange={e=>{
                                        let val = e.target.value
                                        setgastosTasa(val)
                                    }} 
                                    placeholder="Tasa" required={true} />
                                    {/* <button className="btn btn-success" type="button" onClick={()=>setmodeMoneda("dolar")}><i className="fa fa-refresh"></i> $</button> */}
                                </div>
                            </div>
                            <div className="col-md-auto text-right">
                                <small className="text-success fs-3 mt-2">{moneda(parseFloat(removeMoneda(gastosMonto))/parseFloat((gastosTasa)))} $</small>

                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                <div className="input-group w-50 mt-1">
                                    <button className={("btn btn-outline")+(iscomisiongasto==1?"-success":"-danger")} onClick={()=>setiscomisiongasto(iscomisiongasto==1?0:1)}>Genera Comisión</button>
                                    {iscomisiongasto?
                                        <>
                                            <input type="text" style={{border:"none"}} onDoubleClick={()=>setComisionFija()} className="form-control" size={4} placeholder="% Comión" value={comisionpagomovilinterban} onChange={event=>setcomisionpagomovilinterban(number(event.target.value))}/> %
                                        </>
                                    :null}
                                </div>
                            </div>
                            <div className="col text-right">
                                {iscomisiongasto?
                                    <small className="text-danger fs-3 mt-2">Bs. -{moneda(parseFloat(removeMoneda(gastosMonto))*(parseFloat(comisionpagomovilinterban)/100))}</small>
                                :null}
                            </div>
                        </div>
                    </div>
                :null}

                <div className="card p-3 form-group mb-2">
                    <span className="form-label fw-bold fs-4">Categoría</span>
                        {categoriasCajas.length?categoriasCajas.filter(e=>{
                            return e.id==gastosCategoria
                        }).map(e=>
                            <ul key={e.id} className="list-group mb-2 w-50">
                                <li 
                                style={{backgroundColor:colorsGastosCat(e.id,"cat","color")}} 
                                className={"list-group-item d-flex align-items-center"} 
                                onClick={()=>{setgastosCategoria(e.id)}}>
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
                            }).map(e=>
                                <li 
                                key={e.id} 
                                style={{backgroundColor:colorsGastosCat(e.id,"cat","color")}} 
                                className={"list-group-item d-flex align-items-center"} 
                                onClick={()=>{setgastosCategoria(e.id)}}>
                                    <input className="form-check-input m-0 me-3 fs-2" readOnly={true} type="radio" checked={gastosCategoria==e.id?true:false}></input>
                                    {e.nombre}
                                </li>
                            ):null}
                        </ul>

                        
                    </div>
                </div>

                <div className="card p-3 form-group mb-2">
                    <div className="mb-2">
                        <span className="form-label fw-bold fs-4">Asignar a </span>
                        <span className="m-1">
                            {modeEjecutor=="personal"?<span className="text-sinapsis" type="span" onClick={()=>setmodeEjecutor("sucursal")}><i className="fa fa-user fa-2x"></i> </span>:null}
                            {modeEjecutor=="sucursal"?<span className="text-success" type="span" onClick={()=>setmodeEjecutor("personal")}><i className="fa fa-home fa-2x"></i></span>:null}
                        </span>
                        
                        
                        {listBeneficiario.length?
                            <div className="border bg-light p-3 w-50">
                                {listBeneficiario.map(e=>
                                    <button key={e.id} className="btn mb-1 me-1" onClick={()=>addBeneficiarioList("del",e.id)} style={{backgroundColor:e.color?e.color:"coral"}} onDoubleClick={()=>addBeneficiarioList("del",e.id)}>
                                        {e.codigo?e.codigo:e.nominanombre}
                                    </button>	
                                )}
                            </div>
                        :null}
                    </div>


                        {modeEjecutor=="personal"?
                            <div className="">
                                    {/* <button className="btn btn-success" type="button" onClick={()=>addBeneficiarioList("add")}><i className="fa fa-arrow-right"></i></button>*/}
                                    {/* <select className={("form-select ")} 
                                    value={gastosBeneficiario} 
                                    onChange={e=>setgastosBeneficiario(e.target.value)} required={true}>
                                    <option value="">-Personal-</option>
                                    {nominaData.personal?nominaData.personal.length?nominaData.personal.map(e=>
                                        <option value={e.id} key={e.id}>{e.nominanombre} {e.nominacedula}</option>
                                        ):null:null}
                                        </select> */}

                                <div className="input-group">
                                    <form onSubmit={event=>{event.preventDefault();getPersonal()}} className="input-group">
                                        <input type="text" className="form-control is-invalid" value={qNomina} onChange={e=>setqNomina(e.target.value)} placeholder="Buscar PERSONAL..." />
                                        <button className="btn btn-success" type="submit"> <i className="fa fa-search"></i> </button>
                                    </form>
                                </div>
                                <div className="card card-personal h-200px table-responsive">
                                    <ul className="list-group">
                                        {nominaData.personal?nominaData.personal.length?nominaData.personal.map(e=>
                                            <li 
                                            key={e.id} 
                                            className={"list-group-item "} 
                                            onClick={()=>{setgastosBeneficiario(e.id);addBeneficiarioList("add",e.id)}}>
                                                <input className="form-check-input m-0 me-3 fs-2" type="radio" checked={listBeneficiario.filter(ee=>ee.id==e.id).length?true:false}></input>
                                                {e.nominanombre} {e.nominacedula}
                                            </li>
                                        ):null:null}
                                    </ul>
                                </div>
                            </div>
                        :null}

                        {modeEjecutor=="sucursal"?
                            <div>
                                <div className="input-group">
                                    
                                    <form onSubmit={event=>{event.preventDefault();getSucursales(qSucursal)}} className="input-group">
                                        <input type="text" className="form-control is-invalid" value={qSucursal} onChange={e=>{setqSucursal(e.target.value);getSucursales(e.target.value)}} placeholder="Buscar SUCURSALES..." />
                                    </form>
                                </div>
                                <div className="card card-personal h-200px table-responsive">
                                    <ul className="list-group">
                                        {sucursales.length?sucursales.map(e=>
                                            <li 
                                            key={e.id} 
                                            className={"list-group-item "+(listBeneficiario.filter(ee=>ee.id==e.id).length?" active pointer ":"")} 
                                            onClick={()=>{setgastosBeneficiario(e.id);addBeneficiarioList("add",e.id)}}>{e.codigo}</li>
                                        ):null}
                                    </ul>
                                </div>
                            </div>
                        :null}
                        
                    

                </div>

                <div className="text-center">
                    <button className="btn btn-success btn-lg" onClick={()=>saveNewGasto()}><i className="fa fa-save"></i> GUARDAR</button>
                </div>
            </div>	
        </div>

    </div>	 
    )
}