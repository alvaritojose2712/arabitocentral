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
            <form onSubmit={getPersonalNomina}>
                <div className="input-group ">

                
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
                    

                    <input type="text"
                        className="form-control"
                        placeholder="Buscar..."
                        value={qNomina}
                        onChange={e => setqNomina(e.target.value)} />
                    <div className="input-group-prepend">
                        <button className="btn btn-outline-secondary" type="button" onClick={getPersonalNomina}><i className="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

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
                            <div className="col">
                                <table className="table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <h3>
                                                    {nominapagodetalles.sucursal?nominapagodetalles.sucursal.nombre:null}
                                                </h3>
                                            </th>
                                            <th>
                                                <h3 className='text-right'>
                                                    {nominapagodetalles.cargo?nominapagodetalles.cargo.cargosdescripcion:null} (S. {nominapagodetalles.cargo?nominapagodetalles.cargo.cargossueldo:null})
                                                </h3>
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col">
                                <table className="table">
                                    <thead>
                                       
                                        <tr>
                                            <th>Nombres</th>
                                            <th>Cédula</th>
                                        </tr>
                                        <tr>
                                            <td>{nominapagodetalles.nominanombre?nominapagodetalles.nominanombre:null} ({nominapagodetalles.nominafechadenacimiento?nominapagodetalles.nominafechadenacimiento:null}) ({nominapagodetalles.edad?nominapagodetalles.edad:null} años)</td>
                                            <td>{nominapagodetalles.nominacedula?nominapagodetalles.nominacedula:null}</td>
                                        </tr>

                                        <tr>
                                            <th>Teléfono</th>
                                            <th>Dirección</th>
                                        </tr>
                                        <tr>
                                            <td>{nominapagodetalles.nominatelefono?nominapagodetalles.nominatelefono:null}</td>
                                            <td>{nominapagodetalles.nominadireccion?nominapagodetalles.nominadireccion:null}</td>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div className="col">
                                <table className="table">
                                    <thead>
                                        <tr>
                                            <th>Ingreso</th>
                                            <th>Instrucción</th>
                                        </tr>
                                        <tr>
                                            <td>{nominapagodetalles.nominafechadeingreso?nominapagodetalles.nominafechadeingreso:null} ({nominapagodetalles.tiempolaborado?nominapagodetalles.tiempolaborado:null} años)</td>
                                            <td>{nominapagodetalles.nominagradoinstruccion?nominapagodetalles.nominagradoinstruccion:null}</td>
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