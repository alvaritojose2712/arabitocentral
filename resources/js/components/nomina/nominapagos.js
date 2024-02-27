import { useState,useEffect } from 'react';

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
    moneda,
}){
    useEffect(() => {
        getPersonalNomina()
    }, [subViewNomina])
    const [selectIdPersonal, setselectIdPersonal] = useState(null)
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
                            <th>MES ANTEPASADO</th>
                            <th>MES PASADO</th>
                            <th>MES ACTUAL</th>
                            <th>PAGOS TOT.</th>
                            <th>CRÉDITOS TOT.</th>
                        </tr>
                    </thead>
                    <tbody>


                            {
                                nominaData.personal?
                                    nominaData.personal.length?
                                        nominaData.personal.map((e, i) =>
                                            <>
                                                <tr key={e.id} className={('pointer ')+(e.id==selectIdPersonal?"bg-success-light":"")} onClick={()=>setselectIdPersonal(selectIdPersonal==e.id? null: e.id)}>
                                                    <td>{e.sucursal.nombre}</td>
                                                    <td>{e.nominacedula}</td>
                                                    <td>{e.nominanombre}</td>
                                                    <td>{e.cargo.cargosdescripcion}</td>
                                                    <td>{moneda(e.mesantepasado)}</td>
                                                    <td>{moneda(e.mespasado)}</td>
                                                    <td>{moneda(e.mes)}</td>
                                                    <td className={(e.id==selectIdPersonal?"bg-success-light":"text-success")}>{moneda(e.sumPagos)}</td>
                                                    <td className={(e.id==selectIdPersonal?"bg-danger-light":"text-danger")}>{moneda(e.sumCreditos)}</td>
                                                </tr>
                                                {selectIdPersonal==e.id?
                                                    e.pagos.map(pago=>
                                                        <tr key={pago.id} className={(e.id==selectIdPersonal?"bg-success-superlight":"")}>
                                                            <td></td>
                                                            <td>PAGO</td>
                                                            <td>{pago.created_at.replace("00:00:00","")}</td>
                                                            <td>{pago.descripcion}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{moneda(pago.monto)}</td>
                                                            <td></td>
                                                        </tr>
                                                    )
                                                    :null}

                                                {selectIdPersonal==e.id?
                                                    e.creditos.map(credito=>
                                                        <tr key={credito.id} className={(e.id==selectIdPersonal?"bg-danger-light":"")}>
                                                            <td></td>
                                                            <td>CRÉDITO</td>
                                                            <td>{credito.created_at.replace("00:00:00","")}</td>
                                                            <td>{credito.sucursal.codigo}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>{moneda(credito.saldo)}</td>
                                                        </tr>
                                                    )
                                                :null}
                                            </>
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

                                <table className="table">
                                    <thead>
                                        <tr>
                                            <th>FECHA</th>
                                            <th>DESCRIPCIÓN</th>
                                            <th>SUCURSAL</th>
                                            <th>MONTO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {nominapagodetalles?
                                            nominapagodetalles.pagos.map(e=>
                                                <tr key={e.id}>
                                                    <td>{e.created_at}</td>
                                                    <td>{e.descripcion}</td>
                                                    <td>{e.sucursal.nombre}</td>
                                                    <td>{e.monto}</td>
                                                </tr>
                                            )
                                        :null}
                                    </tbody>
                                </table>
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