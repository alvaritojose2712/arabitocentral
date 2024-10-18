import Chart from "react-apexcharts";
import Modalmovil from "./modalmovil";

export default function Inventariogeneral({
    setinvsuc_q,
    invsuc_q,
    invsuc_num,
    setinvsuc_num,
    invsuc_orderBy,
    setinvsuc_orderBy,
    setinvsuc_orderColumn,

    inventariogeneralData,
    getInventarioGeneral,

    sucursales,
    colorSucursal,

    inventarioGeneralqsucursal,
    setinventarioGeneralqsucursal,


    selectcampobusquedaestadistica,
    setselectcampobusquedaestadistica,
    dataCamposBusquedaEstadisticas,
    selectvalorcampobusquedaestadistica,
    setselectvalorcampobusquedaestadistica,
    agregarCampoBusquedaEstadisticas,
    selectsucursalbusquedaestadistica,
    setselectsucursalbusquedaestadistica,
    agregarSucursalBusquedaEstadisticas,
    
    camposAgregadosBusquedaEstadisticas,
    sucursalesAgregadasBusquedaEstadisticas,
    setcamposAgregadosBusquedaEstadisticas,
    setsucursalesAgregadasBusquedaEstadisticas,

    inventariogeneralSelectProEsta,
    setinventariogeneralSelectProEsta,
    inventariogeneralProEsta,
    setinventariogeneralProEsta,
    getEstadiscaSelectProducto,
    delVinculoSucursal,

    
    idselectproductoinsucursalforvicularMaestro,
    setidselectproductoinsucursalforvicularMaestro,
    linkproductocentralmaestro,
    openVincularSucursalwithMaestro,
    buscarInventarioModal,
    productosInventarioModal,
    setproductosInventarioModal,
    InvnumModal,
    setInvnumModal,
    qBuscarInventarioModal,
    setqBuscarInventarioModal,
    InvorderColumnModal,
    setInvorderColumnModal,
    InvorderByModal,
    setInvorderByModal,
    inputbuscarcentralforvincular,
    modalmovilx,
    modalmovily,
    setmodalmovilshow,
    modalmovilshow,
    modalmovilRef,
    id_sucursal_select_internoModal,
    setid_sucursal_select_internoModal,
}){
    /* Object.entries(anual[1]).map(mes=>mes[0]+"-"+anual[0])
    Object.entries(anual[1]).map(mes=>mes[1]["ct"].toFixed(2)) */


    const chartConfig = (type,data) =>{

        if (type=="options") {
            return {
                chart: {
                  height: 500,
                  type: 'line',
                  dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                  },
                  zoom: {
                    enabled: false
                  },
                  toolbar: {
                    show: false
                  }
                },
                colors: ['#77B6EA', '#545454'],
                dataLabels: {
                  enabled: true,
                },
                stroke: {
                  curve: 'smooth'
                },
                title: {
                  text: '',
                  align: 'left'
                },
                grid: {
                  borderColor: '#e7e7e7',
                  row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                  },
                },
                markers: {
                  size: 1
                },
                xaxis: {
                  categories: data,
                  title: {
                    text: ''
                  }
                },
                yaxis: {
                  title: {
                    text: 'UNIDADES VENDIDAS'
                  },
                },
                legend: {
                  position: 'top',
                  horizontalAlign: 'right',
                  floating: true,
                  offsetY: -25,
                  offsetX: -5
                }
              }
        }
        if (type=="series") {
            return [{
                name: "",
                data: data
            }]
        }
        
    }
    return (
        <div className="container-fluid">

            {modalmovilshow ? (
                <Modalmovil
                    getProductos={buscarInventarioModal}
                    productos={productosInventarioModal}
                    setproductosInventarioModal={setproductosInventarioModal}
                    InvnumModal={InvnumModal}
                    setInvnumModal={setInvnumModal}
                    qBuscarInventarioModal={qBuscarInventarioModal}
                    setqBuscarInventarioModal={setqBuscarInventarioModal}
                    InvorderColumnModal={InvorderColumnModal}
                    setInvorderColumnModal={setInvorderColumnModal}
                    InvorderByModal={InvorderByModal}
                    setInvorderByModal={setInvorderByModal}
                    margin={1}
                    inputbuscarcentralforvincular={inputbuscarcentralforvincular}
                    x={modalmovilx}
                    y={modalmovily}
                    setmodalmovilshow={setmodalmovilshow}
                    modalmovilshow={modalmovilshow}
                    modalmovilRef={modalmovilRef}
                    linkproductocentralsucursal={linkproductocentralmaestro}
                    id_sucursal_select={null}
                    sucursales={sucursales}
                    id_sucursal_select_internoModal={id_sucursal_select_internoModal}
                    setid_sucursal_select_internoModal={setid_sucursal_select_internoModal}
                    idselectproductoinsucursalforvicular={idselectproductoinsucursalforvicularMaestro}
                />
            ) : null}


            <div>
                <form className="input-group" onSubmit={event=>{getInventarioGeneral();event.preventDefault()}}>
                    <input type="text" className="form-control" placeholder="Buscar...(esc)" onChange={e => setinvsuc_q(e.target.value)} value={invsuc_q} />
                    <select className="form-control form-control-lg" value={inventarioGeneralqsucursal} onChange={e=>setinventarioGeneralqsucursal(e.target.value)}>
                        <option value="">-SUCURSAL-</option>
                        {sucursales.map(e=>
                            <option key={e.id} value={e.id}>{e.codigo}</option>
                        )}
                    </select>
                    <select value={invsuc_num} onChange={e => setinvsuc_num(e.target.value)} className="form-control">
                        <option value="25">Num.25</option>
                        <option value="50">Num.50</option>
                        <option value="100">Num.100</option>
                        <option value="500">Num.500</option>
                        <option value="2000">Num.2000</option>
                        <option value="10000">Num.100000</option>
                    </select>
                    <select value={invsuc_orderBy} onChange={e => setinvsuc_orderBy(e.target.value)} className="form-control">
                        <option value="asc">Orden Asc</option>
                        <option value="desc">Orden Desc</option>
                    </select>
                    <button className="btn btn-success"><i className="fa fa-search"></i></button>
                </form>
            </div>

            {/* <div className="row">
                <div className="col">
                    <div className="card">
                        <div className="container-fluid">
                            <div className="row">
                                <div className="col-3 text-center">
                                    <div className="m-3">
                                        <select className="form-control" value={selectcampobusquedaestadistica} onChange={event=>setselectcampobusquedaestadistica(event.target.value)}>
                                            {dataCamposBusquedaEstadisticas.map(e=>
                                                <option key={e.id} value={e.id}>{e.codigo}</option>
                                            )}
                                        </select>
                                        <input type="text" className="form-control" value={selectvalorcampobusquedaestadistica} onChange={event=>setselectvalorcampobusquedaestadistica(event.target.value)}/>
                                        <button className="btn btn-success m-2" onClick={()=>agregarCampoBusquedaEstadisticas()}><i className="fa fa-plus"></i></button>

                                    </div>

                                </div>

                                <div className="col">
                                    {camposAgregadosBusquedaEstadisticas.map((e,i)=>
                                        <div key={i} className="btn-group m-2" onDoubleClick={()=>setcamposAgregadosBusquedaEstadisticas(camposAgregadosBusquedaEstadisticas.filter(ee=>ee.campo!=e.campo))}>
                                            <button className="btn btn-sinapsis">{e.campo}</button>
                                            <button className="btn btn-success">{e.valor}</button>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col">
                    <div className="card">
                        <div className="container-fluid">
                            <div className="row">
                                <div className="col-3 text-center">
                                    <div className="m-3">
                                        <select className="form-control" value={selectsucursalbusquedaestadistica} onChange={event=>setselectsucursalbusquedaestadistica(event.target.value)}>
                                            <option value={""}>-SUCURSAL-</option>
                                            {sucursales.map(e=>
                                                <option key={e.id} value={e.id}>{e.codigo}</option>
                                            )}
                                        </select>
                                        <button className="btn btn-success m-2" onClick={()=>agregarSucursalBusquedaEstadisticas()}><i className="fa fa-plus"></i></button>
                                    </div>
                                </div>

                                <div className="col">
                                    {sucursalesAgregadasBusquedaEstadisticas.map(e=>
                                        <div className="btn-group m-2" onDoubleClick={()=>setsucursalesAgregadasBusquedaEstadisticas(sucursalesAgregadasBusquedaEstadisticas.filter(ee=>ee.sucursal!=e.sucursal))}>
                                            <button className="btn">{e.sucursal}</button>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> */}

            <table className="table">
                <thead>
                    <tr>
                        <th>DESCRIPCIÓN</th>
                    </tr>
                </thead>
                {inventariogeneralData?
                    inventariogeneralData.maestros?
                        inventariogeneralData.maestros.map(e=>
                        <tbody>
                            <tr>
                                <td>
                                    {e.descripcion}  <span
                                            className={("text-muted")+(" fs-10px")}
                                            onClick={(event)=>openVincularSucursalwithMaestro(event,{id_producto_central: e.id , index: i, vinculados:e.vinculados})}
                                        >
                                            <i className="fa fa-link fa-2x"></i>
                                        </span>
                                </td>
                            </tr>
                        </tbody>
                        )
                    :null
                :null}
            </table>

            <table className="table table-responsive">
                <thead>
                    <tr>
                        <th className="pointer"><span>SUCURSAL</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("id")}>ID in SUCURSAL</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("codigo_proveedor")}>C. Alterno</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("codigo_barras")}>C. Barras</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("unidad")}>Unidad</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("descripcion")}>Descripción</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("cantidad")}>Ct.</span>/ <span onClick={() => setinvsuc_orderColumn("push")}>Inventario</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("precio_base")}>Base</span></th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("precio")}>Venta </span></th>
                       {/*  <th className="pointer" >
                            <span onClick={() => setinvsuc_orderColumn("id_categoria")}>
                                Categoría
                            </span>
                            <br/>
                            <span onClick={() => setinvsuc_orderColumn("id_proveedor")}>
                                Proveedor
                            </span>
                        </th>
                        <th className="pointer"><span onClick={() => setinvsuc_orderColumn("iva")}>IVA</span></th>
                         */}
                        <th className="">ACTUALIZACIÓN</th>
                        {/* <th className="bg-sinapsis">Histórico de Ventas / TOTAL</th> */}
                        <th className="">HISTÓRICO</th>
                    </tr>
                </thead>

                    {inventariogeneralData?
                        inventariogeneralData.data?
                            inventariogeneralData.data.map(e=>

                                <tbody key={e.id}>

                                    <tr className="bg-success-light">
                                        <td>
                                            <span className="fst-italic" style={{backgroundColor:e.sucursal.background,color:"#000"}}>
                                                {e.sucursal.codigo} <b>({e.vinculados.length})</b>
                                            </span>
                                        </td>
                                        <td className="">{e.idinsucursal}</td>
                                        <td className="">{e.codigo_provedor}</td>
                                        <td className="">{e.codigo_barras}</td>
                                        <td className="">{e.unidad}</td>
                                        <td className="">{e.descripcion}</td>
                                        <th className="">{e.cantidad}</th>
                                        <td className="">{e.precio_base}</td>
                                        <td className="text-success">{e.precio}</td>
                                        <td className="">
                                            {e.updated_at+" "}
                                            <button className="btn btn-sinapsis" onClick={()=>getEstadiscaSelectProducto(e.id)}><i className="fa fa-bar-chart fa-2x"></i></button>
                                        </td>
                                        {inventariogeneralProEsta?
                                            inventariogeneralProEsta.id==e.id?
                                                inventariogeneralProEsta.sumas.map(mesyct=>
                                                    <td className={(mesyct["mes"]==0?"":"bg-warning")+" notwrap"}>
                                                        <b>{mesyct["ano"]}{mesyct["mes"]?"-"+mesyct["mes"]:""}</b>
                                                        <hr />
                                                        {mesyct["ct"].toFixed(2)}
                                                    </td> 
                                                )

                                            :null
                                        :null}
                                    </tr>
                                    {e.vinculados?
                                        e.vinculados.map(ee=>
                                            ee.producto?
                                                <tr key={ee.producto.id}>

                                                    <td>
                                                        <span className="fst-italic" style={{backgroundColor:ee.producto.sucursal.background,color:"#000"}}>
                                                            {ee.producto.sucursal.codigo}
                                                        </span>
                                                    </td>
                                                    <td className="">{ee.producto.idinsucursal}</td>
                                                    <td className="">{ee.producto.codigo_provedor}</td>
                                                    <td className="">{ee.producto.codigo_barras}</td>
                                                    <td className="">{ee.producto.unidad}</td>
                                                    <td className="">{ee.producto.descripcion}</td>
                                                    <th className="">{ee.producto.cantidad}</th>
                                                    <td className="">{ee.producto.precio_base}</td>
                                                    <td className="text-success">{ee.producto.precio}</td>
                                                    <td className="">{ee.producto.updated_at} <i className="fa fa-times text-danger" onDoubleClick={()=>delVinculoSucursal(ee.id)}></i></td>
                                                        {inventariogeneralProEsta?
                                                            inventariogeneralProEsta.id==e.id?
                                                            inventariogeneralProEsta.vinculados.filter(vin=>vin.id==ee.id).map(eee=>
                                                                Object.entries(eee.anual).map(anual=>
                                                                    <>
                                                                        <td className="bg-warning-light">
                                                                            <b>{anual[0]}</b>
                                                                            <hr />
                                                                            {Object.entries(anual[1]).map(mes=>mes[1]["ct"]).reduce((partialSum, a) => partialSum + a, 0).toFixed(2)}
                                                                        </td>
                                                                        {Object.entries(anual[1]).map(mes=>
                                                                            <td className="notwrap">
                                                                                <b>{anual[0]} - {mes[0]}</b>
                                                                                <hr />
                                                                                {mes[1]["ct"].toFixed(2)}
                                                                            </td>
                                                                        )}
                                                                    </>
                                                                )
                                                            )
                                                            :null
                                                        :null}
                                                </tr>
                                            :null
                                        )
                                    :null}
                                </tbody>
                            )
                        :null
                    :null}
            </table>
        </div>
    )
}

