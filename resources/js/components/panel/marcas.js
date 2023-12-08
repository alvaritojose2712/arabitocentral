export default function Marcas({
	addNewMarcas,
	marcasDescripcion,
	setmarcasDescripcion,
	indexSelectMarcas,
	setIndexSelectMarcas,
	qBuscarMarcas,
	setQBuscarMarcas,
	delMarcas,
	marcas,
    getMarcas,
}) {



	const setIndexSelectCatFun = e => {
		let index = e.currentTarget.attributes["data-index"].value

		if (index == indexSelectMarcas) {
			setIndexSelectMarcas(null)
			setmarcasDescripcion("")

		} else {
			setIndexSelectMarcas(index)
			setmarcasDescripcion(marcas[index].descripcion)

		}
	}
	const setNuevoCat = () => {
		setmarcasDescripcion("")
		setIndexSelectMarcas(null)
	}
	return (
		<>
			<div className="container-fluid">
				<div className="row">
					<div className="col">
						<h1>Marcas <button className="btn btn-sm btn-success" onClick={setNuevoCat}>Nuevo</button></h1>

						<div className="">
							<form className="input-group " onSubmit={(e)=>{e.preventDefault();getMarcas()}}>
								<input type="text"
									className="form-control"
									placeholder="Buscar..."
									value={qBuscarMarcas}
									onChange={e => setQBuscarMarcas(e.target.value)} />
								<div className="input-group-prepend">
									<button className="btn btn-outline-secondary" type="button"><i className="fa fa-search"></i></button>
								</div>
							</form>
						</div>
						{
							marcas.length
								? marcas.map((e, i) =>
									<div
										onClick={setIndexSelectCatFun}
										data-index={i}
										key={e.id}
										className={(indexSelectMarcas == i ? "bg-sinapsis" : "bg-light text-secondary") + " card mt-2 pointer"}>
										<div className="card-header flex-row row justify-content-between">
											<div>
												<small>ID.{e.id}</small>
											</div>
											<div className="d-flex justify-content-between">
												<div><span>{e.descripcion}</span></div>
											</div>
										</div>
										<div className="card-body">
											<div className="">
												<h5
													className="card-title"
												><b>{e.descripcion}</b></h5>
											</div>
											<p className="card-text">
											</p>
										</div>
									</div>
								)
								: <div className='h3 text-center text-dark mt-2'><i>¡Sin resultados!</i></div>
						}

					</div>
					<div className="col">

						<form onSubmit={addNewMarcas}>
							<div className="form-group">
								<label htmlFor="">
									Descripción
								</label>
								<input type="text"
									value={marcasDescripcion}
									onChange={e => setmarcasDescripcion(e.target.value)}
									className="form-control" />
							</div>
							<div className="form-group mt-1">
								{indexSelectMarcas == null ?
									<button className="btn btn-outline-success btn-block" type="submit">Guardar</button>
									:
									<div className="btn-group">
										<button className="btn btn-sinapsis btn-block" type="submit">Editar</button>
										<button className="btn btn-outline-danger btn-block" onClick={delMarcas} type="button"><i className="fa fa-times"></i></button>

									</div>
								}
							</div>
						</form>
					</div>
				</div>
			</div>
		</>
	)
}
