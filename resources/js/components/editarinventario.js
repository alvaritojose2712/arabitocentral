export default function Editarinventario({
    productosInventario,
    changeInventarioModificarDiciModificarDici,
    guardarmodificarInventarioDici,

    inputBuscarInventario,
    setQBuscarInventario,
    Invnum,
    setInvnum,
    InvorderBy,
    setInvorderBy,


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
    getDatinputSelectVinculacion,
    saveCuatroNombres,
}){
    return <div className="container-fluid">
            <form className="input-group" onSubmit={e=>{e.preventDefault();buscarInventario()}}>

                <div className="btn btn-success text-light" onClick={() => changeInventarioModificarDici(null, null, "add")}><i className="fa fa-plus"></i></div>
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
                
                <div className="btn btn-success text-light" onClick={guardarmodificarInventarioDici}><i className="fa fa-send"></i> GUARDAR</div>
            </form>

            <table className="table">
                {productosInventario.length?productosInventario.map((e,i)=>
                    <tbody key={i}>
                        <tr className={" align-bottom border-top border-top-1 border-dark pointer "} /* onClick={()=>funIdVinc(e.id,e.n1,e.n2,e.n3,e.n4,e.marca)} */ onDoubleClick={() => changeInventarioModificarDici(null, i, "update")}>
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
                            </>:null}
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
                    </tbody>
                ):null}
            </table>
         
        </div>
    
}