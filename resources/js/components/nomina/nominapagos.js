import { useEffect } from 'react';

export default function Nominapagos({
    qSucursalNomina,
    setqSucursalNomina,
    sucursales,
    qCargoNomina,
    setqCargoNomina,
    cargosData,
    qNomina,
    setqNomina,
    getPersonalNomina,
    getPersonalCargos,
    nominaData,
    subViewNomina,

    selectNominaDetalles,

    nominapagodetalles,
    setnominapagodetalles,
}){
    useEffect(() => {
        getPersonalNomina()
        getPersonalCargos()
    }, [subViewNomina])
    return (
        <div>
			{!nominapagodetalles.nominanombre?
                <form onSubmit={getPersonalNomina}>
                    <div className="input-group ">

                        <input type="text"
                            className="form-control"
                            placeholder="Buscar..."
                            value={qNomina}
                            onChange={e => setqNomina(e.target.value)} />
                    
                        <select
                            value={qSucursalNomina}
                            onChange={e => setqSucursalNomina(e.target.value)}
                            className="form-control">
                            <option value="">--Por Sucursal--</option>
                            {sucursales.map(e=>
                                <option key={e.id} value={e.id}>{e.nombre}</option>
                            )}
                        </select>

                        <select
                            value={qCargoNomina}
                            onChange={e => setqCargoNomina(e.target.value)}
                            className="form-control">
                            <option value="">--Por Cargo--</option>
                            {cargosData.map(e=>
                                <option key={e.id} value={e.id}>{e.cargosdescripcion}</option>
                            )}
                        </select>
                        

                        <div className="input-group-prepend">
                            <button className="btn btn-outline-secondary" type="button" onClick={getPersonalNomina}><i className="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            :null}

            {!nominapagodetalles.nominanombre?
                <table className="table">
                    <thead>
                        <tr>
                            <th>Sucursal</th>
                            <th>Cédula</th>
                            <th>Nombres y Apellidos</th>
                            <th>Cargo</th>
                        </tr>
                    </thead>
                    <tbody>


                            {
                                nominaData.personal?
                                    nominaData.personal.length?
                                        nominaData.personal.map((e, i) =>
                                            <tr key={e.id} className='pointer' onClick={()=>selectNominaDetalles(e.id)}>
                                                <td>{e.sucursal.nombre}</td>
                                                <td>{e.nominacedula}</td>
                                                <td>{e.nominanombre}</td>
                                                <td>{e.cargo.cargosdescripcion}</td>
                                            </tr>
                                        )
                                    : null
                                : null
                            }
                    </tbody>
                </table>
            :
                <div>
					<h1>Detalles <button className="btn btn-sm btn-outline-danger" onClick={()=>setnominapagodetalles({})}><i className="fa fa-times"></i></button></h1>
                    <div className="container-fluid">
                        <div className="row">
                            <div className="col p-0">
                                <table className="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h3>
                                                    <button className="btn btn-secondary">
                                                        {nominapagodetalles.sucursal?nominapagodetalles.sucursal.nombre:null}
                                                    </button>
                                                </h3>
                                            </th>
                                            <th>
                                                <h3 className='text-right'>
                                                    <button className="btn btn-secondary">
                                                        {nominapagodetalles.cargo?nominapagodetalles.cargo.cargosdescripcion:null} (S. {nominapagodetalles.cargo?nominapagodetalles.cargo.cargossueldo:null})
                                                    </button>
                                                </h3>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col p-0">
                                <table className="table table-sm table-striped">
                                    <thead>
                                       
                                        <tr>
                                            <th className=''>Nombres</th>
                                            <td>{nominapagodetalles.nominanombre?nominapagodetalles.nominanombre:null}</td>
                                            <th className=''>Cédula</th>
                                            <td>{nominapagodetalles.nominacedula?nominapagodetalles.nominacedula:null}</td>
                                        </tr>
                                        <tr>
                                            <th className=''>Edad</th>
                                            <td>{nominapagodetalles.nominafechadenacimiento?nominapagodetalles.nominafechadenacimiento:null} ({nominapagodetalles.edad?nominapagodetalles.edad:null} años)</td>
                                            <th className=''>Teléfono</th>
                                            <td>{nominapagodetalles.nominatelefono?nominapagodetalles.nominatelefono:null}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div className="col p-0">
                                <table className="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th className=''>Ingreso</th>
                                            <td>{nominapagodetalles.nominafechadeingreso?nominapagodetalles.nominafechadeingreso:null} ({nominapagodetalles.tiempolaborado?nominapagodetalles.tiempolaborado:null} años)</td>
                                            <th className=''>Instrucción</th>
                                            <td>{nominapagodetalles.nominagradoinstruccion?nominapagodetalles.nominagradoinstruccion:null}</td>
                                        </tr>
                                        <tr>
                                            <th className=''>Dirección</th>
                                            <td>{nominapagodetalles.nominadireccion?nominapagodetalles.nominadireccion:null}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                <h3>Pagos</h3>
                            </div>

                            <div className="col">
                                <h3>Créditos</h3>
                            </div>

                        </div>
                    </div>
                </div>
            }
        </div>
    )
}