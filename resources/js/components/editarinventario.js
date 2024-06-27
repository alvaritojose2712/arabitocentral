import { useState, useEffect } from "react";
export default function Editarinventario({
    productosInventario,
    changeInventarioModificarDici,
    guardarmodificarInventarioDici,

    inputBuscarInventario,
    setQBuscarInventario,
    Invnum,
    type,
    setInvnum,
    InvorderBy,
    setInvorderBy,

    buscarInventario,
    qBuscarInventario,


    selectIdVinculacion, 
    setselectIdVinculacion,
    qvinculacion1, 
    setqvinculacion1,
    qvinculacion2, 
    setqvinculacion2,
    qvinculacion3, 
    setqvinculacion3,
    qvinculacion4, 
    setqvinculacion4,
    qvinculacionmarca, 
    setqvinculacionmarca,
    datavinculacion1, 
    setdatavinculacion1,
    datavinculacion2, 
    setdatavinculacion2,
    datavinculacion3, 
    setdatavinculacion3,
    datavinculacion4, 
    setdatavinculacion4,
    datavinculacionmarca, 
    setdatavinculacionmarca,
    inputselectvinculacion1, 
    setinputselectvinculacion1,
    inputselectvinculacion2, 
    setinputselectvinculacion2,
    inputselectvinculacion3, 
    setinputselectvinculacion3,
    inputselectvinculacion4, 
    setinputselectvinculacion4,
    inputselectvinculacionmarca, 
    setinputselectvinculacionmarca,
    inputselectvinculacion1General, 
    setinputselectvinculacion1General,
    inputselectvinculacion2General, 
    setinputselectvinculacion2General,
    inputselectvinculacion3General, 
    setinputselectvinculacion3General,
    inputselectvinculacion4General, 
    setinputselectvinculacion4General,
    inputselectvinculacionmarcaGeneral, 
    setinputselectvinculacionmarcaGeneral,

    inputselectvinculacion5,
    setinputselectvinculacion5,
    inputselectvinculacioncat,
    setinputselectvinculacioncat,
    inputselectvinculacioncatesp,
    setinputselectvinculacioncatesp,
    inputselectvinculacionproveedor,
    setinputselectvinculacionproveedor,
    inputselectvinculacionmaxct,
    setinputselectvinculacionmaxct,
    inputselectvinculacionminct,
    setinputselectvinculacionminct,
    inputselectvinculacion5General,
    setinputselectvinculacion5General,
    inputselectvinculacioncatGeneral,
    setinputselectvinculacioncatGeneral,
    inputselectvinculacioncatespGeneral,
    setinputselectvinculacioncatespGeneral,
    inputselectvinculacionproveedorGeneral,
    setinputselectvinculacionproveedorGeneral,
    inputselectvinculacionmaxctGeneral,
    setinputselectvinculacionmaxctGeneral,
    inputselectvinculacionminctGeneral,
    setinputselectvinculacionminctGeneral,


    qvinculacion5,
    setqvinculacion5,
    qvinculaciocat,
    setqvinculaciocat,
    qvinculaciocatesp,
    setqvinculaciocatesp,
    qvinculacioproveedor,
    setqvinculacioproveedor,
    qvinculaciomaxct,
    setqvinculaciomaxct,
    qvinculaciominct,
    setqvinculaciominct,
    qvinculacion5General,
    setqvinculacion5General,
    qvinculaciocatGeneral,
    setqvinculaciocatGeneral,
    qvinculaciocatespGeneral,
    setqvinculaciocatespGeneral,
    qvinculacioproveedorGeneral,
    setqvinculacioproveedorGeneral,
    qvinculaciomaxctGeneral,
    setqvinculaciomaxctGeneral,
    qvinculaciominctGeneral,
    setqvinculaciominctGeneral,
    datavinculacion5,
    setdatavinculacion5,
    datavinculaciocat,
    setdatavinculaciocat,
    datavinculaciocatesp,
    setdatavinculaciocatesp,
    datavinculacioproveedor,
    setdatavinculacioproveedor,
    datavinculaciomaxct,
    setdatavinculaciomaxct,
    datavinculaciominct,
    setdatavinculaciominct,
    newNombre5,
    setnewNombre5,
    newNombrecat,
    setnewNombrecat,
    newNombrecatesp,
    setnewNombrecatesp,
    newNombreproveedor,
    setnewNombreproveedor,
    newNombremaxct,
    setnewNombremaxct,
    newNombreminct,
    setnewNombreminct,


    getDatinputSelectVinculacion,
    saveCuatroNombres,

    qvinculacion1General,
    qvinculacion2General,
    qvinculacion3General,
    qvinculacion4General,
    qvinculacionmarcaGeneral,

    colorSucursal,

    qBuscarInventarioSucursal,
    setqBuscarInventarioSucursal,

    sucursales,
    sameCatValue,
}){
    
    const [stockminvaluegeneral, setstockminvaluegeneral] = useState("")
    const [stockmaxvaluegeneral, setstockmaxvaluegeneral] = useState("")

    useEffect(()=>{
        getDatinputSelectVinculacion()
    },[])

    useEffect(()=>{
        let fil = datavinculacion1.find(e=>e.nombre?e.nombre.toLowerCase().substr(0,qvinculacion1.length) == qvinculacion1.toLowerCase() :false)
        if (fil) {if (fil && qvinculacion1!="") {setinputselectvinculacion1(fil.nombre)}}else{setinputselectvinculacion1("")}
        if (!qvinculacion1) {
            setinputselectvinculacion1("")
        }
    },[qvinculacion1])

    useEffect(()=>{
        let fil = datavinculacion2.find(e=>e.nombre?e.nombre.toLowerCase().substr(0,qvinculacion2.length) == qvinculacion2.toLowerCase():false)
        if (fil) {if (fil && qvinculacion2!="") {setinputselectvinculacion2(fil.nombre)}}else{setinputselectvinculacion2("")}
        if (!qvinculacion2) {
            setinputselectvinculacion2("")
        }
    },[qvinculacion2])

    useEffect(()=>{
        let fil = datavinculacion3.find(e=>e.nombre?e.nombre.toLowerCase().substr(0,qvinculacion3.length) == qvinculacion3.toLowerCase():false)
        if (fil) {if (fil && qvinculacion3!="") {setinputselectvinculacion3(fil.nombre)}}else{setinputselectvinculacion3("")}
        if (!qvinculacion3) {
            setinputselectvinculacion3("")
        }
    },[qvinculacion3])

    useEffect(()=>{
        let fil = datavinculacion4.find(e=>e.nombre?e.nombre.toLowerCase().substr(0,qvinculacion4.length) == qvinculacion4.toLowerCase():false)
        if (fil) {if (fil && qvinculacion4!="") {setinputselectvinculacion4(fil.nombre)}}else{setinputselectvinculacion4("")}
        if (!qvinculacion4) {
            setinputselectvinculacion4("")
        }
    },[qvinculacion4])


    useEffect(()=>{
        let fil = datavinculacion5.find(e=>e.nombre?e.nombre.toLowerCase().substr(0,qvinculacion5.length) == qvinculacion5.toLowerCase():false)
        if (fil) {if (fil && qvinculacion5!="") {setinputselectvinculacion5(fil.nombre)}}else{setinputselectvinculacion5("")}
        if (!qvinculacion5) {
            setinputselectvinculacion5("")
        }
    },[qvinculacion5])


    useEffect(()=>{
        let fil = datavinculacionmarca.find(e=>e.descripcion?e.descripcion.toLowerCase().substr(0,qvinculacionmarca.length) == qvinculacionmarca.toLowerCase():false)
        if (fil) {if (fil && qvinculacionmarca!="") {setinputselectvinculacionmarca(fil.descripcion)}}else{setinputselectvinculacionmarca("")}
        if (!qvinculacionmarca) {
            setinputselectvinculacionmarca("")
        }
    },[qvinculacionmarca])


    ///////////////

    const selectAllIds = val => {
        if (val.length) {
            if (productosInventario.length) {
                let acumulateIds = []
                productosInventario.map(e=>{
                    acumulateIds.push(e.id)
                })
                setselectIdVinculacion(acumulateIds)
            }
        }else{
            setselectIdVinculacion([])

        }
    }
    useEffect(()=>{
        let fil = datavinculacion1.find(e=>e.nombre?e.nombre.toLowerCase().indexOf(qvinculacion1General.toLowerCase())!=-1:false)
        if (fil) {if (fil && qvinculacion1General!="") {selectAllIds(qvinculacion1General);setinputselectvinculacion1General(fil.nombre);setinputselectvinculacion1(fil.nombre)}}else{selectAllIds("");setinputselectvinculacion1("");setinputselectvinculacion1General("")}
        
    },[qvinculacion1General])

    useEffect(()=>{
        let fil = datavinculacion2.find(e=>e.nombre?e.nombre.toLowerCase().indexOf(qvinculacion2General.toLowerCase())!=-1:false)
        if (fil) {if (fil && qvinculacion2General!="") {selectAllIds(qvinculacion2General);setinputselectvinculacion2General(fil.nombre);setinputselectvinculacion2(fil.nombre)}}else{selectAllIds("");setinputselectvinculacion2("");setinputselectvinculacion2General("")}
        
    },[qvinculacion2General])

    useEffect(()=>{
        let fil = datavinculacion3.find(e=>e.nombre?e.nombre.toLowerCase().indexOf(qvinculacion3General.toLowerCase())!=-1:false)
        if (fil) {if (fil && qvinculacion3General!="") {selectAllIds(qvinculacion3General);setinputselectvinculacion3General(fil.nombre);setinputselectvinculacion3(fil.nombre)}}else{selectAllIds("");setinputselectvinculacion3("");setinputselectvinculacion3General("")}
        
    },[qvinculacion3General])

    useEffect(()=>{
        let fil = datavinculacion4.find(e=>e.nombre?e.nombre.toLowerCase().indexOf(qvinculacion4General.toLowerCase())!=-1:false)
        if (fil) {if (fil && qvinculacion4General!="") {selectAllIds(qvinculacion4General);setinputselectvinculacion4General(fil.nombre);setinputselectvinculacion4(fil.nombre)}}else{selectAllIds("");setinputselectvinculacion4("");setinputselectvinculacion4General("")}
        
    },[qvinculacion4General])

    useEffect(()=>{
        let fil = datavinculacionmarca.find(e=>e.descripcion?e.descripcion.toLowerCase().indexOf(qvinculacionmarcaGeneral.toLowerCase())!=-1:false)
        if (fil) {if (fil && qvinculacionmarcaGeneral!="") {selectAllIds(qvinculacionmarcaGeneral);setinputselectvinculacionmarcaGeneral(fil.descripcion);setinputselectvinculacionmarca(fil.descripcion)}}else{selectAllIds("");setinputselectvinculacionmarca("");setinputselectvinculacionmarca("")}
        
    },[qvinculacionmarcaGeneral])



   


    const funIdVinc = (
        id,
        n1,
        n2,
        n3,
        n4,
        marca,

        n5,
        cat,
        catesp,
        proveedor,
        maxct,
        minct,
    ) => {
        setselectIdVinculacion([id])
        
        setinputselectvinculacion1(n1)
        setinputselectvinculacion2(n2)
        setinputselectvinculacion3(n3)
        setinputselectvinculacion4(n4)
        setinputselectvinculacionmarca(marca)


        setinputselectvinculacion5(n5)
        setinputselectvinculacioncat(cat)
        setinputselectvinculacioncatesp(catesp)
        setinputselectvinculacionproveedor(proveedor)
        setinputselectvinculacionmaxct(maxct)
        setinputselectvinculacionminct(minct)
       
    }


    return <div className="container-fluid">
                <div className="row">
                    <div className="col">
                        <form className="input-group" onSubmit={e=>{e.preventDefault();buscarInventario()}}>
                            <input type="text" ref={inputBuscarInventario} className="form-control" placeholder="Buscar...(esc)" onChange={e => setQBuscarInventario(e.target.value)} value={qBuscarInventario} />
                            <select className="form-control" value={qBuscarInventarioSucursal} onChange={event=>setqBuscarInventarioSucursal(event.target.value)}>
                                <option value={""}>-SUCURSAL-</option>
                                {sucursales.map(e=>
                                    <option key={e.id} value={e.id}>{e.codigo}</option>
                                )}
                            </select>
                            <select value={Invnum} onChange={e => setInvnum(e.target.value)}>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                                <option value="2000">2000</option>
                            </select>
                            <select value={InvorderBy} onChange={e => setInvorderBy(e.target.value)}>
                                <option value="asc">Asc</option>
                                <option value="desc">Desc</option>
                            </select>
                            <button className="btn btn-success"><i className="fa fa-search"></i></button>
                        </form>
                    </div>
                    <div className="col-sm text-right">
                        <div className="btn btn-success text-light" onClick={guardarmodificarInventarioDici}><i className="fa fa-send"></i> GUARDAR</div>
                    </div>
                </div>

            <table className="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>SUCURSAL</th>
                        <th>ALTERNO</th>
                        <th>BARRAS</th>
                        <th>UNIDAD</th>
                        <th>DESC</th>
                        <th>
                            N1
                            <select onChange={event=>sameCatValue(event.target.value, "n1")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculacion1.map(data=>
                                    <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                )}
                            </select>
                        </th>
                        <th>
                            N2
                            <select onChange={event=>sameCatValue(event.target.value, "n2")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculacion2.map(data=>
                                    <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                )}
                            </select>  
                        </th>
                        <th>
                            N3
                            <select onChange={event=>sameCatValue(event.target.value, "n3")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculacion3.map(data=>
                                    <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                )}
                            </select> 
                        </th>
                        <th>
                            N4
                            <select onChange={event=>sameCatValue(event.target.value, "n4")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculacion4.map(data=>
                                    <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                )}
                            </select>
                        </th>
                        <th>
                            N5
                            <select onChange={event=>sameCatValue(event.target.value, "n5")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculacion5.map(data=>
                                    <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                )}
                            </select> 
                        </th>
                        <th>
                            MARCA
                            <select onChange={event=>sameCatValue(event.target.value, "id_marca")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculacionmarca.map(data=>
                                    <option value={data.descripcion} key={data.id}>{data.descripcion}</option>
                                )}
                            </select> 
                        </th>
                        <th>
                            CAT ESP
                            <select onChange={event=>sameCatValue(event.target.value, "id_categoria")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculaciocatesp.map(data=>
                                    <option value={data.id} key={data.id}>{data.descripcion}</option>
                                )}
                            </select>
                        </th>
                        <th>
                            CAT GEN
                            <select onChange={event=>sameCatValue(event.target.value, "id_catgeneral")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculaciocat.map(data=>
                                    <option value={data.id} key={data.id}>{data.descripcion}</option>
                                )}
                            </select>
                        </th>
                        <th>
                            PROVEEDOR
                            <select onChange={event=>sameCatValue(event.target.value, "id_proveedor")} className="form-control form-control-sm">
                                <option value=""></option>
                                {datavinculacioproveedor.map(data=>
                                    <option value={data.id} key={data.id}>{data.descripcion}</option>
                                )}
                            </select>
                        </th>
                        <th className="bg-ct">CT</th>
                        <th className="bg-base">BASE</th>
                        <th className="bg-venta">VENTA</th>
                        <th>
                            MIN
                            <form onSubmit={event=>{ sameCatValue(stockminvaluegeneral,"stockmin");event.preventDefault()}}>
                                <input type="text" className="form-control form-control-sm"  value={stockminvaluegeneral} onChange={e=>setstockminvaluegeneral(e.target.value)}/>
                            </form>
                        </th>
                        <th>
                            MAX
                            <form onSubmit={event=>{ sameCatValue(stockmaxvaluegeneral,"stockmax");event.preventDefault()}}>
                                <input type="text" className="form-control form-control-sm"  value={stockmaxvaluegeneral} onChange={e=>setstockmaxvaluegeneral(e.target.value)}/>
                            </form>
                        </th>
                    </tr>
                </thead>
                {productosInventario.length?productosInventario.map((e,i)=>
                    <tbody key={i}>
                        <tr className={" align-bottom pointer "} 
                            onClick={()=>funIdVinc(
                                e.id,
                                e.n1,
                                e.n2,
                                e.n3,
                                e.n4,
                                e.id_marca,

                                e.n5,
                                e.id_catgeneral,
                                e.id_categoria,
                                e.id_proveedor,
                                e.stockmax,
                                e.stockmin,
                            )} onDoubleClick={() => changeInventarioModificarDici(null, i, "update")}>
                            <td>
                                <small className="text-muted">{e.id}</small>
                            </td>
                            <td>
                                <button className={"btn w-100 fw-bolder fs-5"} style={{backgroundColor:colorSucursal(e.sucursal.codigo)}}>
                                    {e.sucursal.codigo}
                                </button>
                            </td>
                            <td>{e.codigo_proveedor}</td>
                            <td>{e.codigo_barras}</td>
                            <td>{e.unidad}</td>
                            <td className="text">{e.descripcion}</td>

                            <td>
                                {type(e.type)?
                                    e.n1
                                :
                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "n1")} className="form-control" value={e.n1}>
                                        <option value=""></option>
                                        {datavinculacion1.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>   
                                }
                            </td>
                            <td>
                                {type(e.type)?
                                    e.n2
                                :
                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "n2")} className="form-control" value={e.n2}>
                                        <option value=""></option>
                                        {datavinculacion2.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>   
                                }
                            </td>
                            <td>
                                {type(e.type)?
                                    e.n3
                                :
                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "n3")} className="form-control" value={e.n3}>
                                        <option value=""></option>
                                        {datavinculacion3.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>   
                                }
                            </td>
                            <td>
                                {type(e.type)?
                                    e.n4
                                :
                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "n4")} className="form-control" value={e.n4}>
                                        <option value=""></option>
                                        {datavinculacion4.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>   
                                }
                            </td>
                            <td>
                                {type(e.type)?
                                    e.n5
                                :
                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "n5")} className="form-control" value={e.n5}>
                                        <option value=""></option>
                                        {datavinculacion5.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>   
                                }
                            </td>
                            <td>
                                {type(e.type)?
                                    e.id_marca
                                :

                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "id_marca")} className="form-control" value={e.id_marca}>
                                        <option value=""></option>
                                        {datavinculacionmarca.map(data=>
                                            <option value={data.descripcion} key={data.id}>{data.descripcion}</option>
                                        )}
                                    </select>  
                                }
                            </td>
                            <td className="">
                                {type(e.type)?
                                    (e.categoria?e.categoria.descripcion:null)
                                :
                                       
                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "id_categoria")} value={e.id_categoria} className="form-control">
                                        <option value=""></option>
                                        {datavinculaciocatesp.map(data=>
                                            <option value={data.id} key={data.id}>{data.descripcion}</option>
                                        )}
                                    </select>
                                }
                            </td>
                            <td className="">
                                {type(e.type)?
                                    (e.catgeneral?e.catgeneral.descripcion:null)
                                :
                                       
                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "id_catgeneral")} value={e.id_catgeneral} className="form-control">
                                        <option value=""></option>
                                        {datavinculaciocat.map(data=>
                                            <option value={data.id} key={data.id}>{data.descripcion}</option>
                                        )}
                                    </select>
                                }
                            </td>
                            <td className="">
                                {type(e.type)?
                                    (e.proveedor?e.proveedor.descripcion:null)
                                :

                                    <select onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "id_proveedor")} value={e.id_proveedor} className="form-control">
                                        <option value=""></option>
                                        {datavinculacioproveedor.map(data=>
                                            <option value={data.id} key={data.id}>{data.descripcion}</option>
                                        )}
                                    </select>
                                    
                                }
                            </td>


                            <td className="bg-ct">{e.cantidad}</td>
                            <td className="bg-base">{e.precio_base}</td>
                            <td className="bg-venta">{e.precio}</td>


                            <td className="">
                                {type(e.type)?
                                    e.stockmin
                                :
                                    <input type="text" className="form-control" placeholder="stockmin" size={5} onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "stockmin")} value={e.stockmin} />   
                                
                                }

                            </td> 
                            <td className="">
                                {type(e.type)?
                                    e.stockmax
                                :
                                    <input type="text" className="form-control" placeholder="stockmax" size={5} onChange={event=>changeInventarioModificarDici(event.target.value, i, "changeInput", "stockmax")} value={e.stockmax} />   
                                
                                }

                            </td> 
                            <td>

                                {e.type === "update" ?
                                    <span className="btn-sm btn btn-warning" onClick={() => changeInventarioModificarDici(null, i, "delModeUpdateDelete")}><i className="fa fa-times"></i></span>
                                : null}
                            </td>
                        </tr>
                        {/* <tr className={(selectIdVinculacion.indexOf(e.id)!=-1?" bg-success-superlight ":"")}>
                            <td></td>
                            <td></td>
                            <td>
                                <table className="table table-sm">
                                    <tbody>
                                        {selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?<>
                                            <tr>
                                                <td>
                                                    <input type="text" className="" value={qvinculacion1} onChange={event=>setqvinculacion1(event.target.value)} placeholder="N1" />
                                                </td>

                                                <td>
                                                    <input type="text" className="" value={qvinculacion2} onChange={event=>setqvinculacion2(event.target.value)} placeholder="N2" />
                                                </td>

                                                <td>
                                                    <input type="text" className="" value={qvinculacion3} onChange={event=>setqvinculacion3(event.target.value)} placeholder="N3" />
                                                </td>
                                                <td>
                                                    <input type="text" className="" value={qvinculacionmarca} onChange={event=>setqvinculacionmarca(event.target.value)} placeholder="Marca" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select type="text" className=" text-primary" value={inputselectvinculacion1} onChange={()=>setinputselectvinculacion1} placeholder="VIN 1">
                                                        <option value=""></option>
                                                        {datavinculacion1.map(data=>
                                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                                        )}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select type="text" className=" text-info" value={inputselectvinculacion2} onChange={()=>setinputselectvinculacion2} placeholder="VIN 2">
                                                        <option value=""></option>
                                                        {datavinculacion2.map(data=>
                                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                                        )}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select type="text" className=" text-sinapsis" value={inputselectvinculacion3} onChange={()=>setinputselectvinculacion3} placeholder="VIN 3">
                                                        <option value=""></option>
                                                        {datavinculacion3.map(data=>
                                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                                        )}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select type="text" className=" text-success" value={inputselectvinculacionmarca} onChange={()=>setinputselectvinculacionmarca} placeholder="VIN marca">
                                                    <option value=""></option>
                                                    {datavinculacionmarca.map(data=>
                                                        <option value={data.descripcion} key={data.id}>{data.descripcion}</option>
                                                    )}</select>
                                                </td>
                                            </tr>
                                        
                                        </>:null}
                                    </tbody>
                                </table>
                                {selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?<>
                                    <button className="btn btn-success" type="button" onClick={()=>saveCuatroNombres()}>GUARDAR</button>
                                </>:null}
                            
                            </td>
                            <td className="text-muted" colSpan={7}></td>
                        
                        </tr> */}
                        
                    </tbody>
                ):null}
            </table>
         
        </div>
    
}