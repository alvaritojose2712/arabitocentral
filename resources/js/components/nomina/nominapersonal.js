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
	nominaid_sucursal_disponible,
	setnominaid_sucursal_disponible,
	qSucursalNominaOrden,
	setqSucursalNominaOrden,
	qSucursalNominaOrdenCampo,
	setqSucursalNominaOrdenCampo,
	qSucursalNominaFecha,
	setqSucursalNominaFecha,
	qSucursalNominaEstatus,
	setqSucursalNominaEstatus,
	nominaactivo,
	setnominaactivo,
	number,
	moneda,
	subViewNomina,
	selectNominaDetalles,
	setnominapagodetalles,

	shownewpersonal,
	setshownewpersonal,
	setNuevoPersonal,
	colorSucursal,
	setsubViewNominaGestion,
}) {

    useEffect(() => {
       // getPersonalNomina()
        getPersonalCargos()
        getSucursales()
    }, [subViewNominaGestion])
      

	const setIndexSelectPersonalFun = id => {

		if (id == indexSelectNomina) {
			setIndexSelectNomina(null)
			setNuevoPersonal()
			setshownewpersonal(false)

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
                setnominaid_sucursal_disponible(select[0].id_sucursal_disponible)
                setnominaactivo(select[0].activo)
				setshownewpersonal(true)
            }
			setIndexSelectNomina(id)
		}
	}
	

    const [selectIdPersonal, setselectIdPersonal] = useState(null)
	

	
	return (
		<>
			<div className="container-fluid">
				<div className="text-center p-3">
					{shownewpersonal?
						<button onClick={()=>setshownewpersonal(false)} className="btn btn-outline-sinapsis">OCULTAR NUEVO MOVIMIENTO <i className="fa fa-arrow-up"></i> </button>
						:
						<button onClick={()=>setshownewpersonal(true)} className="btn btn-outline-success ">NUEVO MOVIMIENTO <i className="fa fa-arrow-down"></i></button>
					}
				</div>
				
				{shownewpersonal?
					<div className="container card p-3">
						 <div className="btn-group m-2">
							<button className={("btn ")+(subViewNominaGestion=="personal"?"btn-sinapsis":"")} onClick={()=>setsubViewNominaGestion("personal")}>Personal</button>
							<button className={("btn ")+(subViewNominaGestion=="cargos"?"btn-sinapsis":"")} onClick={()=>setsubViewNominaGestion("cargos")}>Cargos</button>
						</div>
						<form onSubmit={addPersonalNomina}>
							<h1>Personal <button className="btn btn-sm btn-success" type='button' onClick={setNuevoPersonal}>Nuevo</button></h1>


							<div className="row">
								<div className="col">
									<div className="form-group">
										<label htmlFor="">
											Nombres y Apellidos
										</label>
										<input type="text"
											value={nominaNombre}
											onChange={e => setnominaNombre(e.target.value)}
											className="form-control" />
									</div>
								</div>
								<div className="col">
									<div className="form-group">
										<label htmlFor="">
											Cédula
										</label>
										<input type="text"
											value={nominaCedula}
											onChange={e => setnominaCedula(number(e.target.value))}
											className="form-control" />
									</div>
								</div>
							</div>

							<div className="row">
								<div className="col">
									<div className="form-group">
										<label htmlFor="">
											Teléfono
										</label>
										<input type="text"
											value={nominaTelefono}
											onChange={e => setnominaTelefono(e.target.value)}
											className="form-control" />
									</div>

								</div>
								<div className="col">
									<div className="form-group">
										<label htmlFor="">
											Dirección
										</label>
										<input type="text"
											value={nominaDireccion}
											onChange={e => setnominaDireccion(e.target.value)}
											className="form-control" />
									</div>
								</div>
							</div>

							<div className="row">
								<div className="col">
									<div className="form-group">
										<label htmlFor="">
											Fecha de Nacimiento
										</label>
										<input type="date"
											value={nominaFechadeNacimiento}
											onChange={e => setnominaFechadeNacimiento(e.target.value)}
											className="form-control" />
									</div>

								</div>
								<div className="col">
									<div className="form-group">
										<label htmlFor="">
											Fecha de Ingreso
										</label>
										<input type="date"
											value={nominaFechadeIngreso}
											onChange={e => setnominaFechadeIngreso(e.target.value)}
											className="form-control" />
									</div>
								</div>
							</div>


							<div className="row">
								<div className="col">
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

								</div>
								<div className="col">
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

								</div>
							</div>

							<div className="row">
								<div className="col">
									<div className="form-group">
										<label htmlFor="">
											Pertenece a Sucursal... 
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

								</div>
								<div className="col">
									<div className="form-group">
										<label htmlFor="">
											Sucursal Disponible para pago... 
										</label>
										<select
											value={nominaid_sucursal_disponible}
											onChange={e => setnominaid_sucursal_disponible(e.target.value)}
											className="form-control">
											<option value="">--Seleccione--</option>
											{sucursales.map(e=>
												<option key={e.id} value={e.id}>{e.nombre}</option>
											)}
										</select>
									</div>
									
								</div>
							</div>
							<div className="form-group">
								<label htmlFor="">
									ESTATUS
								</label>
								<select
									value={nominaactivo}
									onChange={e => setnominaactivo(e.target.value)}
									className="form-control">
									<option value="">--Seleccione--</option>
									<option value="1">ACTIVO</option>
									<option value="0">INACTIVO</option>
								</select>
							</div>

							
							<div className="form-group m-3 text-center">
								{indexSelectNomina == null ?
									<button className="btn btn-outline-success btn-block" type="submit">GUARDAR</button>
									:
									<div className="btn-group">
										<button className="btn btn-sinapsis btn-block" type="submit">EDITAR</button>
										<button className="btn btn-outline-danger btn-block" onClick={delPersonalNomina} type="button"><i className="fa fa-times"></i></button>

									</div>
								}
							</div>
						</form>

					</div>
				:null}

				

				<form onSubmit={getPersonalNomina} className='mt-3 mb-2'>
					<div className="input-group ">
						<input type="text"
							className="form-control"
							placeholder="Buscar..."
							value={qNomina}
							onChange={e => setqNomina(e.target.value)} />
						<select
							value={qSucursalNominaEstatus}
							onChange={e => setqSucursalNominaEstatus(e.target.value)}
							className="form-control">
							<option value="">-ESTATUS (TODOS)-</option>
							<option value="1">ACTIVOS</option>
							<option value="0">INACTIVOS</option>
						</select>
						<select
							value={qSucursalNomina}
							onChange={e => setqSucursalNomina(e.target.value)}
							className="form-control">
							<option value="">--SUCURSAL--</option>
							{sucursales.map(e=>
								<option key={e.id} value={e.id}>{e.nombre}</option>
							)}
						</select>
						<select
							value={qCargoNomina}
							onChange={e => setqCargoNomina(e.target.value)}
							className="form-control">
							<option value="">--CARGO--</option>
							{cargosData.map(e=>
								<option key={e.id} value={e.id}>{e.cargosdescripcion}</option>
							)}
						</select>


						<select
							value={qSucursalNominaOrdenCampo}
							onChange={e => setqSucursalNominaOrdenCampo(e.target.value)}
							className="form-control">
							<option value="">-ORDENAR POR-</option>
							<option value="sumPrestamos">DEUDA</option>
							<option value="nominanombre">NOMBRE</option>
							<option value="nominacargo">CARGO</option>
							<option value="nominasucursal">SUCURSAL</option>
						</select>
						<select
							value={qSucursalNominaOrden}
							onChange={e => setqSucursalNominaOrden(e.target.value)}
							className="form-control">
							<option value="asc">Ascendente</option>
							<option value="desc">Descendente</option>
						</select>
						<input type="date" className='form-control' value={qSucursalNominaFecha} onChange={e => setqSucursalNominaFecha(e.target.value)} />







						

						<div className="input-group-prepend">
							<button className="btn btn-outline-secondary" type="button" onClick={getPersonalNomina}><i className="fa fa-search"></i></button>
						</div>
					</div>
				</form>

				<table className="table">
                    <thead>
                        <tr>
							<td></td>
                            <th>Sucursal</th>
                            <th>Cédula</th>
                            <th>Nombres y Apellidos</th>
                            <th>Cargo</th>
                            <th>MES ANTEPASADO</th>
                            <th>MES PASADO</th>
                            <th>MES ACTUAL</th>
                            <th>PAGOS TOT.</th>
                            <th>PRÉSTAMOS</th>
                            <th>CRÉDITOS TOT.</th>
							<td></td>
                        </tr>
                    </thead>
                    <tbody>
						{
							nominaData.personal?
								nominaData.personal.length?
									nominaData.personal.map((e, i) =>
										<>
											<tr key={e.id} className={('pointer ')+(e.id==selectIdPersonal?"bg-success-light":"")} onClick={()=>setselectIdPersonal(selectIdPersonal==e.id? null: e.id)}>
												<td>
													<button className={"btn "+(e.activo?"btn-success":"btn-danger")}>{e.activo?"ACTIVO":"INACTIVO"}</button>
												</td>
												<td>{e.sucursal?e.sucursal.nombre:null}</td>
												<td>{e.nominacedula}</td>
												<td>
													{e.nominanombre}
													<br />
													<b>{e.nominafechadenacimiento}</b>
												</td>
												<td>
													{e.cargo.cargosdescripcion} ({e.diario.toFixed(2)} /DIA)
													<br />
													<b>{e.nominafechadeingreso}</b>
												</td>
												<td>{moneda(e.mesantepasado)}</td>
												<td>{moneda(e.mespasado)}</td>
												<td>{moneda(e.mes)} ({e.tiempotrabajado.toFixed(2)} DIAS)</td>
												<td className={("bg-success-light ")+(e.id==selectIdPersonal?"fs-3":"fs-4")}>{moneda(e.sumPagos)}</td>
												<td className={("bg-warning-light ")+(e.id==selectIdPersonal?"fs-3":"fs-4")}>{moneda(e.sumPrestamos)}</td>
												<td className={("bg-danger-light ")+(e.id==selectIdPersonal?"fs-3":"fs-4")}>{moneda(e.sumCreditos)}</td>
												<td>
													<button className="btn btn-sinapsis" onClick={()=>setIndexSelectPersonalFun(e.id)}><i className="fa fa-pencil"></i></button>
												</td>
											</tr>
											{selectIdPersonal==e.id?
												e.pagos.map(pago=>
													<tr key={pago.id} className={(e.id==selectIdPersonal?"bg-success-superlight":"")}>
														<td></td>
														<td>COBRÓ POR {pago.sucursal?pago.sucursal.codigo:null}</td>
														<td>PAGO</td>
														<td>{pago.created_at.replace("00:00:00","")}</td>
														<td>{pago.descripcion}</td>
														<td></td>
														<td></td>
														<td></td>
														<td>{moneda(pago.monto)}</td>
														<td></td>
														<td></td>
													</tr>
												)
											:null}
											{selectIdPersonal==e.id?
												e.prestamos.map(prestamo=>
													<tr key={prestamo.id} className={(e.id==selectIdPersonal?"bg-warning":"")}>
														<td></td>
														<td></td>
														<td>PRÉSTAMO</td>
														<td>{prestamo.created_at?prestamo.created_at.replace("00:00:00",""):null}</td>
														<td>{prestamo.sucursal?prestamo.sucursal.codigo:null}</td>
														<td>{prestamo.descripcion}</td>
														<td></td>
														<td></td>
														<td></td>
														<td>{moneda(prestamo.monto)}</td>
														<td></td>
													</tr>
												)
											:null}

											{selectIdPersonal==e.id?
												e.creditos.map(credito=>
													<tr key={credito.id} className={(e.id==selectIdPersonal?"bg-danger-light":"")}>
														<td></td>
														<td></td>
														<td>CRÉDITO</td>
														<td>{credito.created_at?credito.created_at.replace("00:00:00",""):null}</td>
														<td>{credito.sucursal.codigo}</td>
														<td></td>
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

				<div className="d-flex justify-content-center">
					<div className="">
						<table className="table m-4">
							<thead>
								<tr>
									<th>SUCURSAL</th>
									<th className='text-right'>CORRESPONDE</th>
									<th>PAGÓ</th>
									<th>CUADRE</th>
									<th>PRESTAMOS</th>
								</tr>
							</thead>
							<tbody>
								{nominaData.estadisticas?
									nominaData.estadisticas.map(e=>
										<tr key={e.codigo}>
											<td>
												<button className={"btn w-100 fw-bolder fs-6"} style={{backgroundColor:colorSucursal(e.codigo)}}>
													{e.codigo}
												</button>
											</td>
											<td className='text-right'>
												<span className="text-success">{moneda(e.corresponde)}</span>
											</td>
											<td>
												<span className="text-sinapsis">{moneda(e.pago)}</span>
											</td>
											<td className={(e.cuadre==0?"bg-success text-light":"bg-danger text-light")+" fs-4 text-right"}>{moneda(e.cuadre)}</td>

											<td className='text-right'>
												<span className="text-danger ">{moneda(e.prestamos)}</span>
											</td>

										</tr>
									)
								:null}
							</tbody>
						</table>

					</div>
				</div>

				{/* <table className="table table-bordered">
						<tbody>

							{
								nominaData.personal?
									nominaData.personal.length?
										nominaData.personal.map((e, i) =>
											funshowinactivo(e.activo)?
											<tr
												onClick={()=>setIndexSelectPersonalFun(e.id)}
												
												key={e.id}
												className={(indexSelectNomina == e.id ? "bg-sinapsis" : "bg-light text-secondary") + "mt-2 pointer"}>

													<td>
														<small>ID.{e.id}</small>

													</td>
													<th>
														<div className='fs-3'><span>{e.nominanombre}</span></div>

													</th>
													<th>
														<h5 className="card-title fs-4"
														><b>{e.nominacedula}</b></h5>

													</th>
													<th>

														<div>{e.cargo.cargosdescripcion}-{e.sucursal.nombre}</div>
													</th>
												<th>
													<button onClick={()=>activarPersonal(e.id)} className={"btn "+(e.activo?"btn-success":"btn-danger")}>{e.activo?"ACTIVO":"INACTIVO"}</button>
												</th>
												
												
											</tr>:null
										)
									: <div className='h3 text-center text-dark mt-2'><i>¡Sin resultados!</i></div>
								: null
							}
						</tbody>

				</table> */}
			</div>
		</>
	)
}
