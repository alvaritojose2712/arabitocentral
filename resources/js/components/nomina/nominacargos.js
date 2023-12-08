import { useEffect } from 'react';

export default function Nomina({

	cargosDescripcion,
    setcargosDescripcion,
    cargosSueldo,
    setcargosSueldo,
    qCargos,
    setqCargos,
    indexSelectCargo,
    setindexSelectCargo,
    cargosData,
    delPersonalCargos,
    addPersonalCargos,
    getPersonalCargos,

    subViewNominaGestion,
}) {

    useEffect(() => {
        getPersonalCargos()
    }, [subViewNominaGestion])


	const setindexSelectCargoFun = id => {
		if (id == indexSelectCargo) {
			setindexSelectCargo(null)
		} else {
            let select = cargosData.filter(e=>e.id==id)
            if (select.length) {
                setcargosDescripcion(select[0].cargosdescripcion)
                setcargosSueldo(select[0].cargossueldo)
            }
            setindexSelectCargo(id)
		}
	}
	const setNuevoCargo = () => {
		setcargosDescripcion("")
        setcargosSueldo("")
		setindexSelectCargo(null)
	}
	return (
		<>
			<div className="container">
				<div className="row">
					<div className="col">
						<h1>Cargos <button className="btn btn-sm btn-success" onClick={setNuevoCargo}>Nuevo</button></h1>
						<form onSubmit={addPersonalCargos}>
							<div className="form-group">
								<label htmlFor="">
									Descripción
								</label>
								<input type="text"
									value={cargosDescripcion}
									onChange={e => setcargosDescripcion(e.target.value)}
									className="form-control" />
							</div>
							<div className="form-group">
								<label htmlFor="">
									Sueldo
								</label>
								<input type="text"
									value={cargosSueldo}
									onChange={e => setcargosSueldo(e.target.value)}
									className="form-control" />
							</div>
							
							<div className="form-group mt-1">
								{indexSelectCargo == null ?
									<button className="btn btn-outline-success btn-block" type="submit">Guardar</button>
									:
									<div className="btn-group">
										<button className="btn btn-sinapsis btn-block" type="submit">Editar</button>
										<button className="btn btn-outline-danger btn-block" onClick={delPersonalCargos} type="button"><i className="fa fa-times"></i></button>

									</div>
								}
							</div>
						</form>
						<hr />
						<form onSubmit={getPersonalCargos}>
							<div className="input-group ">
								<input type="text"
									className="form-control"
									placeholder="Buscar..."
									value={qCargos}
									onChange={e => setqCargos(e.target.value)} />
								<div className="input-group-prepend">
									<button className="btn btn-outline-secondary" type="button" onClick={getPersonalCargos}><i className="fa fa-search"></i></button>
								</div>
							</div>
						</form>
						{
							cargosData.length
								? cargosData.map((e, i) =>
									<div
										onClick={()=>setindexSelectCargoFun(e.id)}
										key={e.id}
										className={(indexSelectCargo == e.id ? "bg-sinapsis" : "bg-light text-secondary") + " card mt-2 pointer"}>
										<div className="card-header flex-row row justify-content-between">
											
											<div className="d-flex justify-content-between">
												<div><span>Sueldo. {e.cargossueldo}</span></div>
											</div>
										</div>
										<div className="card-body">
											<div className="">
												<h5
													className="card-title"
												><b>{e.cargosdescripcion}</b></h5>
											</div>
											<p className="card-text">
											</p>
										</div>
									</div>
								)
								: <div className='h3 text-center text-dark mt-2'><i>¡Sin resultados!</i></div>
						}

					</div>
				</div>
			</div>
		</>
	)
}
