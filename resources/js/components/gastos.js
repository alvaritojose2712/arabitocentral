import { useEffect, useState } from "react";
export default function Gastos({
	formatAmount,
	nominaData,
	categoriaMovBanco,
	gastosData,
	gastosQ,
	setgastosQ,
	gastosQCategoria,
	setgastosQCategoria,
	gastosQFecha,
	setgastosQFecha,
	gastosQFechaHasta,
	setgastosQFechaHasta,

	gastosDescripcion,
	setgastosDescripcion,
	gastosMonto,
	setgastosMonto,
	gastosCategoria,
	setgastosCategoria,
	gastosBeneficiario,
	setgastosBeneficiario,
	gastosFecha,
	setgastosFecha,
	setgastosMonto_dolar,              
	gastosMonto_dolar,
	setgastosTasa,              
	gastosTasa,

	subviewGastos,
	setsubviewGastos,

	selectIdGastos,
	setselectIdGastos,
	delGasto,
	saveNewGasto,
	getGastos,
	setNewGastosInput,
	setEditGastosInput,

	qBeneficiario,
	setqBeneficiario,
	qSucursal,
	setqSucursal,

	qCatGastos,
	setqCatGastos,

	getSucursales,
	sucursales,
	getPersonal,
	qNomina,
	setqNomina,
	modeMoneda,
	setmodeMoneda,
	modeEjecutor,
	setmodeEjecutor,
	addBeneficiarioList,
	listBeneficiario,
	setlistBeneficiario,
	gastosBanco,
	setgastosBanco,
	opcionesMetodosPago,
	moneda,

}) {

	useEffect(()=>{
		getPersonal(data=>{
			if (data.personal.length) {
				let id = data.personal[0].id
				if (qNomina) {
					setgastosBeneficiario(id)
				}
			}
		})
		if (qNomina=="") {
			setgastosBeneficiario("")
		}
	},[qNomina])

	useEffect(()=>{
		getSucursales(qSucursal,data=>{
			if (data.length) {
				let id = data[0].id
				if (qSucursal) {
					setgastosBeneficiario(id)
				}
			}
		})
		if (qSucursal=="") {
			setgastosBeneficiario("")
		}
	},[qSucursal])

	useEffect(()=>{
		getGastos()
	},[
		gastosQCategoria,
		gastosQFecha,
		gastosQFechaHasta,
	])

	useEffect(()=>{
		setlistBeneficiario([])
	},[modeEjecutor])

	
	return(
		<div className="container">
			<div className="d-flex justify-content-center">
                <div className="btn-group m-1">
                    <button className={("btn btn-sm ")+(subviewGastos=="cargar"?"btn-sinapsis":"")} onClick={()=>setsubviewGastos("cargar")}>Cargar</button>
                    <button className={("btn btn-sm ")+(subviewGastos=="resumen"?"btn-sinapsis":"")} onClick={()=>setsubviewGastos("resumen")}>Resumen</button>
                </div>
            </div>

			{subviewGastos=="cargar"?
				<form onSubmit={e=>{
					e.preventDefault()
					saveNewGasto()
				}} className="was-validated">
					<div className="form-group mb-2">
						<span className="form-label">Descripción</span>
						<input type="text" className="form-control form-control-lg" value={gastosDescripcion} onChange={e=>setgastosDescripcion(e.target.value)} placeholder="Descripción" required={true}/>
					</div>

					{modeMoneda=="dolar"?
						<div className="form-group mb-2">
							<span className="form-label text-success">Monto $</span>
							<div className="input-group">
								<input type="text" className="form-control text-success fs-2" value={gastosMonto_dolar} onChange={e=>setgastosMonto_dolar(formatAmount(e.target.value,"$ "))} placeholder="Monto $" required={true}/>
								<button className="btn btn-sinapsis" type="button" onClick={()=>setmodeMoneda("bs")}><i className="fa fa-refresh"></i> Bs</button>
							</div>
						</div>
					:null}

					{modeMoneda=="bs"?
						<div className="form-group mb-2">
							<span className="form-label text-sinapsis">Monto Bs </span>
							<div className="row">
								<div className="col">
									<input type="text" className="form-control text-sinapsis fs-2" value={gastosMonto} onChange={e=>setgastosMonto(formatAmount(e.target.value,"Bs. "))} placeholder="Monto Bs" required={true} />
								</div>
								<div className="col-3">
									<div className="input-group">
										<input type="text" className="form-control text-sinapsis fs-2" value={gastosTasa} onChange={e=>setgastosTasa(e.target.value)} placeholder="Tasa" required={true} />
										<button className="btn btn-success" type="button" onClick={()=>setmodeMoneda("dolar")}><i className="fa fa-refresh"></i> $</button>

									</div>
								</div>
							</div>
						</div>
					:null}
					<div className="form-group mb-2">
						<select className="form-control" 
						value={gastosBanco} 
						onChange={e=>setgastosBanco(e.target.value)} required={true}>
							<option value="">-Método-</option>
							{opcionesMetodosPago.map(e=>
								<option value={e.codigo} key={e.id}>{e.descripcion}</option>
							)}
						</select>
					</div>
					<div className="form-group mb-2">
						<span className="form-label">Fecha</span>
						<input type="date" className="form-control form-control-lg" value={gastosFecha} onChange={e=>setgastosFecha(e.target.value)} required={true} />
					</div>

					<div className="form-group mb-2">
						<span className="form-label">Categoría</span>
						<select className="form-control" 
						value={gastosCategoria} 
						onChange={e=>setgastosCategoria(e.target.value)} required={true}>
							<option value="">-Categoría-</option>
							{categoriaMovBanco.map(e=>
								<option value={e.id} key={e.id}>{e.descripcion}</option>
							)}
						</select>
					</div>

					<div className="form-group mb-2">
						<span className="form-label">Ejecutor</span>
							{modeEjecutor=="personal"?
								<div className="row">
									<div className="col-md-2">
										<div className="input-group">
											<button className="btn btn-sinapsis" type="button" onClick={()=>setmodeEjecutor("sucursal")}><i className="fa fa-home"></i> </button>
											<input type="text" className="form-control is-invalid" value={qNomina} onChange={e=>setqNomina(e.target.value)} placeholder="Buscar..." />
										</div>
									</div>
									<div className="col">
										<div className="input-group">
											<button className="btn btn-success" type="button" onClick={()=>addBeneficiarioList("add")}><i className="fa fa-arrow-right"></i></button>
											<select className={("form-select ")} 
											value={gastosBeneficiario} 
											onChange={e=>setgastosBeneficiario(e.target.value)} required={true}>
												<option value="">-Personal-</option>
												{nominaData.personal?nominaData.personal.length?nominaData.personal.map(e=>
													<option value={e.id} key={e.id}>{e.nominanombre} {e.nominacedula}</option>
												):null:null}
											</select>
										</div>
									</div>
								</div>
							:null}

							{modeEjecutor=="sucursal"?
								<div className="row">
									<div className="col-md-2">
										<div className="input-group">
											<button className="btn btn-success" type="button" onClick={()=>setmodeEjecutor("personal")}><i className="fa fa-user"></i></button>
											<input type="text" className="form-control is-invalid" value={qSucursal} onChange={e=>setqSucursal(e.target.value)} placeholder="Buscar..." />
										</div>
									</div>
									<div className="col">
										<div className="input-group">

											<button className="btn btn-success" type="button" onClick={()=>addBeneficiarioList("add")}><i className="fa fa-arrow-right"></i></button>
											<select className={("form-select ")} 
											value={gastosBeneficiario} 
											onChange={e=>setgastosBeneficiario(e.target.value)} required={true}>
												<option value="">-Sucursal-</option>
												{sucursales.map(e=>
													<option value={e.id} key={e.id}>{e.codigo}</option>
												)}
											</select>
										</div>
									</div>
								</div>
							:null}
							
						

						{listBeneficiario.length?
							<div className="border bg-light p-3 m-2">
								{listBeneficiario.map(e=>
									<button key={e.id} className="btn mb-1 me-1" onClick={()=>addBeneficiarioList("del",e.id)} style={{backgroundColor:e.color?e.color:"coral"}} onDoubleClick={()=>addBeneficiarioList("del",e.id)}>
										{e.codigo?e.codigo:e.nominanombre}
									</button>	
								)}
							</div>
						:null}
					</div>

					<div className="text-center">
						<button className="btn btn-success btn-lg"><i className="fa fa-save"></i> Guardar</button>
					</div>
				</form>				
			:null}

			{subviewGastos=="resumen"?
				<>
					<form onSubmit={event=>{
						event.preventDefault()
						getGastos()
					}}>
						<div className="input-group">
							<input type="text" className="form-control fs-3" value={gastosQ} onChange={e=>setgastosQ(e.target.value)} placeholder="Buscar..."/>
							<select className="form-control" 
							value={gastosQCategoria} 
							onChange={e=>setgastosQCategoria(e.target.value)}>
								<option value="">-Buscar por Categoría-</option>
								{categoriaMovBanco.map(e=>
									<option value={e.id} key={e.id}>{e.descripcion}</option>
								)}
							</select>
							
							<input type="date" className="form-control fs-3" value={gastosQFecha} onChange={e=>setgastosQFecha(e.target.value)} />
							<input type="date" className="form-control fs-3" value={gastosQFechaHasta} onChange={e=>setgastosQFechaHasta(e.target.value)} />

							<button className="btn btn-success"><i className="fa fa-search"></i></button>
						</div>
					</form>
					<table className="table">
						<thead>
							<tr>
								<th>FECHA</th>
								<th>DESCRIPCIÓN</th>
								<th>MONTO</th>
								<th className="bg-warning text-danger fs-3 text-right">
								{gastosData?gastosData.sum?(
									<span>
										{moneda(gastosData.sum)}
									</span>
								):null:null}
								</th>

								<th>SUCURSAL / PERSONA</th>
							</tr>
						</thead>
						<tbody>
							{gastosData?gastosData.data?gastosData.data.map(e=>
								<tr key={e.id}>
									<td>{e.created_at}</td>
									<td>{e.loteserial}</td>
									<td className="text-danger fs-4 text-right" colSpan={2}>
										{e.monto_liquidado!=0?("Bs. "+moneda(e.monto_liquidado)+" / "+e.tasa+" = $ "+moneda(e.bs)):""}
										{e.monto_dolar!=0?"$ "+moneda(e.monto_dolar):""}
									</td>
									<td>
										{e.sucursal?e.sucursal.codigo:null}
										{e.beneficiario?" / "+e.beneficiario.nominanombre:null}
									</td>
								</tr>
							):null:null}
						</tbody>
					</table>
				</>
			:null}
			
		</div>
	)
}