import { useEffect, useState } from 'react';

export default function NominaPersonal({
    nominaNombre,
    setnominaNombre,
    nominaCedula,
    setnominaCedula,
    nominaTelefono,
    setnominaTelefono,
    nominaDireccion,
    setnominaDireccion,
    nominaFechadeNacimiento,
    setnominaFechadeNacimiento,
    nominaFechadeIngreso,
    setnominaFechadeIngreso,
    nominaGradoInstruccion,
    setnominaGradoInstruccion,
    nominaCargo,
    setnominaCargo,
    nominaSucursal,
    setnominaSucursal,
    indexSelectNomina,
    setIndexSelectNomina,

    qNomina,
    setqNomina,
    qSucursalNomina,
    setqSucursalNomina,
    qCargoNomina,
    setqCargoNomina,
    
    nominaData,
    delPersonalNomina,
    addPersonalNomina,
    getPersonalNomina,
    
    cargosData,
    sucursales,

    subViewNominaGestion,
    getPersonalCargos,
	getSucursales,
	activarPersonal,
}) {

    useEffect(() => {
        getPersonalNomina()
        getPersonalCargos()
        getSucursales()
    }, [subViewNominaGestion])
      

	const setIndexSelectPersonalFun = id => {

		if (id == indexSelectNomina) {
			setIndexSelectNomina(null)
		} else {
            let select = nominaData.personal.filter(e=>e.id==id)
            if (select.length) {
                setnominaNombre(select[0].nominanombre)
                setnominaCedula(select[0].nominacedula)
                setnominaTelefono(select[0].nominatelefono)
                setnominaDireccion(select[0].nominadireccion)
                setnominaFechadeNacimiento(select[0].nominafechadenacimiento)
                setnominaFechadeIngreso(select[0].nominafechadeingreso)
                setnominaGradoInstruccion(select[0].nominagradoinstruccion)
                setnominaCargo(select[0].nominacargo)
                setnominaSucursal(select[0].nominasucursal)
            }
			setIndexSelectNomina(id)
		}
	}
	const setNuevoPersonal = () => {
		setnominaNombre("")
        setnominaCedula("")
        setnominaTelefono("")
        setnominaDireccion("")
        setnominaFechadeNacimiento("")
        setnominaFechadeIngreso("")
        setnominaGradoInstruccion("")
        setnominaCargo("")
        setnominaSucursal("")
		setIndexSelectNomina(null)
	}

	const [showInactivoValue, setshowInactivoValue] = useState(1)
	const showInactivos = () => {
		let inicial = null
		switch (showInactivoValue) {
			case 0:
				inicial = 1
			break;
			case 1:
				inicial = 2
			break;
			case 2:
				inicial = 0
			break;
			
		}
		setshowInactivoValue(inicial)
	}

	const funshowinactivo = activo => {
		if (showInactivoValue==0) {
			if (activo==0) {
				return true
			}
		}

		if (showInactivoValue==1) {
			if (activo==1) {
				return true
			}
		}
		
		if (showInactivoValue==2) {
			return true
		}
		return false
	}
	return (
		<>
			<div className="container-fluid">
				<div className="row">
					<div className="col">
						<h1>Personal <button className="btn btn-sm btn-success" onClick={setNuevoPersonal}>Nuevo</button></h1>
						<form onSubmit={addPersonalNomina}>
							<div className="form-group">
								<label htmlFor="">
									Nombres y Apellidos
								</label>
								<input type="text"
									value={nominaNombre}
									onChange={e => setnominaNombre(e.target.value)}
									className="form-control" />
							</div>
							<div className="form-group">
								<label htmlFor="">
									Cédula
								</label>
								<input type="text"
									value={nominaCedula}
									onChange={e => setnominaCedula(e.target.value)}
									className="form-control" />
							</div>

                            <div className="form-group">
								<label htmlFor="">
									Teléfono
								</label>
								<input type="text"
									value={nominaTelefono}
									onChange={e => setnominaTelefono(e.target.value)}
									className="form-control" />
							</div>

                            <div className="form-group">
								<label htmlFor="">
									Dirección
								</label>
								<input type="text"
									value={nominaDireccion}
									onChange={e => setnominaDireccion(e.target.value)}
									className="form-control" />
							</div>
                            <div className="form-group">
								<label htmlFor="">
									Fecha de Nacimiento
								</label>
								<input type="date"
									value={nominaFechadeNacimiento}
									onChange={e => setnominaFechadeNacimiento(e.target.value)}
									className="form-control" />
							</div>

                            <div className="form-group">
								<label htmlFor="">
									Fecha de Ingreso
								</label>
								<input type="date"
									value={nominaFechadeIngreso}
									onChange={e => setnominaFechadeIngreso(e.target.value)}
									className="form-control" />
							</div>


							<div className="form-group">
								<label htmlFor="">
									Grado de Instrucción
								</label>
								<select
									value={nominaGradoInstruccion}
									onChange={e => setnominaGradoInstruccion(e.target.value)}
									className="form-control">
									<option value="">--Seleccione--</option>
									<option value="Basica">Basica</option>
									<option value="Bachiller">Bachiller</option>
									<option value="TSU">TSU</option>
									<option value="Profesional">Profesional</option>
								</select>
							</div>

                            <div className="form-group">
								<label htmlFor="">
									Cargo
								</label>
								<select
									value={nominaCargo}
									onChange={e => setnominaCargo(e.target.value)}
									className="form-control">
									<option value="">--Seleccione--</option>
                                    {cargosData.map(e=>
									    <option key={e.id} value={e.id}>{e.cargosdescripcion} - ${e.cargossueldo}</option>
                                    )}
								</select>
							</div>
                            
                            <div className="form-group">
								<label htmlFor="">
									Sucursal
								</label>
								<select
									value={nominaSucursal}
									onChange={e => setnominaSucursal(e.target.value)}
									className="form-control">
									<option value="">--Seleccione--</option>
                                    {sucursales.map(e=>
									    <option key={e.id} value={e.id}>{e.nombre}</option>
                                    )}
								</select>
							</div>
							
							<div className="form-group mt-1">
								{indexSelectNomina == null ?
									<button className="btn btn-outline-success btn-block" type="submit">Guardar</button>
									:
									<div className="btn-group">
										<button className="btn btn-sinapsis btn-block" type="submit">Editar</button>
										<button className="btn btn-outline-danger btn-block" onClick={delPersonalNomina} type="button"><i className="fa fa-times"></i></button>

									</div>
								}
							</div>
						</form>
						<hr />

						<form onSubmit={getPersonalNomina} className='mt-3'>
							<div className="input-group ">
								<input type="text"
									className="form-control"
									placeholder="Buscar..."
									value={qNomina}
									onChange={e => setqNomina(e.target.value)} />
								<button className={("btn ") + (showInactivoValue==0?"btn-danger":(showInactivoValue==1?"btn-success":""))} onClick={()=>showInactivos()}><i className="fa fa-eye"></i></button>
							</div>
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

                                

								<div className="input-group-prepend">
									<button className="btn btn-outline-secondary" type="button" onClick={getPersonalNomina}><i className="fa fa-search"></i></button>
								</div>
							</div>
							
						</form>
						{
							nominaData.personal?
                                nominaData.personal.length?
                                    nominaData.personal.map((e, i) =>
                                        funshowinactivo(e.activo)?
										<div
                                            onClick={()=>setIndexSelectPersonalFun(e.id)}
                                            
                                            key={e.id}
                                            className={(indexSelectNomina == e.id ? "bg-sinapsis" : "bg-light text-secondary") + " card mt-2 pointer"}>
                                            <div className="card-header flex-row row justify-content-between">
                                                <div>
                                                    <small>ID.{e.id}</small>
                                                </div>
                                                <div className="d-flex justify-content-between">
                                                    <div><span>{e.nominanombre}</span></div>
                                                    <div>{e.cargo.cargosdescripcion}-{e.sucursal.nombre}</div>
                                                </div>
                                            </div>
                                            <div className="card-body">
                                                <div className="">
                                                    <h5
                                                        className="card-title"
                                                    ><b>{e.nominacedula}</b></h5>
                                                </div>
                                                <p className="card-text">
                                                </p>
                                            </div>
											<button onClick={()=>activarPersonal(e.id)} className={"btn "+(e.activo?"btn-success":"btn-danger")}>{e.activo?"ACTIVO":"INACTIVO"}</button>
											
                                        </div>:null
                                    )
                                : <div className='h3 text-center text-dark mt-2'><i>¡Sin resultados!</i></div>
                            : null
						}
					</div>
				</div>

			</div>
		</>
	)
}
