import { useEffect, useState } from "react";
import { cloneDeep } from "lodash";

import  SearchBarFacturas  from "../searchBarFacturas";
import Modalselectfile from "../modalselectfile";


export default function ComprascargarFactsItems({
    setProductosInventario,

    facturaSelectAddItems,
    setfacturaSelectAddItems,
    selectCuentaPorPagarProveedorDetallesFun,
    cuentaporpagarAprobado,
    setcuentaporpagarAprobado,
    setqcuentasPorPagarDetalles,
    qcuentasPorPagarDetalles,
    setselectProveedorCxp,
    selectProveedorCxp,
    sucursalcuentasPorPagarDetalles,
    setsucursalcuentasPorPagarDetalles,
    sucursales,
    categoriacuentasPorPagarDetalles,
    setcategoriacuentasPorPagarDetalles,
    qCampocuentasPorPagarDetalles,
    setOrdercuentasPorPagarDetalles,
    setqCampocuentasPorPagarDetalles,
    selectCuentaPorPagarId,
    returnCondicion,
    colorSucursal,
    moneda,

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
    modItemFact,
    delItemFact,

    numfact_select_imagen,
    setselectFilecxp,
    showFilescxp,
    
    setinputimportitems,
    inputimportitems,

    procesarTextitemscompras,
    subviewcargaritemsfact,
    setsubviewcargaritemsfact,
    showtextarea,
    setshowtextarea,

    numcuentasPorPagarDetalles,
    setnumcuentasPorPagarDetalles,

    getBarrasCargaItems,

}){

   /*  useEffect(()=>{
       
    },[]) */

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

    const [sameCatValue, setsameCatValue] = useState("");
    const [sameCateGenValue, setsameCateGenValue] = useState("");

    const setSameCat = (val,type) => {
        if (confirm("¿Confirma Generalizar?")) {
            let obj = cloneDeep(productosInventario);
            obj.map((e) => {
                if (e.type) {
                    if (type=="cat") {
                        e.id_categoria = val;
                        setsameCatValue(val);
                    }
                    
                    if (type=="catgeneral") {
                        e.id_catgeneral = val;
                        setsameCateGenValue(val);
                    }

                }
                return e;
            });
            setProductosInventario(obj);
        }
    };


    const funIdVinc = (id,n1,n2,n3,n4,marca) => {
        setselectIdVinculacion([id])
        
        setinputselectvinculacion1(n1)
        setinputselectvinculacion2(n2)
        setinputselectvinculacion3(n3)
        setinputselectvinculacion4(n4)
        setinputselectvinculacionmarca(marca)
    }

    let facturaSelectAddItemsSelect = {}
    if (facturaSelectAddItems) {
        if (selectCuentaPorPagarId.detalles) {
            
            let match = selectCuentaPorPagarId.detalles.filter(e=>e.id==facturaSelectAddItems) 
            if (match.length) {
                facturaSelectAddItemsSelect = match[0]
            }
        }
    }
    
    return (
        <>
            {subviewcargaritemsfact=="cargar"?
                <>
                    <Modalselectfile
                        numfact_select_imagen={numfact_select_imagen}
                        setselectFilecxp={setselectFilecxp}
                        colorSucursal={colorSucursal}
                        showFilescxp={showFilescxp}
                    />
                    <div className="container-fluid p-0">
                        <div className="row">
                            <div className="col">
                                {showtextarea?
                                    <>

                                        <section className="modal-custom"> 
                                            <div className="text-danger" >
                                                <button className="btn btn-danger" onClick={()=>setshowtextarea(!showtextarea)}><i className="fa fa-times fa-2x"></i></button>
                                            </div>
                                            <div className="modal-content-sm modal-cantidad">
                                                <table className="table mb-2">
                                                    <thead>
                                                        <tr>
                                                            <th>ALTERNO</th>
                                                            <th>UNIDAD</th>
                                                            <th>DESCRIPCION</th>
                                                            <th>CANTIDAD</th>
                                                            <th>BASE F (CXP)</th> 
                                                            <th>BASE</th> 
                                                            <th>VENTA</th> 
                                                        </tr>
                                                    </thead>
                                                </table>
                                                <textarea rows="5" className="w-100" onChange={event=>setinputimportitems(event.target.value)} value={inputimportitems}></textarea>
                                                <div className="text-center">
                                                    <button className="btn btn-success m-3" onClick={()=>procesarTextitemscompras()}>PROCESAR TEXTO <i className="fa fa-cogs"></i></button>
                                                </div>
                                            </div>
                                        </section>
                                        <div className="overlay"></div>
                                    </>
                                :null}
                            </div>
                        </div>

                        {facturaSelectAddItemsSelect.id?
                        <>
                            <div className="row">
                                <div className="col">
                                    <div className="d-flex justify-content-center">
                                        <button className="btn btn-danger text-light m-3" onClick={()=>{setfacturaSelectAddItems({});setsubviewcargaritemsfact("selectfacts")}}>
                                            <i className="fa fa-arrow-left"></i> SELECCIONAR FACTURA 
                                        </button>
                                    </div>
                                </div>
                            </div>

                            
                            <div className="row mb-4">
                                <div className="col">
                                    <table className="table table-borderless table-striped m-0">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th>BARRAS</th>
                                                <th>DESCRIPCION</th>
                                                <th className="bg-ct">CT</th>
                                                <th>BASE F</th>
                                                <th className="bg-base">BASE</th>
                                                <th className="bg-venta">VENTA</th>
                                                <th className="text-right">SUBTOTAL BASE F</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            {facturaSelectAddItemsSelect.items.map((item,i)=>
                                                <tr key={item.id}>
                                                    {item.producto?
                                                        <>
                                                            <td>{i+1}</td>
                                                            <td><i className="fa fa-times text-danger" onClick={()=>delItemFact(item.id)}></i></td>
                                                            <td>{item.producto.codigo_barras}</td>
                                                            <td>{item.producto.descripcion}</td>
                                                            <td onClick={()=>modItemFact(item.id, "cantidad")} className="bg-ct">{item.cantidad}</td>
                                                            <td onClick={()=>modItemFact(item.id, "basef")} >{moneda(item.basef)}</td>
                                                            <td onClick={()=>modItemFact(item.id, "base")} className="bg-base">{moneda(item.base)}</td>
                                                            <td onClick={()=>modItemFact(item.id, "venta")} className="bg-venta">{moneda(item.venta)}</td>
                                                            <td className="text-right">{moneda(item.basef*item.cantidad)}</td>

                                                        </>
                                                    :null}
                                                </tr>
                                            )}
                                            <tr>
                                                <td colSpan={7} className="p-0"></td>
                                                <td className="text-right p-3">
                                                    <span className="fs-1 fw-bolder text-sinapsis mt-2">{moneda(facturaSelectAddItemsSelect.sumitems)}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                

                                <div className="col-3">
                                    <div className="h-100 d-flex justify-content-center align-items-end ">
                                        <div className="text-center">
                                            <div className="mb-2">
                                                <button className={"btn fw-bolder "} style={{backgroundColor:colorSucursal(facturaSelectAddItemsSelect.sucursal? facturaSelectAddItemsSelect.sucursal.codigo:"")}}>
                                                    {facturaSelectAddItemsSelect.sucursal?facturaSelectAddItemsSelect.sucursal.codigo:null}
                                                </button>
                                            </div>
                                            <img src={facturaSelectAddItemsSelect.descripcion} width={200} onClick={()=>showFilescxp(facturaSelectAddItemsSelect.descripcion)} className="pointer mb-2"/>
                                            <div>
                                                <span className="text-muted fst-italic">{facturaSelectAddItemsSelect.created_at}</span>
                                            </div>
                                            <div>
                                                <span className=" fw-bolder">{facturaSelectAddItemsSelect.proveedor?facturaSelectAddItemsSelect.proveedor.descripcion:null}</span>
                                                <br />
                                                <span className={(returnCondicion(facturaSelectAddItemsSelect.condicion))+(" btn  pointer fw-bolder text-light ms-1 ")}> 
                                                    {facturaSelectAddItemsSelect.numfact}
                                                </span>
                                            </div>
                                            <div className="p-3">
                                                <span className="fs-1 fw-bolder text-danger mt-2">{moneda(facturaSelectAddItemsSelect.monto)}</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </>
                        :null}
                        <div className="row">
                            <div className="col">
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
                                    <div className="btn btn-warning" onClick={()=>setshowtextarea(!showtextarea)}>POR LOTES</div>

                                    <div className="btn btn-success text-light" onClick={() => changeInventario(null, null, "add")}><i className="fa fa-plus"></i></div>
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
                                    
                                    <div className="btn btn-success text-light" onClick={guardarNuevoProductoLote}><i className="fa fa-send"></i> ASIGNAR</div>
                                </form>
                                
                                <form ref={refsInpInvList} onSubmit={e=>e.preventDefault()}>
                                    <table className="table mb-5 table-borderless">
                                        <thead>
                                            <tr>
                                                <th>NUM</th>
                                                <th className=" pointer"><span onClick={() => setInvorderColumn("id")}>ID</span></th>
                                                <th className=" pointer"><span onClick={() => setInvorderColumn("codigo_proveedor")}>ALTERNO</span></th>
                                                <th className=" pointer"><span onClick={() => setInvorderColumn("codigo_barras")}>BARRAS</span></th>
                                                <th className=" pointer"><span onClick={() => setInvorderColumn("unidad")}>UNIDAD</span></th>
                                                <th className=" pointer"><span onClick={() => setInvorderColumn("descripcion")}>DESCRIPCIÓN</span></th>
                                                <th className=" pointer bg-ct"><span onClick={() => setInvorderColumn("cantidad")}>CT.</span></th>
                                                <th className=" pointer"><span>BASE F</span></th>
                                                <th className=" pointer bg-base"><span onClick={() => setInvorderColumn("precio_base")}>BASE</span></th>
                                                <th className=" pointer bg-venta" onClick={() => setInvorderColumn("precio")}>VENTA</th>
                                                    {/* <button className="btn btn-success" onClick={()=>setshowInputGeneral(!showInputGeneral)}>SHOW GENERAL</button> */}
                                                <th className=" pointer" onClick={() => setInvorderColumn("id_categoria")}>
                                                    
                                                    <select
                                                        className=""
                                                        value={sameCatValue}
                                                        onChange={e=>setSameCat(e.target.value,"cat")}
                                                    >
                                                        <option value="">--Select--</option>
                                                        {categorias.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                                    </select> 
                                                    <br />  
                                                    DEPARTAMENTO
                                                </th>
                                                <th className=" pointer" onClick={() => setInvorderColumn("id_catgeneral")}>
                                                    <select
                                                        className=""
                                                        value={sameCateGenValue}
                                                        onChange={e=>setSameCat(e.target.value,"catgeneral")}
                                                    >
                                                        <option value="">--Select--</option>
                                                        {catGenerals.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                                    </select>
                                                    <br />  
                                                    CAT GENERAL
                                                </th>
                                                <th className=" pointer"><span onClick={() => setInvorderColumn("iva")}>IVA</span></th> 
                                                <th className=""></th>
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
                                            {productosInventario.length?productosInventario.map((e,i)=>
                                                <tbody key={i}>
                                                    <tr className={" align-bottom border-top border-top-1 border-dark pointer "} /* onClick={()=>funIdVinc(e.id,e.n1,e.n2,e.n3,e.n4,e.marca)} */ onDoubleClick={() => changeInventario(null, i, "update")}>
                                                        <td>{i+1}</td>
                                                        <td className="">
                                                            {e.id}
                                                        </td>
                                                        {type(e.type)?
                                                        <>
                                                            <th className="">{e.codigo_proveedor}</th>
                                                            <th className="">{e.codigo_barras}</th>
                                                            <th className="">{e.unidad}</th>
                                                            <th className="">{e.descripcion}</th>
                                                            <th className="bg-ct"></th>
                                                            <th className=""></th>
                                                            <th className="bg-base">{e.precio_base}</th>
                                                            <th className="bg-venta">{e.precio}</th>
                                                            <th className="">{e.categoria?e.categoria.descripcion:null}</th>
                                                            <th className="">{e.catgeneral?e.catgeneral.descripcion:null}</th>
                                                            <th className="">{e.iva}</th> 
                                                        </>

                                                        :
                                                        <>
                                                            <td className="align-top">
                                                                <input type="text"
                                                                    disabled={type(e.type)} className="form-control form-control-sm"
                                                                    value={!e.codigo_proveedor?"":e.codigo_proveedor}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "codigo_proveedor")}
                                                                    placeholder="codigo_proveedor..." />
                                                                    <button className="btn btn-success mt-2" onClick={()=>getBarrasCargaItems(i)}>Obtener Barras</button>
                                                            </td>
                                                            <td className="align-top">
                                                                <input type="text"
                                                                    disabled={type(e.type)} className="form-control form-control-sm"
                                                                    value={!e.codigo_barras?"":e.codigo_barras}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "codigo_barras")}
                                                                    placeholder="codigo_barras..." />
                                                                    {!e.codigo_barras_antes?"": <span className="text-muted fst-italic">{e.codigo_barras_antes}</span> }

                                                            </td>
                                                            <td className="align-top">
                                                                <select
                                                                    disabled={type(e.type)}
                                                                    className="form-control form-control-sm"
                                                                    value={!e.unidad?"":e.unidad}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "unidad")}
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
                                                            </td>
                                                            <td className="align-top">
                                                                <textarea type="text"
                                                                    cols={100}
                                                                    rows={5}
                                                                    disabled={type(e.type)} className="form-control form-control-sm"
                                                                    value={!e.descripcion?"":e.descripcion}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "descripcion")}
                                                                    placeholder="descripcion..."></textarea>
                                                                    {!e.descripcion_antes?"": <span className="text-muted fst-italic">{e.descripcion_antes}</span> }

                                                            </td>
                                                            <td className="align-top">
                                                                <input type="text"
                                                                    disabled={type(e.type)} className="form-control form-control-sm"
                                                                    value={!e.cantidad?"":e.cantidad}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "cantidad")}
                                                                    placeholder="Cantidad..."/>
                                                            </td>
                                                            <td className="align-top">
                                                                <input type="text"
                                                                    disabled={type(e.type)} className="form-control form-control-sm"
                                                                    value={!e.basef?"":e.basef}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "basef")}
                                                                    placeholder="Base F..."/>
                                                            </td>
                                                            <td className="align-top bg-base">
                                                                <input type="text"
                                                                    disabled={type(e.type)} className="form-control form-control-sm"
                                                                    value={!e.precio_base?"":e.precio_base}
                                                                    onChange={e => changeInventario(number(e.target.value), i, "changeInput", "precio_base")}
                                                                    placeholder="Costo..." />
                                                            </td>
                                                            <td className="align-top bg-venta"> 
                                                                <input type="text"
                                                                    disabled={type(e.type)} className="form-control form-control-sm"
                                                                    value={!e.precio?"":e.precio}
                                                                    onChange={e => changeInventario(number(e.target.value), i, "changeInput", "precio")}
                                                                    placeholder="Venta..." />
                                                            </td>
                                                            {/* <td className="align-top">
                                                                <select
                                                                    disabled={type(e.type)} 
                                                                    className="form-control form-control-sm"
                                                                    value={!e.id_marca?"":e.id_marca}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "id_marca")}
                                                                >
                                                                    <option value="">--Select--</option>
                                                                    {marcas.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                                                    
                                                                </select>
                                                            </td> */}

                                                            <td className="align-top">
                                                                <select
                                                                    disabled={type(e.type)} 
                                                                    className="form-control form-control-sm"
                                                                    value={!e.id_categoria?"":e.id_categoria}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "id_categoria")}
                                                                >
                                                                    <option value="">--Select--</option>
                                                                    {categorias.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                                                    
                                                                    </select>
                                                            
                                                            </td>
                                                            <td className="align-top">
                                                                <select
                                                                    disabled={type(e.type)} 
                                                                    className="form-control form-control-sm"
                                                                    value={!e.id_catgeneral?"":e.id_catgeneral}
                                                                    onChange={e => changeInventario((e.target.value), i, "changeInput", "id_catgeneral")}
                                                                >
                                                                    <option value="">--Select--</option>
                                                                    {catGenerals.map(e => <option value={e.id} key={e.id}>{e.descripcion}</option>)}
                                                                    
                                                                </select>
                                                            </td>
                                                            <td className="align-top">
                                                                <input type="text"
                                                                    disabled={type(e.type)} className="form-control form-control-sm"
                                                                    value={!e.iva?"":e.iva}
                                                                    onChange={e => changeInventario(number(e.target.value,2), i, "changeInput", "iva")}
                                                                    placeholder="iva..." />
                                                            </td> 
                                                        </>
                                                        }
                                                            <td className="align-top">
                                                                <div className='d-flex justify-content-between'>
                                                                    {!e.type ?
                                                                        <>
                                                                            <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, "delMode")}><i className="fa fa-trash"></i></span>
                                                                            <span className="btn-sm btn btn-warning" onClick={() => changeInventario(null, i, "update")}><i className="fa fa-pencil"></i></span>
                                                                        </>
                                                                        : null}
                                                                    {e.type === "new" ?
                                                                        <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, "delNew")}><i className="fa fa-times"></i></span>
                                                                        : null}
                                                                    {e.type === "update" ?
                                                                        <span className="btn-sm btn btn-warning" onClick={() => changeInventario(null, i, "delModeUpdateDelete")}><i className="fa fa-times"></i></span>
                                                                        : null}
                                                                    {e.type === "delete" ?
                                                                        <span className="btn-sm btn btn-danger" onClick={() => changeInventario(null, i, "delModeUpdateDelete")}><i className="fa fa-arrow-left"></i></span>
                                                                        : null}
                                                                </div>
                                                            </td>
                                                        
                                                    </tr>
                                                    <tr className={(selectIdVinculacion.indexOf(e.id)!=-1?" bg-success-superlight ":"")}>
                                                        <td colSpan={4}></td>
                                                        <td>
                                                            <table className="table table-sm">
                                                                <tbody>
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
                                                                </tbody>
                                                            </table>
                                                            {selectIdVinculacion.indexOf(e.id)!=-1&&e.type!="delete"?<>
                                                                <button className="btn btn-success" type="button" onClick={()=>saveCuatroNombres()}>GUARDAR</button>
                                                            </>:null}
                                                        
                                                                {/* {e.n4? e.n4+"  ":""} */}
                                                        </td>
                                                        <td className="text-muted" colSpan={7}></td>
                                                    
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
                                                </tbody>
                                            ):null}
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>   
                </> 
            :null}


            {subviewcargaritemsfact=="selectfacts"?
                <div className="container">
                    <SearchBarFacturas
                        selectCuentaPorPagarProveedorDetallesFun={selectCuentaPorPagarProveedorDetallesFun}
                        cuentaporpagarAprobado={cuentaporpagarAprobado}
                        setcuentaporpagarAprobado={setcuentaporpagarAprobado}
                        setqcuentasPorPagarDetalles={setqcuentasPorPagarDetalles}
                        qcuentasPorPagarDetalles={qcuentasPorPagarDetalles}
                        setselectProveedorCxp={setselectProveedorCxp}
                        selectProveedorCxp={selectProveedorCxp}
                        proveedoresList={proveedoresList}
                        sucursalcuentasPorPagarDetalles={sucursalcuentasPorPagarDetalles}
                        setsucursalcuentasPorPagarDetalles={setsucursalcuentasPorPagarDetalles}
                        sucursales={sucursales}
                        categoriacuentasPorPagarDetalles={categoriacuentasPorPagarDetalles}
                        setcategoriacuentasPorPagarDetalles={setcategoriacuentasPorPagarDetalles}
                        numcuentasPorPagarDetalles={numcuentasPorPagarDetalles}
                        setnumcuentasPorPagarDetalles={setnumcuentasPorPagarDetalles}
                        isonlyestatus={0}
                    />

                    <table className="table table-borderless table-striped mb-500">
                            <thead className="">
                                <tr className="align-middle">
                                    <th colSpan={4}>

                                    </th>
                                    <th colSpan={4} className="text-right">
                                        { 
                                            selectCuentaPorPagarId?
                                                selectCuentaPorPagarId.sum!=""? 
                                                    <>
                                                        Resultados
                                                        <span className="text-muted fs-2 ms-2">
                                                            <b>({selectCuentaPorPagarId.sum})</b>
                                                        </span>
                                                    </>
                                                :null
                                            :null
                                        }
                                    </th>
                                </tr>
                                <tr>
                                
                                    <th>ID</th>
                                    <th  className="pointer p-3">
                                        <span onClick={()=>{if(qCampocuentasPorPagarDetalles=="created_at"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("created_at")}}>CREADA</span>

                                    </th> 
                                    <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_proveedor"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_proveedor")}} className="pointer p-3">
                                        PROVEEDOR
                                    </th>  
                                    <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="numfact"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("numfact")}} className="pointer p-3">
                                        NÚMERO DE FACTURA
                                    </th>  
                                    <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="id_sucursal"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("id_sucursal")}} className="pointer p-3 text-center">
                                        ORIGEN
                                    </th>  
                                    <th onClick={()=>{if(qCampocuentasPorPagarDetalles=="monto"){setOrdercuentasPorPagarDetalles(OrdercuentasPorPagarDetalles==="desc"?"asc":"desc")};setqCampocuentasPorPagarDetalles("monto")}} className="pointer  p-3">
                                        MONTO
                                    </th>
                                    <th>
                                        ITEMS
                                    </th>
                                        
                                </tr>
                            </thead> 
                                
                        {
                            selectCuentaPorPagarId?selectCuentaPorPagarId.detalles
                            ? selectCuentaPorPagarId.detalles.map( (e,i) =>
                                <tbody key={i}>
                                    {!e.aprobado?
                                    <tr className={e.aprobado?"bg-success-superlight":"bg-sinapsis-superlight"}>
                                        <>
                                            <td>{e.id}</td>
                                            <td className="">
                                                <small className="text-muted">{e.created_at}</small>
                                            </td> 
                                            <td className="">
                                                <span className="fw-bold fs-4">{e.proveedor?e.proveedor.descripcion:null}</span>
                                            </td>  
                                            <td className="">
                                                
                                                <span onClick={()=>{if (!e.aprobado) { setfacturaSelectAddItems(e.id);setsubviewcargaritemsfact("cargar") }}} className={(returnCondicion(e.condicion))+(" w-100 btn fs-2 pointer fw-bolder text-light ")}> 
                                                    {e.numfact}
                                                </span>
                                            </td>  
                                            <td className=" ">
                                                <button className={"btn w-100 fw-bolder fs-3"} style={{backgroundColor:colorSucursal(e.sucursal? e.sucursal.codigo:"")}}>
                                                    {e.sucursal? e.sucursal.codigo:""}
                                                </button>
                                            </td>
                                            <td className=" ">
                                                <span className={(e.monto<0? "text-danger": "text-success")+(" fs-3 fw-bold ")}>{moneda(e.monto)}</span>
                                            </td>
                                            <td>
                                                <span className="fs-3">{e.items?e.items.length:null}</span>
                                            </td>
                                        </>    
                                    </tr>:null}
                                </tbody>
                            )
                            : null : null
                        } 
                        
                    </table>
                </div>             
            :null}
        </>
    )
}