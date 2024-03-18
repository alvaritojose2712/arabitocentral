import { useEffect, useState } from "react";
export default function Gestioninventario({
    setporcenganancia,
    productosInventario,
    qBuscarInventario,
    setQBuscarInventario,
    type,

    changeInventario,

    Invnum,
    setInvnum,
    InvorderColumn,
    setInvorderColumn,
    InvorderBy,
    setInvorderBy,
    inputBuscarInventario, 
    guardarNuevoProductoLote,

    proveedoresList,
    number,
    refsInpInvList,
    buscarInventario,
    categorias,
    marcas,
    catGenerals,

    getMarcas,
    getCatGenerals,
    getCategorias,

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
    datavinculacion2, 
    datavinculacion3, 
    datavinculacion4, 
    datavinculacionmarca, 
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
    getDatinputSelectVinculacion,
    saveCuatroNombres,

    qvinculacion1General,
    setqvinculacion1General,
    qvinculacion2General,
    setqvinculacion2General,
    qvinculacion3General,
    setqvinculacion3General,
    qvinculacion4General,
    setqvinculacion4General,
    qvinculacionmarcaGeneral,
    setqvinculacionmarcaGeneral,
    addnewNombre,

    newNombre1,
    setnewNombre1,
    newNombre2,
    setnewNombre2,
    newNombre3,
    setnewNombre3,
    newNombre4,
    setnewNombre4,
    newNombremarca,
    setnewNombremarca,
}){

    useEffect(()=>{
        getMarcas()
        getCatGenerals()
        getCategorias()
        getDatinputSelectVinculacion()
    },[])

    const [showInputGeneral, setshowInputGeneral] = useState(false)

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

    const funIdVinc = (id,n1,n2,n3,n4,marca) => {
        setselectIdVinculacion([id])
        
        setinputselectvinculacion1(n1)
        setinputselectvinculacion2(n2)
        setinputselectvinculacion3(n3)
        setinputselectvinculacion4(n4)
        setinputselectvinculacionmarca(marca)
    }
    
    return (
        <div className="container-fluid p-0">

            {showInputGeneral?<div className="boton-fijo-inferiorizq shadow">
                <div className="row">
                        <div className="btn-group">
                            <input type="text" className="fs-3" size={7} value={newNombre1} onChange={e=>setnewNombre1(e.target.value.toUpperCase())} />
                            <button className="btn btn-primary me-3 form-control-sm" placeholder="n1" onClick={()=>addnewNombre(newNombre1.toUpperCase(),"n1")}>n1</button>
                        
                            <input type="text" className="fs-3" size={7} value={newNombre2} onChange={e=>setnewNombre2(e.target.value.toUpperCase())} />
                            <button className="btn btn-info me-3 form-control-sm" placeholder="n2" onClick={()=>addnewNombre(newNombre2.toUpperCase(),"n2")}>n2</button>
                       
                            <input type="text" className="fs-3" size={7} value={newNombre3} onChange={e=>setnewNombre3(e.target.value.toUpperCase())} />
                            <button className="btn btn-sinapsis me-3 form-control-sm" placeholder="n3" onClick={()=>addnewNombre(newNombre3.toUpperCase(),"n3")}>n3</button>
                        
                            {/* <input type="text" className="fs-3" size={7} value={newNombre4} onChange={e=>setnewNombre4(e.target.value.toUpperCase())} />
                            <button className="btn btn-danger me-3 form-control-sm" placeholder="n4" onClick={()=>addnewNombre(newNombre4.toUpperCase(),"n4")}>n4</button> */}
                        
                            <input type="text" className="fs-3" size={7} value={newNombremarca} onChange={e=>setnewNombremarca(e.target.value.toUpperCase())} />
                            <button className="btn btn-success me-3 form-control-sm" placeholder="marca" onClick={()=>addnewNombre(newNombremarca.toUpperCase(),"marca")}>marca</button>
                        </div>
                </div>
            </div>:null}
            <form className="input-group" onSubmit={e=>{e.preventDefault();buscarInventario()}}>
                <div className="btn btn-success text-light" onClick={() => changeInventario(null, null, null, "add")}><i className="fa fa-plus"></i></div>
                    <input type="text" ref={inputBuscarInventario} className="form-control" placeholder="Buscar...(esc)" onChange={e => setQBuscarInventario(e.target.value)} value={qBuscarInventario} />

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
                    
                <div className="btn btn-success text-light" onClick={guardarNuevoProductoLote}><i className="fa fa-send"></i> (f1)</div>
            </form>
            
            <form ref={refsInpInvList} onSubmit={e=>e.preventDefault()}>
                <table className="table mb-5 table-borderless">
                    <thead>
                        <tr>
                            <th className="cell05 pointer"><span onClick={() => setInvorderColumn("id")}>ID</span></th>
                            <th className="cell1 pointer"><span onClick={() => setInvorderColumn("codigo_proveedor")}>C. Alterno</span></th>
                            <th className="cell1 pointer"><span onClick={() => setInvorderColumn("codigo_barras")}>C. Barras</span></th>
                           {/*  <th className="cell05 pointer"><span onClick={() => setInvorderColumn("unidad")}>Unidad</span></th> */}
                            <th className="cell2 pointer"><span onClick={() => setInvorderColumn("descripcion")}>Descripción</span></th>
                           
                           {/*  <th className="cell05 pointer"><span onClick={() => setInvorderColumn("cantidad")}>Ct.</span></th> */}
                            <th className="cell1 pointer"><span onClick={() => setInvorderColumn("precio_base")}>Base</span></th>
                            <th className="cell15 pointer">
                                <span onClick={() => setInvorderColumn("precio")}>Venta</span>
                                <button className="btn btn-success" onClick={()=>setshowInputGeneral(!showInputGeneral)}>SHOW GENERAL</button>
                            </th>

                            {/* <th className="cell15 pointer" >
                                <span onClick={() => setInvorderColumn("id_categoria")}>Departamento</span><br/>
                            </th>

                            <th className="cell15 pointer" >
                                <span onClick={() => setInvorderColumn("id_catgeneral")}>Cat General</span><br/>
                            </th>
                            <th className="cell05 pointer"><span onClick={() => setInvorderColumn("iva")}>IVA</span></th> */}
                            <th className="cell1"></th>

                        </tr>
                        {/* {showInputGeneral?<tr className="bg-sinapsis-light">
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <div className="form-group">
                                    <input type="text" className="form-control" value={qvinculacion1General} onChange={event=>setqvinculacion1General(event.target.value)} placeholder="BUSCAR VIN 1General" />
                                </div>
                                <div className="form-group">
                                    <select type="text" className="form-control text-primary" value={inputselectvinculacion1General} onChange={()=>setinputselectvinculacion1General} placeholder="VIN 1General">
                                        <option value=""></option>
                                        {datavinculacion1.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div className="form-group">
                                    <input type="text" className="form-control" value={qvinculacion2General} onChange={event=>setqvinculacion2General(event.target.value)} placeholder="BUSCAR VIN 2General" />
                                </div>
                                <div className="form-group">
                                    <select type="text" className="form-control text-info" value={inputselectvinculacion2General} onChange={()=>setinputselectvinculacion2General} placeholder="VIN 2General">
                                        <option value=""></option>
                                        {datavinculacion2.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div className="form-group">
                                    <input type="text" className="form-control" value={qvinculacion3General} onChange={event=>setqvinculacion3General(event.target.value)} placeholder="BUSCAR VIN 3General" />
                                </div>
                                <div className="form-group">
                                    <select type="text" className="form-control text-sinapsis" value={inputselectvinculacion3General} onChange={()=>setinputselectvinculacion3General} placeholder="VIN 3General">
                                        <option value=""></option>
                                        {datavinculacion3.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div className="form-group">
                                    <input type="text" className="form-control" value={qvinculacion4General} onChange={event=>setqvinculacion4General(event.target.value)} placeholder="BUSCAR VIN 4General" />
                                </div>
                                <div className="form-group">
                                    <select type="text" className="form-control text-danger" value={inputselectvinculacion4General} onChange={()=>setinputselectvinculacion4General} placeholder="VIN 4General">
                                        <option value=""></option>
                                        {datavinculacion4.map(data=>
                                            <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                        )}
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div className="form-group">
                                    <input type="text" className="form-control" value={qvinculacionmarcaGeneral} onChange={event=>setqvinculacionmarcaGeneral(event.target.value)} placeholder="BUSCAR VIN marcaGeneral" />
                                </div>
                                <div className="form-group">
                                    <select type="text" className="form-control text-success" value={inputselectvinculacionmarcaGeneral} onChange={()=>setinputselectvinculacionmarca} placeholder="VIN marcaGeneral">
                                        <option value=""></option>
                                        {datavinculacionmarca.map(data=>
                                            <option value={data.descripcion} key={data.id}>{data.descripcion}</option>
                                        )}
                                    </select>
                                </div>
                            </td>
                        </tr>:null} */}
                    </thead>
                    <tbody>
                        {productosInventario.length?productosInventario.map((e,i)=>
                        <>
                            <tr key={i} className={" align-bottom border-top border-top-1 border-dark pointer "+(e.type === "delete"?"bg-danger-light ":(selectIdVinculacion.indexOf(e.id)!=-1?" bg-sinapsis ":""))+(e.n1&&e.n2&&e.n3&&e.marca?"bg-success":"bg-danger")} onClick={()=>funIdVinc(e.id,e.n1,e.n2,e.n3,e.n4,e.marca)} onDoubleClick={() => changeInventario(null, i, e.id, "delMode")}>
                                <td className="cell05">
                                    {e.id}
                                </td>
                                {type(e.type)?
                                <>
                                    <th className="cell1">{e.codigo_proveedor}</th>
                                    <th className="cell1">{e.codigo_barras}</th>
                                    {/* <th className="cell05">{e.unidad}</th> */}
                                    <th className="cell2">{e.descripcion}</th>
                                    {/* <th className="cell05">{e.cantidad}</th> */}
                                    <th className="cell1">{e.precio_base}</th>
                                    <th className="cell15 text-success">{e.precio}</th>
                                    {/* <th className="cell15">{e.categoria.descripcion}</th>
                                    <th className="cell15">{e.catgeneral.descripcion}</th>
                                    <th className="cell05">{e.iva}</th> */}
                                </>

                                :
                                <>
                                    <td className="cell1">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.codigo_proveedor?"":e.codigo_proveedor}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "codigo_proveedor")}
                                            placeholder="codigo_proveedor..." />

                                    </td>
                                    <td className="cell1">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.codigo_barras?"":e.codigo_barras}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "codigo_barras")}
                                            placeholder="codigo_barras..." />

                                    </td>
                                    {/* <td className="cell05">
                                        <select
                                            disabled={type(e.type)}
                                            className="form-control form-control-sm"
                                            value={!e.unidad?"":e.unidad}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "unidad")}
                                        >
                                            <option value="">--Select--</option>
                                            <option value="UND">UND</option>
                                            <option value="PAR">PAR</option>
                                            <option value="JUEGO">JUEGO</option>
                                            <option value="PQT">PQT</option>
                                            <option value="MTR">MTR</option>
                                            <option value="KG">KG</option>
                                            <option value="GRS">GRS</option>
                                            <option value="LTR">LTR</option>
                                            <option value="ML">ML</option>
                                        </select>
                                    </td> */}
                                    <td className="cell2">
                                        <textarea type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.descripcion?"":e.descripcion}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "descripcion")}
                                            placeholder="descripcion..."></textarea>

                                    </td>
                                    <td className="cell15">
                                        <select
                                            disabled={type(e.type)} 
                                            className="form-control form-control-sm"
                                            value={!e.id_marca?"":e.id_marca}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "id_marca")}
                                        >
                                            <option value="">--Select--</option>
                                            {marcas.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                            
                                        </select>
                                       
                                    </td>
                                    <td className="cell05">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.cantidad?"":e.cantidad}
                                            onChange={e => changeInventario(number(e.target.value), i, e.id, "changeInput", "cantidad")}
                                            placeholder="cantidad..." />

                                    </td>
                                    <td className="cell1">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.precio_base?"":e.precio_base}
                                            onChange={e => changeInventario(number(e.target.value), i, e.id, "changeInput", "precio_base")}
                                            placeholder="Costo..." />



                                    </td>
                                    <td className="cell15">
                                        <div className="input-group">
                                            <input type="text"
                                                disabled={type(e.type)} className="form-control form-control-sm"
                                                value={!e.precio?"":e.precio}
                                                onChange={e => changeInventario(number(e.target.value), i, e.id, "changeInput", "precio")}
                                                placeholder="Final..." />
                                            <span className="btn btn-sm" onClick={()=>setporcenganancia("list",e.precio_base,(precio)=>{
                                                    changeInventario(precio, i, e.id, "changeInput", "precio")
                                                })}>%</span>
                                        </div>

                                    </td>

                                    {/* <td className="cell15">
                                        <select
                                            disabled={type(e.type)} 
                                            className="form-control form-control-sm"
                                            value={!e.id_categoria?"":e.id_categoria}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "id_categoria")}
                                        >
                                            <option value="">--Select--</option>
                                            {categorias.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                            
                                        </select>
                                       
                                    </td>

                                    <td className="cell15">
                                        <select
                                            disabled={type(e.type)} 
                                            className="form-control form-control-sm"
                                            value={!e.id_catgeneral?"":e.id_catgeneral}
                                            onChange={e => changeInventario((e.target.value), i, e.id, "changeInput", "id_catgeneral")}
                                        >
                                            <option value="">--Select--</option>
                                            {catGenerals.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                            
                                        </select>
                                       
                                    </td>
                                    <td className="cell05">
                                        <input type="text"
                                            disabled={type(e.type)} className="form-control form-control-sm"
                                            value={!e.iva?"":e.iva}
                                            onChange={e => changeInventario(number(e.target.value,2), i, e.id, "changeInput", "iva")}
                                            placeholder="iva..." />

                                    </td> */}
                                </>
                                }
                                    <td className="cell1">
                                        <div className='d-flex justify-content-between'>
                                            {!e.type ?
                                                <>
                                                    <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, e.id, "delMode")}><i className="fa fa-trash"></i></span>
                                                    <span className="btn-sm btn btn-warning" onClick={() => changeInventario(null, i, e.id, "update")}><i className="fa fa-pencil"></i></span>
                                                </>
                                                : null}
                                            {e.type === "new" ?
                                                <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, e.id, "delNew")}><i className="fa fa-times"></i></span>
                                                : null}
                                            {e.type === "update" ?
                                                <span className="btn-sm btn btn-warning" onClick={() => changeInventario(null, i, e.id, "delModeUpdateDelete")}><i className="fa fa-times"></i></span>
                                                : null}
                                            {e.type === "delete" ?
                                                <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, e.id, "delModeUpdateDelete")}><i className="fa fa-arrow-left"></i></span>
                                                : null}
                                        </div>
                                    </td>
                                
                            </tr>
                            <tr className={(selectIdVinculacion.indexOf(e.id)!=-1?" bg-success-superlight ":"")}>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <table className="table table-sm">
                                        <tr>
                                            <td>
                                                {e.n1? e.n1+"  ":<span className="text-success fw-bold">{(selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?inputselectvinculacion1:"")}</span>}
                                            </td>
                                            <td>
                                                {e.n2? e.n2+"  ":<span className="text-success fw-bold">{(selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?inputselectvinculacion2:"")}</span>}
                                            </td>
                                            <td>
                                                {e.n3? e.n3+"  ":<span className="text-success fw-bold">{(selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?inputselectvinculacion3:"")}</span>}
                                            </td>
                                            <td>
                                                {e.marca? e.marca+"  ":<span className="text-success fw-bold">{(selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?inputselectvinculacionmarca:"")}</span>}

                                            </td>
                                        </tr>
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
                                    </table>
                                    {selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?<>
                                        <button className="btn btn-success" type="button" onClick={()=>saveCuatroNombres()}>GUARDAR</button>
                                    </>:null}
                                   
                                        {/* {e.n4? e.n4+"  ":""} */}
                                </td>
                                <td className="text-muted"></td>
                                <td className="text-muted"></td>
                                <td className="text-muted"></td>
                                <td className="text-muted"></td>
                            </tr>
                            {
                                selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?
                                    <>
                                        <tr className={(selectIdVinculacion.indexOf(e.id)!=-1?" bg-success-superlight ":"")}>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                           

                                            {/* <td>
                                                <div>
                                                    <div className="form-group">
                                                        <input type="text" className="" value={qvinculacion4} onChange={event=>setqvinculacion4(event.target.value)} placeholder="BUSCAR VIN 4" />
                                                    </div>
                                                    <div className="form-group">
                                                        <select type="text" className=" text-danger" value={inputselectvinculacion4} onChange={()=>setinputselectvinculacion4} placeholder="VIN 4">
                                                            <option value=""></option>
                                                            {datavinculacion4.map(data=>
                                                                <option value={data.nombre} key={data.id}>{data.nombre}</option>
                                                            )}
                                                        </select>
                                                    </div>
                                                </div>
                                                <div>

                                                </div>
                                            </td> */}

                                            <td>

                                            </td>
                                        </tr>
                                                        
                                    </>
                                :null
                            }

                             {/*{e.sucursales?e.sucursales.length?
                                e.sucursales.map(su=>
                                    <tr key={su.id}>
                                        <td></td>
                                        <td></td>
                                        <td>{e.codigo_barras}</td>
                                        <td></td>
                                        <td colSpan={4}>{e.descripcion}</td>
                                    </tr>
                                )
                            :null:null} */}
                        </>
                        ):null}
                    </tbody>
                </table>
            </form>
        </div>    
    )
}