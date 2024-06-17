import Chart from "react-apexcharts";

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
            <div>
                <form className="input-group" onSubmit={event=>{getInventarioGeneral();event.preventDefault()}}>
                    <input type="text" className="form-control" placeholder="Buscar...(esc)" onChange={e => setinvsuc_q(e.target.value)} value={invsuc_q} />

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
            <table className="table">
                <thead>
                    <tr>
                        <td></td>
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
                        <th className="bg-warning">Histórico de Ventas / MES</th>
                    </tr>
                </thead>

                    {inventariogeneralData?
                        inventariogeneralData.data?
                            Object.entries(inventariogeneralData.data).map(e=>
                                <tbody key={e.id}>
                                    <tr>
                                        <th colSpan={14}>
                                            {e[0]}
                                        </th>
                                    </tr>
                                    {e[1].map(ee=>
                                        <>
                                            <tr key={ee.id}>
                                                <td></td>
                                                <td className="">
                                                    <button className={"btn w-100 fw-bolder fs-3"} style={{backgroundColor:colorSucursal(ee.sucursal.codigo)}}>
                                                        {ee.sucursal.codigo}
                                                    </button>
                                                </td>
                                                <td className="">{ee.idinsucursal}</td>
                                                <td className="">{ee.codigo_proveedor}</td>
                                                <td className="">{ee.codigo_barras}</td>
                                                <td className="">{ee.unidad}</td>
                                                <td className="">{ee.descripcion}</td>
                                                <th className="">{ee.cantidad}</th>
                                                <td className="">{ee.precio_base}</td>
                                                <td className="text-success">{ee.precio}</td>
                                                <td className="">{ee.updated_at}</td>
                                                {/* <td className="bg-sinapsis">
                                                    <b>
                                                        {Object.entries(ee.anual).map(anual=>anual[0]+" ")}
                                                    </b>  
                                                    <hr />
                                                    {Object.entries(ee.anual).map(anual=> Object.entries(anual[1]).map(mes=>mes[1]["ct"]).reduce((partialSum, a) => partialSum + a, 0) ).reduce((partialSum, a) => partialSum + a, 0).toFixed(2)} 
                                                </td> */}
                                                {Object.entries(ee.anual).map(anual=>
                                                    <>
                                                        <td className="bg-warning">
                                                            <b>{anual[0]}</b>
                                                            <hr />
                                                            {Object.entries(anual[1]).map(mes=>mes[1]["ct"]).reduce((partialSum, a) => partialSum + a, 0).toFixed(2)}
                                                        </td>
                                                        {Object.entries(anual[1]).map(mes=>
                                                            <td>
                                                                <b>{mes[0]} - {anual[0]}</b>
                                                                <hr />

                                                                {mes[1]["ct"].toFixed(2)}
                                                            </td>
                                                        )}
                                                    </>
                                                )}
                                                
                                            </tr>
                                           {/*  <tr>
                                                <td></td>
                                                    {Object.entries(ee.anual).map(anual=>
                                                        <td className="">
                                                                <Chart
                                                                    options={chartConfig("options",Object.entries(anual[1]).map(mes=>mes[0]+"-"+anual[0]))}
                                                                    series={chartConfig("series",Object.entries(anual[1]).map(mes=>mes[1]["ct"].toFixed(2)))}
                                                                    type="line"
                                                                    width="400"
                                                                />
                                                                
                                                        </td>
                                                    )}
                                            </tr> */}
                                        </>
                                    )}
                                </tbody>
                            )
                        :null
                    :null}
            </table>
        </div>
    )
}